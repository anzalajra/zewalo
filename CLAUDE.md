# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Zewalo is a multi-tenant SaaS rental management platform built with Laravel 12, Filament 4, and stancl/tenancy v3. It manages equipment/product rentals with full e-commerce flow (catalog, cart, checkout), customer portal, invoicing, and double-entry accounting.

## Tech Stack

- **PHP 8.2+**, **Laravel 12**, **Filament 4** (admin panels)
- **stancl/tenancy v3** for multi-tenancy (database-per-tenant via PostgreSQL 16)
- **Livewire** for dynamic frontend components
- **Tailwind CSS v4**, **Vite 7**, **Alpine.js**
- **PostgreSQL 16** (Docker Alpine), **Redis**, **Mailpit** (dev email)
- **Cloudflare R2** (S3-compatible) for file storage
- **bezhansalleh/filament-shield** for role/permission management
- **lara-zeus/sky** for CMS (blog/pages)
- **barryvdh/laravel-dompdf** for PDF generation

## Development Commands

```bash
# Start localhost (http://localhost) — URUTAN WAJIB:
# 1. Buka Docker Desktop dulu, tunggu sampai fully started
# 2. Baru jalankan:
docker compose -f docker-compose.dev.yml up -d
# Akses: http://localhost (port 80, Nginx)

# Stop dev containers
docker compose -f docker-compose.dev.yml down

# Clear cache di dalam container (bukan di host!)
docker compose -f docker-compose.dev.yml exec app php artisan view:clear
docker compose -f docker-compose.dev.yml exec app php artisan config:clear
docker compose -f docker-compose.dev.yml exec app php artisan cache:clear

# Run migration di dalam container
docker compose -f docker-compose.dev.yml exec app php artisan migrate --path=database/migrations/central --database=central

# CATATAN: Jangan jalankan `php artisan` langsung di host untuk clear cache,
# karena app berjalan di dalam Docker container, bukan di host.

# Full dev environment tanpa Docker (pakai XAMPP, port 8000)
composer dev

# Individual commands
php artisan serve
npm run dev
php artisan queue:listen

# Tests
php artisan test
php artisan test --filter=TestClassName
php artisan test --filter=test_method_name

# Linting
./vendor/bin/pint     # Laravel Pint (PSR-12 style fixer)

# Build frontend assets
npm run build
```

## Multi-Tenancy Architecture

This is the most critical architectural concept. The app uses **domain-based tenant identification** with **separate databases per tenant**.

### Two Database Contexts
- **Central database** (`central` connection): Stores tenants, domains, subscription plans, and central admin users. Migrations live in `database/migrations/central/`.
- **Tenant databases** (`tenant` connection, dynamically set): Each tenant gets a `tenant_{uuid}` PostgreSQL database. Migrations live in `database/migrations/tenant/`.

### Migration Commands
```bash
# Central migrations only
php artisan migrate --path=database/migrations/central --database=central

# Tenant migrations (runs against ALL tenant databases)
php artisan tenants:migrate

# Single tenant migration
php artisan tenants:migrate --tenants={tenant-id}
```

### Two Filament Panels
- **Central Panel** (`sa.{APP_DOMAIN}/admin`): `CentralPanelProvider` — Manages tenants and subscription plans. Runs on subdomain `sa.` of the central domain (e.g., `sa.localhost/admin`, `sa.zewalo.test/admin`).
- **Admin Panel** (`{tenant-domain}/admin`): `AdminPanelProvider` — Tenant-scoped admin panel for managing rentals, products, customers, finance, etc. Uses `InitializeTenancyByDomain` middleware.

### Tenant Lifecycle
On `TenantCreated` event (see `TenancyServiceProvider`): CreateDatabase → MigrateDatabase → CreateTenantStorageFolder. On delete: DeleteDatabase → DeleteTenantStorageFolder.

### Tenant Setup Wizard
New tenants get `setup_status = 'pending'` on creation (set in `CreateTenantJob`). The `Tenant` model has `setup_status` as a custom column with helpers: `needsSetup()`, `completeSetup()`, `skipSetup()`, `getSetupCurrentStep()`, `setSetupCurrentStep()`.

**Wizard Page:** `app/Filament/Pages/SetupWizard.php` — 4-step Filament Wizard:
1. **Store Info** — logo, name, category, address, phone, email, theme preset → saves to `Setting` + syncs to DocumentLayout via `SettingsSyncService`
2. **Operational Hours** — Alpine.js schedule table (same pattern as `RentalSettings`) → saves to `Setting` (operational_schedule, operational_days)
3. **Payment** — bank transfer toggle + details → saves to `Setting` group `payment`
4. **Seed Data** — optional import of category-specific template products via `TenantTemplateSeeder`

**Flow Control:**
- `RedirectToSetupWizard` middleware — auto-redirects to wizard on first admin login (once per session), then shows banner
- Render hook banner at `panels::content.start` (blue, above subscription warning)
- Skip button available → sets `setup_status = 'skipped'`

**Template Data Source:** Template data **lives in the central database** (tables `tenant_templates`, `tenant_template_brands`, `tenant_template_product_categories`, `tenant_template_products`, `tenant_template_product_units`, `tenant_template_product_variations`, `tenant_template_unit_kits`). Manage via **Central Admin → Tenant Management → Tenant Templates** (`TenantTemplateResource`). One active template per `TenantCategory`. Note: Kit dimodelkan **per-unit** (mirror tenant `unit_kits`), bukan product-level bundle. Tabel lama `tenant_template_product_components` sudah di-drop.

**Seeder entry point:** `database/seeders/Tenant/TenantTemplateSeeder.php::run($categorySlug, $tenantId)` queries `TenantCategory::on('central')->activeTemplate` and delegates to `App\Services\TemplateImporter`. If no active template exists → no-op (logged at info). Legacy PHP seeders under `database/seeders/tenant/Templates/` are retained ONLY for the one-off `templates:import-legacy` command that backfills existing data into the central DB; they are not invoked at runtime.

**TemplateImporter service** (`App\Services\TemplateImporter::import($template, $tenantId)`):
- Wrapped in `DB::transaction` on the tenant connection. Multi-pass:
  1. Create `Brand` per template brand — fallback to existing active brand or "Umum" (`products.brand_id` NOT NULL).
  2. Create `Category` per template product category.
  3. Create `Product` — copy image from central R2 to tenant R2 prefix (see below).
  4. Create `ProductUnit` (`serial_number = TMPL-{SLUG}-{suffix}`) + `ProductVariation`.
  5. Per Unit: create `UnitKit` dari template kits. Kalau `track_by_serial=true` dan kit `serial_suffix` cocok dengan ProductUnit yang baru dibuat (`TMPL-{SLUG}-{suffix}`) → `linked_unit_id` di-set otomatis.
- **Image copy**: `TenantStorageService::copyFromCentral($absoluteCentralPath, $tenantRelativePath, $tenantId)` — uses raw `Storage::disk('r2')->copy()`. Source is absolute (e.g. `central/templates/<template_id>/foo.jpg`), target gets auto-prefixed to `tenant_<id>/products/...`. Retries 3x with exponential backoff (100/300/900ms) on failure. Final failure = **soft-fail**: log + `TenantIssueReporter::report()` with code `TEMPLATE_IMAGE_COPY_FAILED`, set `image = null`, continue import. Structural failures (brand/category/product/unit/kit inserts) hard-fail the whole transaction.

**Adding template data via Central Admin:**
1. Navigate to `sa.{domain}/admin/tenant-templates`
2. Create template → select `Tenant Category` (unique, 1:1) → save
3. Add Brands (mark one as "default"), then Product Categories, then Products
4. In each Product: set image (uploaded to `central/templates/<template_id>/`, R2 private visibility), define Units & Kit (Tab — kit nested di dalam tiap unit, mirror pola tenant), Variations (Tab)

**Legacy import command:** `php artisan templates:import-legacy` (add `--fresh` to wipe & reimport). Reads `$legacySeederMap` in `TenantTemplateSeeder`, reflects into `categories()` + `products()` of each legacy PHP seeder, inserts into central template tables with default "Umum" brand. Skips categories that already have a template.

- **Known limitations (v1)**: single `image_path` per template product (no gallery); no template-level warehouses/variations-per-unit support yet.
- **Error handling**: if the seeder throws, `SetupWizard::submit()` reports it via `TenantIssueReporter` (code `SETUP_WIZARD_SEED_FAILED`) and shows the reference `ZWL-ERR-XXXXXX` to the user. See the **Tenant Issues** section below.

**Backfill existing tenants:** `php artisan tenants:backfill-setup-status` — checks if tenant has site_logo or products, marks as `completed` if so.

### Central Domains
Defined in `config/tenancy.php`: `127.0.0.1`, `localhost`, `zewalo.test`, and `APP_DOMAIN` env var. Any other domain triggers tenant resolution.

### Custom PostgreSQL Database Manager
`App\Services\Tenancy\PostgreSQL16DatabaseManager` — custom DB manager for Docker Alpine PostgreSQL 16 compatibility (handles template0, collation).

## Application Structure

### Filament Resources (Tenant Admin Panel)
Located in `app/Filament/Resources/` — Rentals, Products, ProductUnits, Customers, Invoices, Quotations, Deliveries, Discounts, Warehouses, etc.

### Filament Central Admin Pages
Located in `app/Filament/Central/Pages/` — System-level settings pages:
- `BrandingSettings.php` — Logo, nama web, favicon, SEO meta (group: `branding`)
- `EmailSettings.php` — SMTP/SES email config (group: `mail`)
- `R2StorageSettings.php` — Cloudflare R2 credentials (group: `r2`)
- `ServerSettings.php` — Read-only server health info
- `DatabaseManagement.php` — Database operations
- `R2FileBrowser.php` — Browse R2 storage
- `StorageManagement.php` — Local storage management
- `LogViewer.php` — Aggregated log viewer (Laravel, queue worker, scheduler, PHP errors). Backed by `LogViewerService` which reads every `*.log` under `storage/logs/`, parses Laravel-format entries (`[date] env.LEVEL: message + context`), supports filtering by file / level / keyword, download, and truncate.

### Filament Central Admin Resources
Located in `app/Filament/Central/Resources/`:
- `TenantResource`, `TenantCategoryResource`, `TenantTemplateResource`, `UserResource`
- `SubscriptionPlanResource`, `SaasInvoiceResource`
- `PaymentGatewayResource`, `PaymentMethodResource`
- `TranslationResource`

`TenantTemplateResource` backs **Central Admin → Tenant Management → Tenant Templates**. Template data (brands, product categories, products with images, units, variations, kit components) is stored in central DB tables (`tenant_templates` + children) and imported into tenant DB on Setup Wizard step 4. See **Tenant Setup Wizard** section for the full flow.

### Filament Clusters (Tenant)
- `app/Filament/Clusters/Finance/` — Double-entry accounting: journal entries, chart of accounts, finance transactions, tax reports, depreciation
- `app/Filament/Clusters/Settings/` — Tenant-level settings pages:
  - `GeneralSettings.php` — site_name, site_logo, company info
  - `AppearanceSettings.php` — theme_preset, theme_color, navigation_layout
  - `DocumentLayoutSettings.php` — doc_logo, doc colors, fonts, PDF layout

### Key Services
- `App\Services\JournalService` — Double-entry journal posting
- `App\Services\TaxService` / `TaxReportService` — Tax calculations and reporting
- `App\Services\PromotionService` — Discount/promotion logic
- `App\Services\ThemeService` — Tenant theme customization (maps presets to Filament Color objects)
- `App\Services\CentralBrandingService` — Static helper for central branding values (siteName, logoUrl, faviconUrl, ogImageUrl, metaKeywords)
- `App\Services\Storage\` — R2/S3 storage integration

### Customer-Facing Routes (web.php)
The app has a setup wizard flow (checks `storage/installed` file). Once installed:
- Public: catalog, blog (Zeus Sky), landing pages
- Customer auth: login/register/forgot-password (custom, not Breeze — Breeze is dev-only)
- Customer portal: dashboard, rentals, documents, cart, checkout
- Tenant registration: `/register-tenant` (Livewire)
- Central login portal: `/masuk` (Livewire)
- Tenant login portal: `/login-tenant` (Livewire)

### Models
Central models (`central` connection): `Tenant`, `Domain`, `SubscriptionPlan`, `CentralSetting`
Tenant models (default connection): `Product`, `ProductUnit`, `Rental`, `RentalItem`, `Customer`, `Invoice`, `Quotation`, `Delivery`, `Cart`, `Warehouse`, `Setting`, and finance models under `App\Models\Finance\`

### CentralSetting Model (`app/Models/CentralSetting.php`)
Key-value store for platform-wide settings (central database).
- **Table**: `central_settings` — columns: `id`, `group`, `key`, `value`, `is_encrypted`, `label`, `sort_order`
- **Groups**: `branding` (logo, site name, SEO), `mail` (email config), `r2` (storage), `general`
- **Methods**: `CentralSetting::get($key, $default)`, `CentralSetting::set($key, $value, $encrypted, $group)`, `CentralSetting::getGroup($group)`
- **Caching**: 1-hour TTL per key, auto-cleared on save/delete
- **Encryption**: Supports auto-encryption for sensitive fields (passwords, API keys)

### Setting Model (`app/Models/Setting.php`) — Tenant-scoped
Key-value store per tenant (tenant database).
- **Table**: `settings` — columns: `id`, `group`, `key`, `value`, `type`, `label`, `description`, `sort_order`
- **Methods**: `Setting::get($key, $default)`, `Setting::set($key, $value)`
- **Caching**: 1-hour TTL, auto-invalidated
- **Default groups**: `general` (site_name, site_tagline, etc.), `rental`, `whatsapp`

### Providers
- `AppServiceProvider` — Loads central migrations, registers observers, sets up View composers (PDF doc_settings, theme CSS, central branding), applies central mail & branding config, tenant-level overrides
- `CentralSettingsServiceProvider` — Applies R2 storage settings to filesystem config at boot
- `Filament/CentralPanelProvider` — Central admin panel config (dynamic brand from CentralSetting)
- `Filament/AdminPanelProvider` — Tenant admin panel config (dynamic brand from Setting)
- `TenancyServiceProvider` — Tenant lifecycle events (create/delete DB, storage folders)

## Central Branding & SEO System

Platform-wide branding dikelola via **Central Admin → System → Branding & SEO** (`BrandingSettings.php`), disimpan di `CentralSetting` group `branding`.

### Settings Keys (group: `branding`)
| Key | Keterangan |
|-----|-----------|
| `branding_site_name` | Nama platform (default: "Zewalo") |
| `branding_site_description` | Meta description untuk SEO |
| `branding_logo` | Logo utama (file path di disk `r2`, dir `central/branding`) |
| `branding_favicon` | Favicon (file path di disk `r2`, dir `central/branding`) |
| `branding_meta_keywords` | SEO keywords (comma-separated) |
| `branding_og_image` | Open Graph image untuk social sharing (disk `r2`, dir `central/branding`) |

### Hierarki Brand Name (terendah → tertinggi)
1. `.env` `APP_NAME` / `config('app.name')` defaults
2. `CentralSetting::get('branding_site_name')` — override `config('app.name')` di `AppServiceProvider` boot
3. Tenant-specific `Setting::get('site_name')` — override lagi jika dalam tenant context

### Bagaimana Branding Diterapkan
- **View Composer** di `AppServiceProvider` inject `$centralBrandName`, `$centralBrandLogo`, `$centralBrandDesc`, `$centralFavicon` ke views: `layouts.landing`, `landing.partials.header`, `landing.partials.footer`, `livewire.register-tenant`, `livewire.tenant-login`
- **Blade component** `<x-central-brand-logo>` (`resources/views/components/central-brand-logo.blade.php`) — reusable logo: tampilkan uploaded image jika ada, fallback ke inline SVG default. Props: `class`, `showName`, `nameClass`
- **Email** otomatis ikut karena `config('app.name')` di-override dari `branding_site_name`
- **Central Admin Panel** (`CentralPanelProvider`) — brand name, logo, favicon dinamis dari CentralSetting
- **Landing layout** (`layouts/landing.blade.php`) — dynamic `<title>`, `<meta description>`, `<meta keywords>`, Open Graph tags, favicon
- **Helper**: `App\Services\CentralBrandingService` — static methods: `siteName()`, `siteDescription()`, `logoUrl()`, `faviconUrl()`, `ogImageUrl()`, `metaKeywords()`, `hasLogo()`

### Menambah Central Settings Page Baru
Pattern (ikuti `EmailSettings.php` atau `BrandingSettings.php`):
1. Buat class di `app/Filament/Central/Pages/` — extend `Page implements HasForms`, use `InteractsWithForms`
2. Define `$navigationIcon`, `$navigationGroup = 'System'`, `$navigationSort`, `$navigationLabel`
3. Set `$view` ke `filament.central.pages.{nama-page}`
4. Implement `mount()` load via `CentralSetting::getGroup('group_name')`, `form()` define fields, `save()` persist via `CentralSetting::set()`
5. Buat blade view di `resources/views/filament/central/pages/{nama-page}.blade.php` (form + submit button wrapped in `<x-filament-panels::page>`)
6. No migration needed — `central_settings` table is flexible key-value

## View Layouts & Templates

### Layouts (`resources/views/layouts/`)
| File | Digunakan Untuk |
|------|----------------|
| `landing.blade.php` | Landing pages (zewalo.com public pages) — SEO meta, OG tags |
| `frontend.blade.php` | Tenant storefront (catalog, cart, checkout) — uses `Setting::get('site_name')` |
| `guest.blade.php` | Customer auth pages (login, register) — tenant-scoped |
| `app.blade.php` | Customer portal (dashboard, rentals) — tenant-scoped |

### Landing Page Partials (`resources/views/landing/partials/`)
- `header.blade.php` — Sticky navbar, mega menu (features, solutions), uses `<x-central-brand-logo>`
- `footer.blade.php` — Footer links, social icons, uses `<x-central-brand-logo>`
- `hero.blade.php` — Hero section

### Livewire Auth Pages (`resources/views/livewire/`)
- `register-tenant.blade.php` — Multi-step tenant registration form, uses `<x-central-brand-logo>`
- `tenant-login.blade.php` — Tenant admin login, uses `<x-central-brand-logo>`

### Custom Blade Components (`resources/views/components/`)
- `central-brand-logo.blade.php` — Central platform logo (uploaded or SVG fallback)
- `language-switcher.blade.php` — Language toggle (ID/EN)

## Adding New Public Landing Pages (Central Domain)

When adding a new public page accessible on the central domain (e.g., `/new-section/page`), **two places must be updated**:

### 1. `routes/web.php`
Add the route to the `$landingPages` array:
```php
'new-section/page' => ['view' => 'landing.new-section.page', 'name' => 'landing.new-section.page'],
```

### 2. `app/Http/Middleware/RedirectCentralDomainToPanel.php`
Add the path prefix to the whitelist inside `handle()`. Without this, the middleware will redirect all unknown paths on the central domain back to `/` (homepage):
```php
str_starts_with($path, 'new-section') ||
```

**Current whitelisted prefixes**: `/`, `admin`, `register-tenant`, `login-tenant`, `pricing`, `feature`, `solution`, `contact`, `careers`, `about-us`, `blog`, `livewire`, `masuk`, `api/payment`, `impersonate`, `storage`, `build`, `vendor`, `css`, `js`, `fonts`, `icons`, `filament`, `_debugbar`, `up`.

> **Why this matters**: Forgetting step 2 causes the page to appear to load (URL is shown in browser status bar) but then silently redirect to homepage — a confusing bug that's hard to trace.

## Email System (Amazon SES v2)

### Mail Driver Configuration
Mail driver dikonfigurasi secara dinamis melalui **Central Admin → Email Settings** (`sa.{domain}/admin/email-settings`), disimpan di model `CentralSetting` group `mail`, dan diapply saat boot via `AppServiceProvider`.

**Hierarki prioritas (dari terendah ke tertinggi):**
1. `.env` / `config/mail.php` defaults
2. Central settings (`CentralSetting` group `mail`) — diatur via Central Admin UI
3. Tenant-specific sender identity (`Setting` model: `mail_from_address`, `mail_from_name`)

### Mengaktifkan Amazon SES v2
1. Masuk ke Central Admin → Email Settings
2. Pilih mailer **"Amazon SES v2"**
3. Isi AWS Access Key ID, Secret Access Key (dienkripsi di DB), dan Region
4. Isi From Address (harus sudah diverifikasi di AWS SES Console)
5. Klik "Kirim Email Test" untuk verifikasi

Kredensial AWS disimpan di `CentralSetting` (group `mail`) dan diapply ke `services.ses.*` saat boot. Tidak perlu env vars AWS jika sudah dikonfigurasi via UI.

### Mailer yang Tersedia
| Key | Keterangan |
|-----|-----------|
| `smtp` | SMTP biasa (Gmail, dll) |
| `sesv2` | Amazon SES v2 — **direkomendasikan** |
| `ses` | Amazon SES v1 (legacy) |
| `mailgun` | Mailgun |
| `postmark` | Postmark |
| `log` | Log file saja (untuk testing) |

### Email Notification Architecture
Sistem menggunakan **Laravel Notification** (bukan Mailable class terpisah). Semua notifikasi ada di `app/Notifications/` dan menggunakan `->markdown('emails.x.y', [...])` di `toMail()`.

**Central area** — dikirim dari platform ke tenant (`resources/views/emails/central/`):
- `tenant-ready` → `TenantReadyNotification` — welcome email saat toko selesai di-provision
- `saas-invoice-created` → `SaasInvoiceCreatedNotification` — tagihan langganan baru
- `payment-received` → `PaymentReceivedNotification` — konfirmasi pembayaran diterima
- `subscription-suspended` → `SubscriptionSuspendedNotification` — akun disuspend

**Tenant area** — dikirim dari toko ke customer/admin (`resources/views/emails/tenant/`):
- `new-booking` → `NewBookingNotification` — admin notif pemesanan baru
- `booking-confirmed` → `BookingConfirmedNotification` — customer konfirmasi pemesanan
- `delivery-out` → `DeliveryOutNotification` — surat jalan keluar
- `delivery-in` → `DeliveryInNotification` — surat jalan masuk/pengembalian
- `invoice-created` → `InvoiceCreatedNotification` — invoice baru
- `customer-reset-password` → `CustomerResetPassword` — reset password customer
- `pickup-reminder` → `PickupReminderNotification` — pengingat jadwal pengambilan
- `return-reminder` → `ReturnReminderNotification` — pengingat jadwal pengembalian
- `overdue-alert` → `OverdueAlertNotification` — peringatan keterlambatan

### Menambah Notifikasi Baru
1. Buat class di `app/Notifications/` yang extend `Notification`
2. `via()` menggunakan `Setting::get('notification_email_enabled')` dan `tenant()?->hasFeature(TenantFeature::EmailNotification)` untuk mail channel
3. `toMail()` return `(new MailMessage)->markdown('emails.[area].[name]', [...data...])`
4. Buat blade template di `resources/views/emails/[area]/[name].blade.php` menggunakan `<x-mail::message>`, `<x-mail::panel>`, `<x-mail::button>`

## SaaS Subscription Billing (Tenant Admin)

Halaman tenant **Admin → Subscription & Billing** (`/admin/subscription-billing`) menampilkan plan, usage, dan invoice history. Hidden dari sidebar — diakses via user menu "Subscription & Billing".

### Halaman & File
| File | Kegunaan |
|------|----------|
| `app/Filament/Pages/SubscriptionBilling.php` | List page (plan card, usage stats, invoice history) |
| `app/Filament/Pages/SubscriptionInvoiceDetail.php` | Detail invoice + Download PDF + Bayar (route `/admin/subscription-billing/invoices/{record}`) |
| `app/Filament/Concerns/InteractsWithSubscriptionPayment.php` | Trait shared payment-modal logic (pilih metode, polling status) — dipakai oleh kedua page di atas |
| `resources/views/filament/partials/subscription-payment-modal.blade.php` | Modal reusable (pilih metode + instruksi VA/QR/payment URL) |

### Authorization Detail Page
`SubscriptionInvoiceDetail::mount()` validasi `invoice->tenant_id === tenant()->id` — kalau tidak match → 404. Trait `InteractsWithSubscriptionPayment::initiatePayment()` ada defence-in-depth check yang sama.

### Flow Pembayaran
1. User klik **Bayar** di list atau di detail page → trait method `selectInvoice($id)` → buka modal
2. User pilih payment method (filter: `SubscriptionCheckoutService::getPaymentMethodsForTenant()` — region tenant + active gateway + active method)
3. `initiatePayment()` → `PaymentService::createPayment($invoice, $method)` → store `payment_data` di invoice → tampilkan instruksi
4. Modal `wire:poll.5s="checkPaymentStatus"` — auto-refresh, atau user klik tombol manual
5. Saat success: `handlePaymentSuccess()` aktifkan subscription + tenant status

### PDF Invoice
- **Template**: `resources/views/pdf/saas-invoice.blade.php` — standalone (tidak extend `pdf.layout` yang dipakai untuk dokumen tenant)
- **Logo**: `CentralBrandingService` → di-embed sebagai base64 data URI via `SubscriptionInvoiceDetail::resolveCentralLogoDataUri()` (dompdf tidak bisa fetch signed R2 URL secara reliable). Fallback teks "Zewalo App" kalau logo tidak ada.
- **Plan detail**: dibaca dari `$invoice->tenantSubscription->subscriptionPlan` — nama plan, deskripsi, fitur, billing cycle, periode start–end
- **Settings**: lihat seksi *Pengaturan Invoice PDF* di bawah

### Membuat SaaS Invoice Manual (Central Admin)
**URL**: `sa.{domain}/admin/saas-invoices/create`

Form **wajib** isi `subscription_plan_id` + `billing_cycle` saat create. Saat submit:
1. Kalau `tenant_subscription_id` kosong (default), `CreateSaasInvoice::mutateFormDataBeforeCreate()` auto-create `TenantSubscription` baru dengan plan + cycle yang dipilih, status `pending`, periode dari `issued_at` + 1 month/year sesuai cycle
2. SaasInvoice di-link ke subscription yang baru dibuat → `tenantSubscription.subscriptionPlan` ke-resolve di detail page tenant + di PDF

Field `subscription_plan_id` & `billing_cycle` adalah `dehydrated(false)` → tidak dikirim ke `SaasInvoice::create()`, hanya dipakai untuk auto-create subscription. Diambil dari raw form state via `$this->form->getRawState()`.

**Alasan field plan wajib**: tanpa subscription, PDF cuma tampil "Langganan {brandName} — Paket Standar" tanpa detail (deskripsi, fitur, limit). Sumber kebenaran nama plan ada di `TenantSubscription->subscriptionPlan`, BUKAN di kolom langsung `SaasInvoice`.

## Pengaturan Invoice PDF (Central Admin)

**Central Admin → System → Invoice PDF** (`InvoicePdfSettings.php`) — atur tampilan & konten PDF invoice SaaS, disimpan di `CentralSetting` group `invoice_pdf`.

### Settings Keys (group: `invoice_pdf`)
| Key | Tipe | Default | Keterangan |
|-----|------|---------|-----------|
| `invoice_pdf_primary_color` | hex | `#2563eb` | Warna utama (border, header table, accent line) |
| `invoice_pdf_text_color` | hex | `#111827` | Warna teks utama |
| `invoice_pdf_muted_color` | hex | `#6b7280` | Warna teks sekunder/muted |
| `invoice_pdf_accent_bg` | hex | `#f9fafb` | Background meta box & footer |
| `invoice_pdf_font_size` | int (9–16) | `12` | Font size body (px) |
| `invoice_pdf_paper_size` | enum | `a4` | `a4` atau `letter` |
| `invoice_pdf_show_logo` | bool | `true` | Tampilkan logo central di header (fallback teks) |
| `invoice_pdf_show_plan_description` | bool | `true` | Tampilkan deskripsi plan di item description |
| `invoice_pdf_show_plan_features` | bool | `true` | Tampilkan daftar fitur + limit (users, storage, dll) |
| `invoice_pdf_header_note` | text | `''` | Catatan tambahan di bawah logo (misal alamat, NPWP) |
| `invoice_pdf_footer_text` | text | `'Terima kasih...'` | Teks footer |
| `invoice_pdf_terms_text` | text | `''` | Syarat & ketentuan di bawah invoice (kosong = hidden) |

### Akses dari Code
```php
$pdfSettings = \App\Filament\Central\Pages\InvoicePdfSettings::loadSettings();
// Returns array merged dengan defaults — aman dipakai langsung di blade
```

`loadSettings()` selalu merge dengan `defaults()` jadi template tidak pernah pecah karena key kosong. `paper_size` dipakai oleh `SubscriptionInvoiceDetail::downloadPdf()` saat `Pdf::setPaper()`.

## Production Environment Variables (Critical)

Beberapa env var yang **wajib** di-set di production (Dokploy) agar fitur berjalan:

| Key | Value | Keterangan |
|-----|-------|-----------|
| `QUEUE_CONNECTION` | `redis` | **Wajib.** Default-nya `database`, tapi queue worker berjalan di Redis. Tanpa ini, job seperti `CreateTenantJob` tidak pernah diproses dan tenant registration stuck di "Menunggu antrian.. 5%". |
| `REDIS_HOST` | nama service Redis | Nama container Redis di Dokploy, contoh: `zewalo-redis-gnumoa`. Otomatis di-set oleh Dokploy. |
| `LIVEWIRE_TMP_DISK` | `local` | **Wajib** untuk upload file. Livewire menaruh file sementara di disk ini sebelum dipindah ke R2. Harus disk lokal yang selalu writable — **jangan set ke `r2` atau `public`**, karena menyebabkan upload stuck di "uploading" (file upload foto logo, produk, dll tidak pernah selesai). Default di `config/livewire.php` sekarang adalah `local`. |
| `FILESYSTEM_DISK` | `local` | Default filesystem disk. Di production gunakan `local` (bukan `public`) — file user-upload tetap ke R2 via `TenantFileUpload` / `disk('r2')` eksplisit. |

### Tenant Registration Queue

Proses pembuatan tenant (`/register-tenant` step 3) menggunakan `CreateTenantJob` yang di-dispatch ke queue **`tenant-creation`** via Redis. Queue worker di supervisord (`docker/supervisord.conf`) harus:
1. Menggunakan connection `redis` (bukan `database`)
2. Listen ke queue `tenant-creation,default`

Jika stuck di progress 5% ("Menunggu antrian..."), cek:
```bash
# Verifikasi QUEUE_CONNECTION
docker exec -it <container> php artisan config:show queue | grep "^  default"
# Harus: default = redis (bukan database)

# Monitor queue
docker exec -it <container> php artisan queue:monitor redis:tenant-creation,redis:default
```

## File Upload & R2 Storage (Critical)

Sistem upload file di Zewalo punya 2 tahap: **Livewire temp** (lokal) → **R2 permanen**. Memahami ini penting karena bug "uploading stuck" di production biasanya di step pertama, bukan R2.

### Upload Flow
```
Filament FileUpload
  ↓ [step 1] Livewire POST /livewire/upload-file
  ↓           → simpan ke LIVEWIRE_TMP_DISK (local)
  ↓           → return TemporaryUploadedFile
  ↓ [step 2] Form save() → Filament storeFiles()
  ↓           → move dari temp disk ke target disk (r2)
  ↓           → return final path (disimpan di DB)
```

### Komponen File Upload
| Komponen | File | Kegunaan |
|---------|------|----------|
| `TenantFileUpload` | `app/Filament/Components/TenantFileUpload.php` | Auto-prefix tenant: `tenant_<id>/<dir>/file.ext`. Default disk=`r2`, visibility=`private`. |
| `FileUpload->tenantDirectory()` | macro di `StorageServiceProvider` | Sama dengan di atas, versi fluent API. |
| `FileUpload->r2Directory()` | macro di `StorageServiceProvider` | R2 tanpa prefix tenant — untuk central admin (branding, central docs). |
| `FileUpload->r2Tenant()` | macro di `StorageServiceProvider` | Auto-detect: prefix `tenant_<id>/` jika dalam tenant context, `central/` jika tidak. |
| `HasTenantStorage` trait | `app/Filament/Concerns/HasTenantStorage.php` | Static helpers: `tenantFileUpload()`, `tenantMultipleFileUpload()`, `tenantDocumentUpload()`. |
| Central branding: raw `FileUpload->disk('r2')->directory('central/branding')` | `BrandingSettings.php` | Logo/favicon/og image — tanpa macro. |

### R2 Disk Behavior
- **Default visibility**: `'private'` (di `config/filesystems.php`). R2 tidak support ACL per-object — **jangan set `->visibility('public')`** karena R2 akan reject header `x-amz-acl: public-read` dan upload gagal.
- **Display URL**: **selalu pakai signed URL** via `\App\Services\Storage\R2Url::signed($path)` — `Storage::disk('r2')->url()` mengembalikan direct public URL yang 404 di bucket private. Lihat subseksi *Menampilkan File R2* di bawah.
- **`r2` disk BUKAN bagian dari `tenancy.filesystem.disks`** — tidak di-suffix per tenant oleh `FilesystemTenancyBootstrapper`. Prefix tenant ditangani manual oleh `TenantStorageService::getPath()`.

### R2 Config Loading
`CentralSettingsServiceProvider` memuat R2 creds dari `CentralSetting` group `r2` ke `config('filesystems.disks.r2.*')` saat boot. Kredensial disimpan di DB (bukan `.env`) karena bisa diubah via Central Admin UI tanpa redeploy.

- **Per-request cache**: `static $r2Loaded = true` setelah load pertama di request → panggilan berikutnya no-op.
- **Force reload**: `CentralSettingsServiceProvider::ensureR2Config(force: true)` — dipakai setelah ganti kredensial di runtime, atau di CLI command.
- **Reset flag**: `CentralSettingsServiceProvider::resetR2Cache()` — reset `$r2Loaded = false` tanpa langsung reload.
- **Storage::purge('r2')** dipanggil di `loadR2Settings()` supaya disk instance di-recreate dengan config baru.
- **Di queue worker**: R2 config ter-load sekali saat worker boot. Kalau kredensial diubah saat worker jalan, restart worker (`supervisorctl restart queue-worker`) atau panggil `ensureR2Config(force: true)` di awal job.

### Menampilkan File R2 (Signed URLs — Penyebab Umum "Foto 404")
R2 bucket kita **private** (`visibility => 'private'` di `config/filesystems.php`) — **jangan pernah pakai `Storage::disk('r2')->url($path)` langsung untuk display** karena hasilnya direct public URL yang selalu 404 di bucket private.

**Gunakan helper signed URL**:
```php
// Blade / controller — berlaku untuk semua file R2 (logo, produk, dokumen)
{{ \App\Services\Storage\R2Url::signed($path) }}

// Central branding (logo/favicon/og image) sudah pakai signed di dalam service
\App\Services\CentralBrandingService::logoUrl()
\App\Services\CentralBrandingService::faviconUrl()
\App\Services\CentralBrandingService::ogImageUrl()

// Tenant-prefixed (auto prefix `tenant_{id}/`)
app(\App\Services\Storage\TenantStorageService::class)->temporaryUrl($path, now()->addHour())
```

**Filament image components** (`ImageColumn` / `ImageEntry`): `StorageServiceProvider::forceSignedUrlsOnR2Images()` memanggil `configureUsing()` yang memaksa `->visibility('private')` di semua instance — Filament lalu otomatis pakai `temporaryUrl()` saat render. Tidak perlu set manual di setiap resource.

**Filament `FileUpload`**: upload tetap pakai disk `r2` dengan visibility `private` (jangan set `public` — R2 reject ACL). Preview thumbnail di form edit sudah otomatis pakai signed URL karena visibility `private`.

**Kalau suatu saat bucket diubah jadi public** (misal mau pakai CDN): tinggal ganti implementation `R2Url::signed()` jadi `Storage::disk('r2')->url($path)` — semua callsite tidak perlu berubah.

**Kenapa signed, bukan public bucket?** Private + signed URL default: (1) lebih aman, file rental/invoice/KTP customer tidak bisa di-crawl, (2) tidak tergantung config `r2_url` yang sering salah (trailing slash, bucket name duplicate), (3) tiap tenant ter-isolate di level object access — kalaupun URL bocor, expired dalam 60 menit.

### R2 CORS Policy (WAJIB — Penyebab "Upload Stuck Loading di Filament")

**Gejala**: User upload foto di Filament FileUpload → spinner "ketuk untuk membatalkan, ukuran berkas" muncul terus tidak pernah selesai, walaupun file sebenarnya sudah ter-upload ke R2. Di DevTools Network tab tidak ada request ke `/livewire/upload-file` yang stuck — yang ada adalah request ke `*.r2.cloudflarestorage.com/<file-id>?X-Amz-...` dengan status **CORS error**.

**Root cause**: Filament 4 / FilePond JS (`vendor/filament/forms/resources/js/components/file-upload.js:187`) memanggil `fetch(signedR2Url, {cache: 'no-store'})` untuk render preview file di FileUpload component. R2 (default) tidak return CORS headers, browser block fetch, FilePond stuck di state loading.

**Fix**: Set CORS policy di **Cloudflare Dashboard → R2 → bucket `zewalowebapp` → Settings → CORS Policy**:

```json
[
  {
    "AllowedOrigins": [
      "https://zewalo.com",
      "https://*.zewalo.com",
      "http://localhost",
      "http://localhost:8000"
    ],
    "AllowedMethods": ["GET", "HEAD", "PUT", "POST", "DELETE"],
    "AllowedHeaders": ["*"],
    "ExposeHeaders": ["ETag"],
    "MaxAgeSeconds": 3600
  }
]
```

**Origins yang harus include**:
- `https://<central-domain>` — landing page (e.g. `https://zewalo.com`)
- `https://*.<central-domain>` — wildcard cover central admin (`sa.zewalo.com`) DAN semua tenant subdomain (`ajra.zewalo.com`, `toko-a.zewalo.com`, dll)
- `http://localhost` + `http://localhost:8000` — dev environment

**Kalau ada tenant pakai custom domain** (di luar pattern `*.zewalo.com`): tambah origin-nya manual ke array `AllowedOrigins` setiap kali tenant baru pakai custom domain. Pertimbangkan otomasi via API kalau ini sering terjadi.

**Setelah update CORS**: hard reload browser (Ctrl+Shift+R) supaya browser invalidate CORS preflight cache lama. Propagasi Cloudflare ~1 menit.

**Kenapa Livewire upload tidak kena CORS?** Livewire pakai server-proxied upload (temp disk = `local`, lihat seksi *Livewire Temp Upload* di bawah). Browser POST ke `/livewire/upload-file` (same-origin) → server PHP yang upload ke R2 (server-to-server, no CORS). Yang kena CORS hanyalah **preview/display** file existing di FileUpload component yang fetch via signed URL dari browser.

### Livewire Temp Upload (Penyebab Umum "Uploading Stuck")
`config/livewire.php` → `temporary_file_upload.disk` = `LIVEWIRE_TMP_DISK` env, **harus `local`** (bukan `r2` / `public` / `s3`). Alasan:
- Livewire temp disk harus writable dalam single-container (Docker Alpine).
- Kalau set ke `r2`: tiap chunk upload jadi round-trip ke R2 via signed URL → lambat, tidak reliable, sering timeout.
- Kalau set ke `public`: `storage/app/public/livewire-tmp` tidak auto-created di Dockerfile + permissions rawan bentrok antara root (entrypoint) dan www-data (php-fpm).

**Direktori wajib ada** di container (pre-created di Dockerfile + re-ensured di entrypoint):
- `storage/app/private/livewire-tmp/` — untuk `local` disk (Laravel 11+ private root)
- `storage/app/livewire-tmp/` — backup location
- `storage/app/public/livewire-tmp/` — jika fallback ke `public`

Semua dengan `chown www:www-data` + `chmod 775`.

### Verifikasi R2 Writable
**Problem umum**: R2 credentials valid saat `testConnection()` (yang hanya list directories) tapi gagal saat PUT karena permission scope token R2 hanya "Read". Probe menulis file kecil untuk catch ini.

```bash
# CLI — paling reliable, runs dengan env production
docker exec -it <container> php artisan r2:probe                 # central + semua tenant
docker exec -it <container> php artisan r2:probe --central       # central only
docker exec -it <container> php artisan r2:probe --tenant=<id>   # satu tenant
```

Via UI: **Central Admin → System → R2 Storage Settings** → tombol **"Test Tulis (Central)"** atau **"Test Tulis Semua Tenant"**. Hasil tampil di section dengan latency + error message per scope.

Probe otomatis juga jalan di `CreateTenantStorageFolder` job setelah tenant baru dibuat — kalau gagal, log ke level `error` (visible di Log Viewer).

### Debugging "Uploading Stuck"
Urutan diagnosis:
1. Buka DevTools → Network → lihat request `POST /livewire/upload-file`. Kalau tidak ada response / timeout → step 1 (Livewire temp) bermasalah.
2. Cek `LIVEWIRE_TMP_DISK` env: `docker exec ... php artisan config:show livewire.temporary_file_upload.disk` — harus `local`.
3. Cek writable: `docker exec ... ls -la storage/app/private/livewire-tmp/` — harus ada, owner `www:www-data`.
4. Kalau step 1 OK tapi tetap stuck di progress 95%+: step 2 (move ke R2) bermasalah. Jalankan `php artisan r2:probe` — biasanya ketemu error kredensial/permission di sini.
5. Lihat **Central Admin → System → Log Viewer** untuk error detail dari `laravel.log` / `php-errors.log`.

## Log Viewer (Central Admin)

**Central Admin → System → Log Viewer** — aggregated viewer untuk semua file `*.log` di `storage/logs/`.

### Arsitektur
- `app/Services/LogViewerService.php` — parse `*.log` files, support format Laravel standar (`[date] env.LEVEL: message\ncontext`) + fallback raw-line untuk log non-Laravel.
- `app/Filament/Central/Pages/LogViewer.php` — Filament page dengan filter (file / level / keyword), URL-persistent via `#[Url]` Livewire attributes.
- `resources/views/filament/central/pages/log-viewer.blade.php` — UI dengan summary cards (error/warning/critical/info count), file sidebar, expandable entries dengan stack trace, copy button, download raw file, truncate file.

### File Log yang Tampil
Semua `*.log` dan `*.txt` di `storage/logs/`:
- `laravel.log` — aplikasi Laravel (central + semua tenant; shared container storage)
- `queue-worker.log` / `queue-worker-error.log` — queue worker (supervisord)
- `scheduler.log` / `scheduler-error.log` — scheduler loop (supervisord)
- `php-errors.log` — error dari `error_log` di `php.ini`
- `backup-restore.log` — backup-restore operations (tenant channel)

### Cara Pakai untuk Support
1. User report error → bukak Log Viewer → filter `level:error` + search keyword.
2. Klik entry untuk expand → lihat full stack trace → click **Copy** button untuk share ke support.
3. Atau download raw file via tombol **Download** untuk attach ke tiket.
4. **Clear** button untuk truncate file (misal setelah issue resolved, agar log tidak bloat).

### Menambah Log Channel Baru
Kalau tambah channel di `config/logging.php` dengan `'path' => storage_path('logs/xxx.log')`, otomatis tampil di Log Viewer tanpa perubahan kode — `LogViewerService::listFiles()` scan direktori `storage/logs/`.

## Tenant Issues (Central Admin Error Reporting)

**Central Admin → System → Tenant Issues** — persistent error tracker untuk error yang terjadi di tenant context. Lebih struktur daripada Log Viewer: setiap error dapat reference code (`ZWL-ERR-000123`), bisa di-resolve/reopen, punya status tracking, dan terfilter per tenant.

### Kapan Pakai Tenant Issues vs Log Viewer
- **Tenant Issues**: error yang perlu action (bug, config salah, seed gagal). User bisa kasih kode `ZWL-ERR-XXXXXX` ke support untuk lookup langsung.
- **Log Viewer**: raw log files untuk debugging mendalam (stack trace, SQL, cache warnings). Tidak punya state resolved/unresolved.

Keduanya komplementer — unhandled tenant exception masuk ke **keduanya** (Log Viewer via Laravel logger, Tenant Issues via reportable hook).

### Arsitektur
- **Table** `tenant_issues` (central DB) — `tenant_id`, `tenant_name`, `code`, `area`, `severity`, `title`, `message`, `exception_class`, `file`, `line`, `stack_trace`, `context` (JSON), `url`, `user_email`, `resolved_at`, `resolved_by`, `resolution_note`.
- **Model**: `App\Models\TenantIssue` — connection `central`, scope `unresolved()`, `markResolved($by, $note)`.
- **Service**: `App\Services\TenantIssueReporter` — dua method utama:
  - `reportException(Throwable $e, string $code, string $title, string $area = 'tenant', string $severity = 'error', array $context = []): string` — catat exception (auto capture file/line/trace). Return reference `ZWL-ERR-000123`.
  - `report(string $code, string $title, string $message, string $area, string $severity, array $context): string` — catat issue non-exception (config salah, validasi bisnis gagal).
- **Filament Resource**: `App\Filament\Central\Resources\TenantIssueResource` — list/view/edit + aksi **Resolve** / **Reopen**, sidebar badge shows unresolved count.

### Global Reporter (Auto-Capture)
`bootstrap/app.php` `withExceptions()` reportable hook otomatis catat setiap exception dalam tenant context dengan code `UNCAUGHT_TENANT_EXCEPTION`. Skipped: `NotFoundHttpException`, `MethodNotAllowedHttpException`, `ValidationException`, `AuthenticationException`, `AuthorizationException`, `TokenMismatchException` (non-critical / user error).

### Convention untuk Error Code
Format: `SCREAMING_SNAKE_CASE`, 64 char max. Pola rekomendasi: `{AREA}_{WHAT_FAILED}`, misal:
- `SETUP_WIZARD_SEED_FAILED` — seeder error di wizard
- `R2_UPLOAD_FAILED` — file upload gagal ke R2
- `TENANT_MIGRATION_FAILED` — tenant migration error
- `UNCAUGHT_TENANT_EXCEPTION` — fallback untuk unhandled exception

### Pola Pakai di Code
```php
use App\Services\TenantIssueReporter;

try {
    // ... code yang bisa gagal
} catch (\Throwable $e) {
    $ref = TenantIssueReporter::reportException(
        e: $e,
        code: 'MY_FEATURE_FAILED',
        title: 'Human-readable description',
        area: 'my_feature',
        severity: 'error', // 'critical' | 'error' | 'warning'
        context: ['extra' => 'data'],
    );

    // Tampilkan reference ke user supaya bisa lapor ke support
    Notification::make()
        ->title('Terjadi error')
        ->body("Kode error: {$ref}")
        ->warning()
        ->send();
}
```

### Navigation Badge
Sidebar navigation menampilkan badge jumlah issue yang unresolved. Warna **danger** kalau ada severity `critical`, **warning** untuk lainnya. Helps superadmin spot issue baru tanpa polling halaman.

## Tenant Admin Dashboard (Home)

Halaman home di tenant admin panel (`/admin`) **bukan** lagi default Filament Dashboard dengan stack widget terpisah. Diganti satu widget custom yang merangkum greeting + stats + menu + jadwal hari ini + booking terbaru, dengan layout yang berbeda per breakpoint.

### Arsitektur
- **Widget**: `App\Filament\Widgets\DashboardHomeWidget` — single full-width widget (`columnSpan = 'full'`, `sort = -10` supaya selalu di paling atas).
- **View**: `resources/views/filament/widgets/dashboard-home.blade.php` — semua CSS inline di `@push('styles')` dengan namespace `.zw-` supaya tidak bentrok dengan Filament.
- **Registrasi**: di `AdminPanelProvider::widgets([...])` — hanya `DashboardHomeWidget::class`. Widget lama (`StatsOverview`, `LatestRentals`, `RentalChart`) tetap ada di codebase tapi `protected static bool $isDiscovered = false` supaya tidak auto-register lewat `discoverWidgets()`.

### Konten per Breakpoint
| Section | Mobile (<768) | Tablet (768–1023) | Desktop (≥1024) |
|---------|--------------|--------------------|-----------------|
| Greeting + tanggal | ✓ | ✓ | ✓ |
| Subtitle "X pickup · Y return hari ini" | ✗ | ✓ | ✓ |
| Overview stats (6 kartu) | ✗ | ✓ (3 kolom) | ✓ (6 kolom) |
| Menu grid (8 menu) | ✓ (2 kolom, row layout dengan icon kiri + label & desc) | ✓ (4 kolom column) | ✓ (4 kolom column) |
| Today's Schedule list | ✗ | ✓ | ✓ |
| Recent Bookings table | ✗ | ✗ | ✓ |

Mobile sengaja minimal — hanya greeting + menu — sesuai keputusan UX (overview & today schedule terlalu padat di layar kecil).

### Data Sources (cached 120s)
- `getStats()` → `Cache::remember('zw_dashboard_home_stats', 120, ...)` — Active rentals, Quotations, Pickups today (`start_date = today` + status `confirmed`/`late_pickup`), Returns today (`end_date = today` + status `active`/`late_return`/`partial_return`), Overdue (`late_*`), Revenue (sum `total` bulan ini)
- `getTodaySchedule()` → rental yang `start_date` atau `end_date` jatuh hari ini, sort by `start_date`, limit 8
- `getRecentBookings()` → 6 rental terbaru by `created_at`
- Cache key tidak per-tenant scoped karena `Setting::set()` / Filament cache flush sudah handle invalidation di tenant boundary; kalau perlu lebih aman, bungkus key dengan `tenant()?->id`

### Status Color Map
Konsisten antara Dashboard & Schedule (lihat seksi Schedule di bawah). 7 status (`quotation`/`confirmed`/`active`/`completed`/`cancelled`/`late_pickup`/`late_return`/`partial_return`) → warna solid + bg + fg + label.

### Menambah Menu Baru
Edit array `getMenu()` di `DashboardHomeWidget.php` — tiap item: `id`, `label`, `icon` (heroicon class), `url`, `desc` (tampil hanya di mobile).

## Tenant Admin Schedule (Native Calendar)

Halaman Schedule (`/admin/schedule`) **tidak lagi pakai** Filament FullCalendar plugin (`saade/filament-fullcalendar`) — di-rewrite jadi native Livewire + Alpine.js untuk fidelity UI yang lebih tinggi dan bundle yang lebih ringan. Widget lama `RentalCalendarWidget` masih ada (untuk page `RentalCalendar` yang `shouldRegisterNavigation = false`) tapi tidak dipakai di Schedule baru.

### Arsitektur
- **Page**: `App\Filament\Pages\Schedule` — Livewire page dengan state URL-persistent via `#[Url]` attributes (`tab`, `view`, `d` untuk cursor date, `search`).
- **Main view**: `resources/views/filament/pages/schedule.blade.php` — toolbar + legend + Google Calendar–style nav + container untuk partial sesuai `calendarView`.
- **Partials** di `resources/views/filament/pages/partials/`:
  - `schedule-month.blade.php` — 7×6 grid bulan, "+N more" → Alpine popup
  - `schedule-week.blade.php` — Gantt bars 7 kolom dengan `grid-column: span N` per rental
  - `schedule-day.blade.php` — timeline 24-jam dengan lane assignment otomatis untuk overlap
  - `schedule-by-product.blade.php` — sticky product+SKU+month header, **hour-aware mini-bars** (lihat di bawah)

### Filter & View Toggle
- **Tab "By Order" / "By Product"** — pill toggle di toolbar atas (`setTab($tab)`)
- **View dropdown** "Month / Week / Day" — hanya muncul saat tab `order` (`setView($view)`)
- **Tombol "New Booking"** — link ke `/admin/rentals/create`, hidden di mobile via `.zw-hide-mobile` CSS

### Google Calendar–style Toolbar
- Tombol **Today** (pill rounded) → `gotoToday()` reset cursor ke hari ini
- Tombol chevron **prev/next** → `navigatePrev()` / `navigateNext()`, step unit otomatis sesuai view (`month`/`week`/`day`)
- Title format: bulan untuk Month, range tanggal untuk Week, full date untuk Day

### Data Loading
- `getRentals()` — query `Rental::where('start_date', '<', $end)->where('end_date', '>', $start)` (overlap query), eager-load `customer:id,name`, limit 500. Dipanggil oleh semua view (Month/Week/Day) berdasarkan `getRangeStart()` & `getRangeEnd()`.
- **Tidak di-cache** — page Livewire akan re-run pada tiap navigasi; cache layer akan salah saat tenant berbeda. Kalau perlu, tambah Redis cache dengan key per tenant + cursor + view.

### Month View — "+N more" Popup
- `getMonthGrid($maxStack = 3)` — return array `[week_index][cell_index]` dengan `visible` (3 pertama) & `overflow` count + `all` (semua items)
- Saat `overflow > 0`: tombol `+N more` → trigger Alpine `openOverflow(title, items)` → modal bottom-sheet dengan **list rental hari itu lengkap dengan status pill** (warna sesuai status di sistem)
- Click rental di popup → `openRental(id)` → mount Filament action `viewRentalDetails`

### Day View — Lane Assignment
- `getDayLayout()` — sort events by `startMin`, assign lane greedy (cari lane yang sudah `free` di waktu itu, kalau tidak ada → lane baru)
- Render: `position: absolute` dengan `top = startMin/60 * 48px`, `height = duration/60 * 48px`, `left = lane * (100/totalLanes)%`, `width = (100/totalLanes)%`

### By Product — Hour-Aware Mini-Bars (Penting)
**Problem yang di-fix**: Versi lama, kalau di tanggal yang sama ada 2 rental berbeda untuk unit yang sama (jam pagi & jam sore), bar yang kedua menimpa yang pertama karena tiap cell hanya bisa render 1 background.

**Solusi (option b — proportional positioning)**:
- Setiap day-cell jadi container `position: relative; height: 36px`
- Tiap rental yang overlap dengan tanggal itu di-clip jadi segment `[segStart, segEnd]` dalam jendela `[dayStart, dayEnd]`
- Konversi ke persen: `left = (segStart.hour*60 + segStart.minute) / (24*60) * 100%`, `width = ((endMin - startMin) / (24*60)) * 100%`
- Tiap segment jadi `<button position: absolute; left: X%; width: Y%; background: status_color>` — **bisa berdampingan tanpa menimpa**
- `continuesLeft` / `continuesRight` flag → border-radius dihilangkan di sisi yang continue ke hari sebelum/sesudah, jadi visually nyambung
- Min visible width 8% supaya rental durasi <2 jam tetap kelihatan

**Catatan**: hover/click tetap akurat ke segment yang diklik (bukan bar terakhir saja).

### Sticky Header By Product
- Kolom **Product / Unit** sticky `left: 0`
- **Month label row** (e.g. "April 2026") sticky `top: 0` dengan `colspan` jumlah hari di bulan itu
- **Day header row** (Sen 13, Sel 14, ...) sticky `top: 36px`
- Tiap product baris pertama: header row dengan nama product (`zw-prod__product`), baris berikutnya untuk tiap unit dengan SKU tag

### Status Color Map (Single Source di Blade)
Didefinisikan di awal `schedule.blade.php` sebagai `$statusColors = [...]` PHP array, lalu di-pass ke partial via `'sc' => $sc`. Sama persis dengan yang dipakai di Dashboard widget.

| Status DB | Solid | BG | FG | Label |
|-----------|-------|----|----|-------|
| `quotation` | #f97316 | #fff7ed | #c2410c | Quotation |
| `confirmed` | #3b82f6 | #eff6ff | #1d4ed8 | Confirmed |
| `active` | #22c55e | #f0fdf4 | #15803d | Active |
| `completed` | #a855f7 | #faf5ff | #7e22ce | Done |
| `cancelled` | #6b7280 | #f9fafb | #374151 | Cancel |
| `late_pickup` / `late_return` | #ef4444 | #fef2f2 | #b91c1c | Late |
| `partial_return` | #eab308 | #fefce8 | #854d0e | Partial |

### Click Rental → Detail Modal
Semua view trigger `wire:click="mountAction('viewRentalDetails', { rentalId: X })"`. Action didefinisikan di `Schedule::viewRentalDetailsAction()` — Filament action modal dengan field disabled (rental_code, status, customer, total, periode, items, notes) + footer button "Buka Rental" → `/admin/rentals/{id}/view`.

### Responsive Breakpoints
Pure CSS media queries (no JS detection), namespace `.zw-`:
- **Mobile** `<768px` — tombol "New Booking" hidden, calendar cells lebih ringkas (`min-height: 64px` vs 88), font lebih kecil, `gc-title: 14px`
- **Tablet** `768–1023px` — stats grid 3 kolom, menu grid 4 kolom column layout
- **Desktop** `≥1024px` — stats grid 6 kolom, sidebar full

### Menambah View Baru (misal Year)
1. Tambah method `getYearGrid()` di `Schedule.php` yang return data terstruktur
2. Buat partial `partials/schedule-year.blade.php`
3. Tambah ke array dropdown di `schedule.blade.php`: `['month'=>'Month', 'week'=>'Week', 'day'=>'Day', 'year'=>'Year']`
4. Tambah `@elseif ($calendarView === 'year') @include('filament.pages.partials.schedule-year', [...])` di main view
5. Update `stepUnit()` & `getRangeStart()`/`getRangeEnd()` untuk handle `'year'`

### Cache & Performance
- View partial blade di-compile sekali oleh Laravel; refresh saat `php artisan view:clear`
- Query rental dibatasi 500 rows per request — cukup untuk view Month (overlap window max ~5 minggu); kalau lebih besar, paginate atau tambah indeks pada `(start_date, end_date)`
- By Product pakai paginate eksisting (`perPage` setting di UI) — lebih aman untuk tenant dengan ratusan unit

## Key Conventions

- The app uses Indonesian language for some user-facing routes and labels (e.g., `/masuk` for login)
- Settings model stores tenant-level configuration as key-value pairs
- Products have Units (individual trackable items) and optional Variations and Components
- Rental flow: Quotation → Confirmed → Active → Returned (with partial return support)
- Custom customer auth system with middleware `customer.auth` and `customer.guest` (separate from admin `auth`)
- File uploads: lihat bagian **File Upload & R2 Storage** di atas untuk detail lengkap (komponen, macro, visibility, probe, debugging).
- Central vs tenant settings: use `CentralSetting` for platform-wide, `Setting` for per-tenant. Both have 1-hour cache with auto-invalidation
- View composers in `AppServiceProvider::boot()` inject data into specific blade views (PDF settings, theme CSS vars, central branding). Wrap in try/catch for migration safety
- `config('app.name')` is overridden at boot: first by `CentralSetting::get('branding_site_name')`, then by tenant `Setting::get('site_name')` — this cascade ensures emails and UI always show the correct name

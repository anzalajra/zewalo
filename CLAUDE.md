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

**Template Seeders:** `database/seeders/tenant/TenantTemplateSeeder.php` dispatches to category-specific seeders in `database/seeders/tenant/templates/` (Photography, Automotive, Camping, Electronics, Wedding, Sports, Music, Default). Each creates 1-2 categories + 3 products with 1 unit each.

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

### Filament Central Admin Resources
Located in `app/Filament/Central/Resources/`:
- `TenantResource`, `TenantCategoryResource`, `UserResource`
- `SubscriptionPlanResource`, `SaasInvoiceResource`
- `PaymentGatewayResource`, `PaymentMethodResource`
- `TranslationResource`

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

## Production Environment Variables (Critical)

Beberapa env var yang **wajib** di-set di production (Dokploy) agar fitur berjalan:

| Key | Value | Keterangan |
|-----|-------|-----------|
| `QUEUE_CONNECTION` | `redis` | **Wajib.** Default-nya `database`, tapi queue worker berjalan di Redis. Tanpa ini, job seperti `CreateTenantJob` tidak pernah diproses dan tenant registration stuck di "Menunggu antrian.. 5%". |
| `REDIS_HOST` | nama service Redis | Nama container Redis di Dokploy, contoh: `zewalo-redis-gnumoa`. Otomatis di-set oleh Dokploy. |

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

## Key Conventions

- The app uses Indonesian language for some user-facing routes and labels (e.g., `/masuk` for login)
- Settings model stores tenant-level configuration as key-value pairs
- Products have Units (individual trackable items) and optional Variations and Components
- Rental flow: Quotation → Confirmed → Active → Returned (with partial return support)
- Custom customer auth system with middleware `customer.auth` and `customer.guest` (separate from admin `auth`)
- File uploads: tenant files use `TenantFileUpload` component (`app/Filament/Components/TenantFileUpload.php`) which auto-prefixes tenant directory on R2. Central file uploads (branding) use standard `FileUpload` with `disk('r2')` and `directory('central/branding')`. **Jangan pakai `disk('public')` di production** — local storage tidak persisten di Docker, gunakan R2
- Central vs tenant settings: use `CentralSetting` for platform-wide, `Setting` for per-tenant. Both have 1-hour cache with auto-invalidation
- View composers in `AppServiceProvider::boot()` inject data into specific blade views (PDF settings, theme CSS vars, central branding). Wrap in try/catch for migration safety
- `config('app.name')` is overridden at boot: first by `CentralSetting::get('branding_site_name')`, then by tenant `Setting::get('site_name')` — this cascade ensures emails and UI always show the correct name

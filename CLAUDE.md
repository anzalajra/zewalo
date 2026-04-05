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

### Central Domains
Defined in `config/tenancy.php`: `127.0.0.1`, `localhost`, `zewalo.test`, and `APP_DOMAIN` env var. Any other domain triggers tenant resolution.

### Custom PostgreSQL Database Manager
`App\Services\Tenancy\PostgreSQL16DatabaseManager` — custom DB manager for Docker Alpine PostgreSQL 16 compatibility (handles template0, collation).

## Application Structure

### Filament Resources (Tenant Admin Panel)
Located in `app/Filament/Resources/` — Rentals, Products, ProductUnits, Customers, Invoices, Quotations, Deliveries, Discounts, Warehouses, etc.

### Filament Clusters
- `app/Filament/Clusters/Finance/` — Double-entry accounting: journal entries, chart of accounts, finance transactions, tax reports, depreciation
- `app/Filament/Clusters/Settings/` — Tenant-level settings

### Key Services
- `App\Services\JournalService` — Double-entry journal posting
- `App\Services\TaxService` / `TaxReportService` — Tax calculations and reporting
- `App\Services\PromotionService` — Discount/promotion logic
- `App\Services\ThemeService` — Tenant theme customization
- `App\Services\Storage\` — R2/S3 storage integration

### Customer-Facing Routes (web.php)
The app has a setup wizard flow (checks `storage/installed` file). Once installed:
- Public: catalog, blog (Zeus Sky), landing pages
- Customer auth: login/register/forgot-password (custom, not Breeze — Breeze is dev-only)
- Customer portal: dashboard, rentals, documents, cart, checkout
- Tenant registration: `/register-tenant` (Livewire)
- Central login portal: `/masuk` (Livewire)

### Models
Central models (`central` connection): `Tenant`, `Domain`, `SubscriptionPlan`
Tenant models (default connection): `Product`, `ProductUnit`, `Rental`, `RentalItem`, `Customer`, `Invoice`, `Quotation`, `Delivery`, `Cart`, `Warehouse`, `Setting`, and finance models under `App\Models\Finance\`

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

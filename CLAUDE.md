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

## Key Conventions

- The app uses Indonesian language for some user-facing routes and labels (e.g., `/masuk` for login)
- Settings model stores tenant-level configuration as key-value pairs
- Products have Units (individual trackable items) and optional Variations and Components
- Rental flow: Quotation → Confirmed → Active → Returned (with partial return support)
- Custom customer auth system with middleware `customer.auth` and `customer.guest` (separate from admin `auth`)

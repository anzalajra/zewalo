<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Setting;
use App\Services\ThemeService;
use Closure;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Applies the current tenant's appearance settings (theme color, locale) to
 * the panel at request time, after tenant context has been initialized.
 *
 * Why a middleware: Filament's `panel()` method runs once during the Laravel
 * boot phase — before any tenant context exists — so settings read inside it
 * always come from the central database. Even Filament's `bootUsing()` callback
 * fires too early (it runs inside the `panel:` route alias, which is the FIRST
 * middleware in the panel route group, before `InitializeTenancyByDomain`).
 * This middleware is appended to the panel's auth middleware list AFTER the
 * `PaletteSwitcherPlugin`'s `ApplyPalette` middleware so:
 *   1. Tenant context is initialized (panel middleware ran first).
 *   2. Our color registration is the LAST one, beating ApplyPalette's stale
 *      config-derived registration.
 */
class ApplyTenantAppearance
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Theme color — re-register so we override any earlier registrations
            // (Panel::boot's defaults, ApplyPalette plugin's config-based set).
            FilamentColor::register([
                'primary' => ThemeService::getPrimaryColor(),
                'danger' => Color::Red,
                'gray' => Color::Gray,
                'info' => Color::Blue,
                'success' => Color::Green,
                'warning' => Color::Orange,
                'purple' => Color::Purple,
            ]);

            // Locale — apply the tenant's saved language preference. Falls back
            // to the locale already set by SetLocale / browser detection if no
            // tenant override exists.
            $tenantLocale = Setting::get('locale');
            if (is_string($tenantLocale) && in_array($tenantLocale, ['id', 'en'], true)) {
                app()->setLocale($tenantLocale);
            }
        } catch (\Throwable $e) {
            // Defensively allow the request to continue with default appearance
            // if anything (missing tables during provisioning, broken cache,
            // etc.) goes wrong.
        }

        return $next($request);
    }
}

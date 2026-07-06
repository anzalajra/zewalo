<?php

namespace App\Providers\Filament;

use App\Http\Middleware\EnsureTenantSubscriptionActive;
use App\Http\Middleware\RedirectCentralDomainToPanel;
use App\Models\Setting;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Saade\FilamentFullCalendar\FilamentFullCalendarPlugin;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

class AdminPanelProvider extends PanelProvider
{
    public function register(): void
    {
        parent::register();
    }

    public function panel(Panel $panel): Panel
    {
        // NOTE: This method runs ONCE during the Laravel boot phase, before any
        // request hits the panel — i.e. before tenant context is initialized.
        // Reading `Setting::get(...)` directly here always queries the central
        // database (or default connection), which is why we wrap every per-tenant
        // value below in a Closure. Filament evaluates Closures passed to
        // `brandName`, `brandLogo`, `favicon`, `topNavigation`, and `topbar` at
        // render time, by which point the tenant DB is active. The primary color
        // is applied via the `ApplyTenantAppearance` auth middleware further down.

        $brandNameResolver = function () {
            try {
                if (Schema::hasTable('settings')) {
                    $siteName = Setting::get('site_name');
                    if ($siteName) {
                        return $siteName;
                    }
                }
            } catch (\Throwable $e) {
                // ignore — fall through to default
            }
            return config('app.name');
        };

        $brandLogoResolver = function () {
            try {
                if (Schema::hasTable('settings')) {
                    $logo = Setting::get('site_logo') ?: Setting::get('logo');
                    if ($logo) {
                        return \App\Services\Storage\R2Url::signed($logo);
                    }
                }
            } catch (\Throwable $e) {
                // ignore
            }
            return null;
        };

        // Configure the palette plugin's static fallback. The palette plugin's
        // ApplyPalette middleware writes these to FilamentColor on each request
        // BEFORE our ApplyTenantAppearance middleware, so we register sane
        // defaults here knowing our middleware will override `primary` later.
        Config::set('filament-palette.palette.dynamic_theme', [
            'primary' => Color::Amber,
            'warning' => Color::Amber,
            'danger' => Color::Red,
            'success' => Color::Green,
            'info' => Color::Blue,
        ]);
        Config::set('filament-palette.default', 'dynamic_theme');

        $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->passwordReset()
            ->maxContentWidth(Width::Full)
            ->brandName($brandNameResolver)
            ->brandLogo($brandLogoResolver)
            ->favicon($brandLogoResolver)
            ->colors([
                // Default colors used as a fallback. The actual `primary` is
                // overridden per-tenant by ApplyTenantAppearance middleware.
                'primary' => Color::Amber,
                'danger' => Color::Red,
                'gray' => Color::Gray,
                'info' => Color::Blue,
                'success' => Color::Green,
                'warning' => Color::Orange,
                'purple' => Color::Purple,
            ]);

        // Resolve navigation layout lazily so it reflects the tenant's current
        // setting. Used both for `topNavigation()`/`topbar()` config and for
        // render-hook decisions below.
        $resolveUseTopNav = function (): bool {
            $layout = 'sidebar';
            try {
                if (Schema::hasTable('settings')) {
                    $layout = Setting::get('navigation_layout', 'sidebar');
                }
            } catch (\Throwable $e) {
                // ignore
            }
            return $layout === 'top' || $this->isPhone();
        };

        $panel
            ->renderHook(
                'panels::styles.after',
                fn () => view('filament.hooks.zw-styles')
            )
            ->renderHook(
                'panels::content.start',
                function () {
                    $tenant = tenant();
                    $output = '';

                    // Setup wizard banner
                    if ($tenant && $tenant->needsSetup() && session()->has('setup_wizard_redirected')) {
                        $output .= view('filament.hooks.setup-wizard-banner', [
                            'wizardUrl' => \App\Filament\Pages\SetupWizard::getUrl(),
                        ])->render();
                    }

                    // Subscription warning
                    if ($tenant && $tenant->isInGracePeriod()) {
                        $graceTo = $tenant->grace_period_ends_at?->format('d M Y') ?? '-';

                        $output .= view('filament.hooks.subscription-warning', [
                            'message' => "Subscription Anda telah berakhir. Perpanjang sebelum {$graceTo} untuk menghindari suspend.",
                            'billingUrl' => \App\Filament\Pages\SubscriptionBilling::getUrl(),
                        ])->render();
                    }

                    return $output;
                }
            )
            ->renderHook(
                'panels::head.end',
                fn () => view('filament.hooks.admin-pwa')
            )
            ->renderHook(
                'panels::content.end',
                fn () => view('filament.hooks.footer')
            )
            ->renderHook(
                'panels::body.end',
                fn () => view('filament.hooks.responsive-navigation')
            )
            ->renderHook(
                'panels::body.end',
                fn () => view('filament.hooks.floating-profile-capsule')
            )
            ->renderHook(
                'panels::body.end',
                fn () => view('filament.hooks.mobile-bottom-nav')
            )
            ->renderHook(
                'panels::body.end',
                fn () => view('filament.hooks.qr-scanner-listener')
            )
            ->renderHook(
                'panels::body.end',
                fn () => view('filament.hooks.floating-help-button')
            )
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\Filament\Clusters')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                \App\Filament\Widgets\DashboardHomeWidget::class,
            ])
            ->middleware([
                RedirectCentralDomainToPanel::class,  // Redirect central domains FIRST
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                InitializeTenancyByDomain::class,  // Initialize tenant context
                PreventAccessFromCentralDomains::class,  // Block access from localhost
            ])
            ->authMiddleware([
                Authenticate::class,
                \App\Http\Middleware\RedirectToSetupWizard::class,
                EnsureTenantSubscriptionActive::class,
            ])
            ->userMenuItems([
                MenuItem::make()
                    ->label('My Profile')
                    ->icon('heroicon-o-user-circle')
                    ->url(fn (): string => \App\Filament\Pages\Profile::getUrl()),
                MenuItem::make()
                    ->label('Subscription & Billing')
                    ->icon('heroicon-o-credit-card')
                    ->url(fn (): string => \App\Filament\Pages\SubscriptionBilling::getUrl()),
                MenuItem::make()
                    ->label('Documentation')
                    ->icon('heroicon-o-book-open')
                    ->url(fn (): string => 'https://' . config('app.domain', 'localhost') . '/documentation')
                    ->openUrlInNewTab(),
            ]);
        // NOTE: Tenant locale is applied by `ApplyTenantAppearance` middleware,
        // not here. Filament's `bootUsing()` runs during `Panel::boot()` which
        // is fired by the `panel:` middleware alias — that runs BEFORE the
        // panel's other middleware including `InitializeTenancyByDomain`, so
        // any `Setting::get()` call here would hit the central DB, not the
        // tenant's. See `ApplyTenantAppearance::handle()`.

        // Both `topNavigation()` and `topbar()` accept a Closure that is
        // evaluated at render time (inside Filament's layout blade), which is
        // after `InitializeTenancyByDomain` has run. We always register both so
        // a tenant changing `navigation_layout` from sidebar → top (or back)
        // takes effect on the next request without needing the panel to be
        // re-built.
        //
        // Layout matrix:
        //   - top nav mode (or mobile):  topNavigation=true,  topbar=true
        //     (the topbar is what *renders* the top navigation links)
        //   - sidebar mode (default):    topNavigation=false, topbar=false
        //     (sidebar already holds search/notifications; no need for a topbar)
        $panel
            ->topNavigation(fn () => $resolveUseTopNav())
            ->topbar(fn () => $resolveUseTopNav());

        return $panel
            ->bootUsing(function () {
                try {
                    // Safeguard against missing tables during tenant provisioning
                    if (! \Illuminate\Support\Facades\Schema::hasTable('posts') || ! \Illuminate\Support\Facades\Schema::hasTable('pages')) {
                        return;
                    }

                    \LaraZeus\Sky\SkyPlugin::get()
                        ->itemType(
                            'Post',
                            [
                                \Filament\Forms\Components\Select::make('post_id')
                                    ->label(__('zeus-sky::cms.post.select_post'))
                                    ->searchable()
                                    ->options(function () {
                                        try {
                                            return \LaraZeus\Sky\SkyPlugin::get()->getModel('Post')::published()->pluck('title', 'id');
                                        } catch (\Throwable $e) {
                                            return [];
                                        }
                                    }),
                            ],
                            'post_link'
                        )
                        ->itemType(
                            'Page',
                            [
                                \Filament\Forms\Components\Select::make('page_id')
                                    ->label(__('zeus-sky::cms.page.select_page'))
                                    ->searchable()
                                    ->options(function () {
                                        try {
                                            return \LaraZeus\Sky\SkyPlugin::get()->getModel('Post')::query()
                                                ->page()
                                                ->whereDate('published_at', '<=', now())
                                                ->pluck('title', 'id');
                                        } catch (\Throwable $e) {
                                            return [];
                                        }
                                    }),
                            ],
                            'page_link'
                        );
                } catch (\Throwable $e) {
                    // Posts table may not exist yet during tenant provisioning — skip Sky item types
                }
            })
            ->plugins([
                FilamentFullCalendarPlugin::make(),
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make(),
                \Octopy\Filament\Palette\PaletteSwitcherPlugin::make()
                    ->applyThemeGlobally(true)
                    ->hidden(fn () => true),
                \LaraZeus\Sky\SkyPlugin::make()
                    ->uploadDisk('r2')
                    ->uploadDirectory(fn () => \App\Services\Storage\TenantStorageService::getFilamentDirectory('cms'))
                    ->navigationGroupLabel('Page & Post')
                    ->hideResources([
                        \LaraZeus\Sky\Filament\Resources\PageResource::class,
                        \LaraZeus\Sky\Filament\Resources\FaqResource::class,
                        \LaraZeus\Sky\Filament\Resources\LibraryResource::class,
                        \LaraZeus\Sky\Filament\Resources\PostResource::class,
                        \LaraZeus\Sky\Filament\Resources\NavigationResource::class,
                        \LaraZeus\Sky\Filament\Resources\TagResource::class,
                    ]),
                \LaraZeus\SpatieTranslatable\SpatieTranslatablePlugin::make()
                    ->defaultLocales(['en', 'id']),
            ])
            // Append AFTER plugins so it runs after `PaletteSwitcherPlugin`'s
            // `ApplyPalette` middleware. ApplyPalette registers stale colors
            // from `config('filament-palette.palette.dynamic_theme')` which is
            // computed at boot (no tenant context); our middleware re-registers
            // the correct primary color from the tenant's Setting and wins
            // because FilamentColor's last `register()` call takes precedence.
            // `isPersistent: true` ensures it also runs on Livewire panel
            // requests so colors stay correct across SPA navigations.
            ->authMiddleware([
                \App\Http\Middleware\ApplyTenantAppearance::class,
            ], isPersistent: true)
            ->databaseNotifications()
            // Navigation Groups Order - mengatur urutan group di sidebar
            ->navigationGroups([
                __('admin.nav.rentals'),
                __('admin.nav.sales'),
                __('admin.nav.inventory'),
                'Page & Post',
                __('admin.document_type.nav_group'),
                __('admin.nav.system'),
            ])
            // Sidebar collapsible (opsional - bisa dihapus jika tidak perlu)
            ->sidebarCollapsibleOnDesktop();
    }

    /**
     * Detect whether the request comes from a PHONE (not a tablet).
     *
     * Phones get Filament's top-nav layout (topbar user menu); tablets deliberately
     * do NOT — a portrait tablet keeps the sidebar layout but has its sidebar hidden
     * and a bottom nav shown by the `gr-compact` engine (see responsive-navigation +
     * zw-styles hooks). iPads and Android tablets are therefore excluded here:
     *  - iPad UA is matched and rejected outright.
     *  - Android *phones* include the "Mobile" token; Android *tablets* omit it.
     */
    protected function isPhone(): bool
    {
        $userAgent = request()->header('User-Agent', '');
        if ($userAgent === '') {
            return false;
        }

        // Explicit tablets are not phones — they use the gr-compact chrome instead.
        if (preg_match('/iPad/i', $userAgent)) {
            return false;
        }
        // Android tablets lack the "Mobile" token that Android phones carry.
        if (preg_match('/Android/i', $userAgent) && ! preg_match('/Mobile/i', $userAgent)) {
            return false;
        }

        $phonePatterns = [
            '/iPhone/i',
            '/iPod/i',
            '/Android.*Mobile/i',
            '/Windows Phone/i',
            '/IEMobile/i',
            '/BlackBerry/i',
            '/BB10/i',
            '/Opera Mini/i',
            '/webOS/i',
            '/Mobile Safari/i',
        ];

        foreach ($phonePatterns as $pattern) {
            if (preg_match($pattern, $userAgent)) {
                return true;
            }
        }

        // Generic fallback: any remaining UA that self-identifies as "Mobile".
        return (bool) preg_match('/Mobile/i', $userAgent);
    }
}

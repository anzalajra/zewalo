<?php

namespace App\Providers\Filament;

use App\Http\Middleware\PreventTenancyInitialization;
use BezhanSalleh\GoogleAnalytics\GoogleAnalyticsPlugin;
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
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class CentralPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $brandName = 'Zewalo Central';
        $brandLogo = null;
        $favicon = null;

        try {
            $siteName = \App\Models\CentralSetting::get('branding_site_name');
            if ($siteName) {
                $brandName = $siteName . ' Central';
            }
            $logo = \App\Models\CentralSetting::get('branding_logo');
            if ($logo) {
                $brandLogo = \App\Services\Storage\R2Url::signed($logo);
            }
            $fav = \App\Models\CentralSetting::get('branding_favicon');
            if ($fav) {
                $favicon = \App\Services\Storage\R2Url::signed($fav);
            }
        } catch (\Exception $e) {
            // Fallback to defaults if central_settings table doesn't exist yet
        }

        return $panel
            ->id('central')
            ->domain('sa.'.config('app.domain', 'localhost'))
            ->path('admin')
            ->login()
            ->maxContentWidth(Width::Full)
            ->brandName($brandName)
            ->brandLogo($brandLogo)
            ->favicon($favicon)
            ->colors([
                'primary' => Color::Indigo,
                'danger' => Color::Red,
                'gray' => Color::Slate,
                'info' => Color::Blue,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
            ])
            ->viteTheme('resources/css/filament/central/theme.css')
            ->discoverResources(in: app_path('Filament/Central/Resources'), for: 'App\Filament\Central\Resources')
            ->discoverPages(in: app_path('Filament/Central/Pages'), for: 'App\Filament\Central\Pages')
            ->pages([
                Dashboard::class,
                \App\Filament\Pages\Profile::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Central/Widgets'), for: 'App\Filament\Central\Widgets')
            ->widgets([
                AccountWidget::class,
            ])
            ->middleware([
                PreventTenancyInitialization::class, // MUST be first to prevent tenant DB switch
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->authGuard('web') // Use default web guard with central connection
            ->plugins([
                GoogleAnalyticsPlugin::make(),
            ])
            ->databaseNotifications()
            ->sidebarCollapsibleOnDesktop()
            ->userMenuItems([
                MenuItem::make()
                    ->label('My Profile')
                    ->icon('heroicon-o-user-circle')
                    ->url(fn () => \App\Filament\Pages\Profile::getUrl()),
                MenuItem::make()
                    ->label(fn () => app()->getLocale() === 'id' ? '🇬🇧 English' : '🇮🇩 Bahasa Indonesia')
                    ->icon('heroicon-o-language')
                    ->url(fn () => request()->fullUrlWithQuery(['lang' => app()->getLocale() === 'id' ? 'en' : 'id'])),
            ]);
    }
}

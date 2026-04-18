<?php

namespace App\Providers;

use App\Models\Rental;
use App\Models\Setting;
use App\Models\FinanceTransaction;
use App\Models\JournalEntryItem;
use App\Models\UnitKit;
use App\Observers\FinanceTransactionObserver;
use App\Observers\JournalEntryItemObserver;
use App\Observers\RentalObserver;
use App\Observers\UnitKitObserver;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Cart;
use App\Policies\CartPolicy;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {

         \Illuminate\Support\Facades\URL::forceScheme('https');
         
        $this->loadMigrationsFrom(database_path('migrations/central'));

        Rental::observe(RentalObserver::class);
        FinanceTransaction::observe(FinanceTransactionObserver::class);
        JournalEntryItem::observe(JournalEntryItemObserver::class);
        UnitKit::observe(UnitKitObserver::class);
    
        Gate::policy(Cart::class, CartPolicy::class);

        View::composer('pdf.*', function ($view) {
            $settings = Setting::where('key', 'like', 'doc_%')
                ->pluck('value', 'key')
                ->toArray();

            // Convert doc_logo path to base64 data URI for dompdf (can't access remote URLs)
            if (! empty($settings['doc_logo'])) {
                try {
                    $logoContents = \Illuminate\Support\Facades\Storage::disk('r2')->get($settings['doc_logo']);
                    if ($logoContents) {
                        $mime = 'image/png';
                        $ext = strtolower(pathinfo($settings['doc_logo'], PATHINFO_EXTENSION));
                        $mimeMap = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif', 'svg' => 'image/svg+xml', 'webp' => 'image/webp'];
                        if (isset($mimeMap[$ext])) {
                            $mime = $mimeMap[$ext];
                        }
                        $settings['doc_logo_data_uri'] = 'data:' . $mime . ';base64,' . base64_encode($logoContents);
                    }
                } catch (\Exception $e) {
                    // R2 not available, logo won't show in PDF
                }
            }

            $view->with('doc_settings', $settings);
        });

        // Inject Central Branding for landing/auth pages
        View::composer(
            ['layouts.landing', 'landing.partials.header', 'landing.partials.footer', 'livewire.register-tenant', 'livewire.tenant-login'],
            function ($view) {
                try {
                    $view->with([
                        'centralBrandName'  => \App\Services\CentralBrandingService::siteName(),
                        'centralBrandLogo'  => \App\Services\CentralBrandingService::logoUrl(),
                        'centralBrandDesc'  => \App\Services\CentralBrandingService::siteDescription(),
                        'centralFavicon'    => \App\Services\CentralBrandingService::faviconUrl(),
                    ]);
                } catch (\Exception $e) {
                    // central_settings table might not exist yet
                }
            }
        );

        // Inject Theme Colors
        View::composer(['layouts.app', 'layouts.frontend', 'layouts.guest'], function ($view) {
            $primaryColor = \App\Services\ThemeService::getPrimaryColor();
            
            $cssVariables = [];
            foreach ($primaryColor as $shade => $value) {
                $cssVariables[] = "--primary-{$shade}: {$value};";
            }
            
            $view->with('themeCssVariables', implode(' ', $cssVariables));
        });

        try {
            // Apply central branding site name (base level, can be overridden by tenant)
            $centralBrandName = \App\Models\CentralSetting::get('branding_site_name');
            if ($centralBrandName) {
                config(['app.name' => $centralBrandName]);
            }

            if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                // Global App Config (tenant-level override)
                $siteName = Setting::get('site_name');
                if ($siteName) {
                    config(['app.name' => $siteName]);
                    
                    // Set default mail from name to site name if not explicitly configured
                    if (!Setting::get('mail_from_name')) {
                        config(['mail.from.name' => $siteName]);
                    }
                }

                // Apply central mail settings (SMTP server + default from, managed by central admin)
                $centralMailSettings = \App\Filament\Central\Pages\EmailSettings::loadSettings();
                if (! empty($centralMailSettings)) {
                    $mailConfig = [];
                    if (! empty($centralMailSettings['mail_mailer'])) $mailConfig['mail.default'] = $centralMailSettings['mail_mailer'];
                    if (! empty($centralMailSettings['mail_host'])) $mailConfig['mail.mailers.smtp.host'] = $centralMailSettings['mail_host'];
                    if (! empty($centralMailSettings['mail_port'])) $mailConfig['mail.mailers.smtp.port'] = $centralMailSettings['mail_port'];
                    if (isset($centralMailSettings['mail_encryption'])) $mailConfig['mail.mailers.smtp.encryption'] = $centralMailSettings['mail_encryption'] ?: null;
                    if (! empty($centralMailSettings['mail_username'])) $mailConfig['mail.mailers.smtp.username'] = $centralMailSettings['mail_username'];
                    if (! empty($centralMailSettings['mail_password'])) $mailConfig['mail.mailers.smtp.password'] = $centralMailSettings['mail_password'];
                    if (! empty($centralMailSettings['mail_from_address'])) $mailConfig['mail.from.address'] = $centralMailSettings['mail_from_address'];
                    if (! empty($centralMailSettings['mail_from_name'])) $mailConfig['mail.from.name'] = $centralMailSettings['mail_from_name'];

                    if (! empty($mailConfig)) {
                        config($mailConfig);
                    }

                    // Apply AWS SES credentials when using ses or sesv2 driver
                    if (! empty($centralMailSettings['mail_mailer']) &&
                        in_array($centralMailSettings['mail_mailer'], ['ses', 'sesv2'])) {
                        if (! empty($centralMailSettings['aws_access_key_id'])) {
                            config(['services.ses.key'    => $centralMailSettings['aws_access_key_id']]);
                            config(['services.ses.secret' => $centralMailSettings['aws_secret_access_key'] ?? null]);
                            config(['services.ses.region' => $centralMailSettings['aws_default_region'] ?? 'ap-southeast-1']);
                        }
                    }
                }

                // Override from address/name with tenant-specific sender identity
                if ($fromAddress = Setting::get('mail_from_address')) {
                    config(['mail.from.address' => $fromAddress]);
                }
                if ($fromName = Setting::get('mail_from_name')) {
                    config(['mail.from.name' => $fromName]);
                }
            }
        } catch (\Exception $e) {
            // Settings table might not exist yet during migration
        }

        // Configure Livewire Update Route to support both Central and Tenant domains
        \Livewire\Livewire::setUpdateRoute(function ($handle) {
            return \Illuminate\Support\Facades\Route::post('/livewire/update', $handle)
                ->middleware([
                    'web',
                    \App\Http\Middleware\TenantLivewireMiddleware::class,
                ]);
        });
    }
}
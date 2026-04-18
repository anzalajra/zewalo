<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\Storage\R2StorageService;
use App\Services\Storage\TenantStorageService;
use Closure;
use Filament\Forms\Components\FileUpload;
use Filament\Infolists\Components\ImageEntry;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Support\ServiceProvider;
use Stancl\Tenancy\Tenancy;

class StorageServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(TenantStorageService::class, function ($app) {
            return new TenantStorageService;
        });

        $this->app->singleton(R2StorageService::class, function ($app) {
            return new R2StorageService;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->extendFilamentFileUpload();
        $this->forceSignedUrlsOnR2Images();
    }

    /**
     * Force Filament's image components to emit signed temporary URLs for the private R2 disk,
     * since direct url() calls return 404 when the bucket isn't public.
     */
    protected function forceSignedUrlsOnR2Images(): void
    {
        if (class_exists(ImageColumn::class)) {
            ImageColumn::configureUsing(function (ImageColumn $column): void {
                $column->visibility('private');
            });
        }

        if (class_exists(ImageEntry::class)) {
            ImageEntry::configureUsing(function (ImageEntry $entry): void {
                $entry->visibility('private');
            });
        }
    }

    /**
     * Extend Filament's FileUpload with tenant-aware methods.
     */
    protected function extendFilamentFileUpload(): void
    {
        // Add macro for tenant-aware directory
        FileUpload::macro('tenantDirectory', function (string|Closure $directory) {
            /** @var FileUpload $this */
            $component = $this;

            // Set disk to R2 (ensures R2 config is loaded from central settings)
            $component->disk(TenantStorageService::getFilamentDisk());

            // Set default visibility
            $component->visibility(TenantStorageService::getFilamentVisibility());

            // Set directory with tenant prefix
            $component->directory(function () use ($directory): string {
                // Ensure R2 config is loaded right before directory resolution (upload time)
                CentralSettingsServiceProvider::ensureR2Config();
                $dir = $directory instanceof Closure ? $directory() : $directory;

                return TenantStorageService::getFilamentDirectory($dir);
            });

            return $component;
        });

        // Add macro for using R2 storage without tenant prefix (for central admin)
        FileUpload::macro('r2Directory', function (string|Closure $directory) {
            /** @var FileUpload $this */
            $component = $this;

            $component->disk('r2');
            $component->visibility('private');

            $component->directory(function () use ($directory): string {
                return $directory instanceof Closure ? $directory() : $directory;
            });

            return $component;
        });

        // Add macro to use R2 storage with auto tenant prefix detection
        FileUpload::macro('r2Tenant', function (string|Closure $directory = '') {
            /** @var FileUpload $this */
            $component = $this;

            $component->disk('r2');
            $component->visibility('private');

            $component->directory(function () use ($directory): string {
                $tenantId = null;

                if (app()->bound(Tenancy::class)) {
                    $tenancy = app(Tenancy::class);
                    $tenantId = $tenancy->tenant?->getTenantKey();
                }

                $prefix = $tenantId ? "tenant_{$tenantId}" : 'central';
                $dir = $directory instanceof Closure ? $directory() : $directory;

                return $dir ? "{$prefix}/{$dir}" : $prefix;
            });

            return $component;
        });
    }
}

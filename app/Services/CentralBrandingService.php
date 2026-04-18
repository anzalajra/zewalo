<?php

namespace App\Services;

use App\Models\CentralSetting;
use App\Providers\CentralSettingsServiceProvider;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CentralBrandingService
{
    public static function siteName(): string
    {
        return CentralSetting::get('branding_site_name', 'Zewalo');
    }

    public static function siteDescription(): string
    {
        return CentralSetting::get('branding_site_description', 'Platform manajemen rental terbaik. Kelola bisnis penyewaan Anda dalam hitungan menit.');
    }

    public static function logoUrl(): ?string
    {
        return static::signedUrl(CentralSetting::get('branding_logo'));
    }

    public static function faviconUrl(): ?string
    {
        return static::signedUrl(CentralSetting::get('branding_favicon'));
    }

    public static function ogImageUrl(): ?string
    {
        return static::signedUrl(CentralSetting::get('branding_og_image'));
    }

    public static function metaKeywords(): ?string
    {
        return CentralSetting::get('branding_meta_keywords');
    }

    public static function hasLogo(): bool
    {
        return CentralSetting::get('branding_logo') !== null;
    }

    /**
     * Generate a signed (temporary) URL for a private R2 object.
     * Falls back to public URL if signing fails. Returns null when no path is given.
     */
    protected static function signedUrl(?string $path, int $minutes = 60): ?string
    {
        if (! $path) {
            return null;
        }

        CentralSettingsServiceProvider::ensureR2Config();

        try {
            /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
            $disk = Storage::disk('r2');

            return $disk->temporaryUrl($path, now()->addMinutes($minutes));
        } catch (\Throwable $e) {
            Log::warning('CentralBrandingService: failed to sign URL, falling back to public url', [
                'path' => $path,
                'error' => $e->getMessage(),
            ]);

            try {
                return Storage::disk('r2')->url($path);
            } catch (\Throwable) {
                return null;
            }
        }
    }
}

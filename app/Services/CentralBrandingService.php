<?php

namespace App\Services;

use App\Models\CentralSetting;
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
        $path = CentralSetting::get('branding_logo');

        return $path ? Storage::disk('r2')->url($path) : null;
    }

    public static function faviconUrl(): ?string
    {
        $path = CentralSetting::get('branding_favicon');

        return $path ? Storage::disk('r2')->url($path) : null;
    }

    public static function ogImageUrl(): ?string
    {
        $path = CentralSetting::get('branding_og_image');

        return $path ? Storage::disk('r2')->url($path) : null;
    }

    public static function metaKeywords(): ?string
    {
        return CentralSetting::get('branding_meta_keywords');
    }

    public static function hasLogo(): bool
    {
        return CentralSetting::get('branding_logo') !== null;
    }
}

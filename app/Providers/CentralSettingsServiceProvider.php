<?php

namespace App\Providers;

use App\Models\CentralSetting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

class CentralSettingsServiceProvider extends ServiceProvider
{
    protected static bool $r2Loaded = false;

    public function boot(): void
    {
        try {
            $this->loadR2Settings();
        } catch (\Exception $e) {
            // Table may not exist yet during migration
        }
    }

    /**
     * Ensure R2 settings are loaded into config.
     * Can be called multiple times safely — only queries DB once per request.
     */
    public static function ensureR2Config(): void
    {
        if (static::$r2Loaded) {
            return;
        }

        try {
            app(static::class)->loadR2Settings();
        } catch (\Exception $e) {
            // Silently fail if DB not available
        }
    }

    protected function loadR2Settings(): void
    {
        $r2Settings = CentralSetting::getGroup('r2');

        if (empty($r2Settings)) {
            static::$r2Loaded = true;

            return;
        }

        $configMap = [
            'r2_access_key_id' => 'filesystems.disks.r2.key',
            'r2_secret_access_key' => 'filesystems.disks.r2.secret',
            'r2_bucket' => 'filesystems.disks.r2.bucket',
            'r2_endpoint' => 'filesystems.disks.r2.endpoint',
            'r2_url' => 'filesystems.disks.r2.url',
            'r2_region' => 'filesystems.disks.r2.region',
            'r2_use_path_style_endpoint' => 'filesystems.disks.r2.use_path_style_endpoint',
        ];

        foreach ($configMap as $settingKey => $configKey) {
            $value = $r2Settings[$settingKey] ?? null;

            if ($value !== null && $value !== '') {
                if ($settingKey === 'r2_use_path_style_endpoint') {
                    $value = (bool) $value;
                }

                // Prevent bucket name duplication in URL or endpoint
                // If r2_url/r2_endpoint ends with the bucket name, strip it to avoid paths like bucket/bucket/
                if ($settingKey === 'r2_url' || $settingKey === 'r2_endpoint') {
                    $bucket = $r2Settings['r2_bucket'] ?? config('filesystems.disks.r2.bucket');
                    if ($bucket) {
                        $value = rtrim($value, '/');
                        $path = parse_url($value, PHP_URL_PATH) ?? '';
                        if ($path === '/'.$bucket || str_ends_with($path, '/'.$bucket)) {
                            $value = preg_replace('#/'.preg_quote($bucket, '#').'$#', '', $value);
                        }
                    }
                }

                config([$configKey => $value]);
            }
        }

        // Purge cached R2 disk instance so Storage::disk('r2') picks up new config
        Storage::purge('r2');

        static::$r2Loaded = true;
    }
}

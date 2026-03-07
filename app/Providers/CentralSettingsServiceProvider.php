<?php

namespace App\Providers;

use App\Models\CentralSetting;
use Illuminate\Support\ServiceProvider;

class CentralSettingsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        try {
            $this->loadR2Settings();
        } catch (\Exception $e) {
            // Table may not exist yet during migration
        }
    }

    protected function loadR2Settings(): void
    {
        $r2Settings = CentralSetting::getGroup('r2');

        if (empty($r2Settings)) {
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
                config([$configKey => $value]);
            }
        }
    }
}

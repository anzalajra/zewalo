<?php

declare(strict_types=1);

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use Stancl\Tenancy\Events\TenancyBootstrapped;

class EnsureTenantStorageDirectories
{
    public function handle(TenancyBootstrapped $event): void
    {
        $dirs = [
            'framework/cache',
            'framework/views',
            'framework/sessions',
            'framework/testing',
            'logs',
            'app/public',
        ];

        foreach ($dirs as $dir) {
            $path = storage_path($dir);

            if (! is_dir($path)) {
                try {
                    mkdir($path, 0755, recursive: true);
                } catch (\Throwable $e) {
                    Log::warning("EnsureTenantStorageDirectories: could not create {$path}: {$e->getMessage()}");
                }
            }
        }
    }
}

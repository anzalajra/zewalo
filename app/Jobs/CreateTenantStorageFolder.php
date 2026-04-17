<?php

declare(strict_types=1);

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\Tenant;

class CreateTenantStorageFolder implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Tenant $tenant
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Ensure R2 config is loaded from CentralSetting before any S3 call
        \App\Providers\CentralSettingsServiceProvider::ensureR2Config();

        if (! config('filesystems.disks.r2.bucket')) {
            Log::info("CreateTenantStorageFolder: R2 bucket not configured, skipping storage folder creation for tenant '{$this->tenant->id}'");
            return;
        }

        $tenantPrefix = "tenant_{$this->tenant->id}";

        $directories = [
            'products',
            'brands',
            'categories',
            'customer-documents',
            'finance/bills',
            'finance/expenses',
            'finance/transactions',
            'documents',
        ];

        try {
            foreach ($directories as $dir) {
                $path = "{$tenantPrefix}/{$dir}/.gitkeep";

                if (! Storage::disk('r2')->exists($path)) {
                    Storage::disk('r2')->put($path, '');
                }
            }

            // Verify tenant prefix is writable by running a probe
            $probe = app(\App\Services\Storage\R2StorageService::class)->probe($tenantPrefix);

            if (! $probe['success']) {
                Log::error("CreateTenantStorageFolder: R2 write probe FAILED for tenant '{$this->tenant->id}' — bucket may not be writable", [
                    'message' => $probe['message'],
                ]);
            } else {
                Log::info("CreateTenantStorageFolder: Created & verified storage folders for tenant '{$this->tenant->id}' ({$probe['latency_ms']}ms)");
            }
        } catch (\Throwable $e) {
            Log::error("CreateTenantStorageFolder: Failed to create storage folders for tenant '{$this->tenant->id}': {$e->getMessage()}", [
                'exception' => get_class($e),
                'tenant_id' => $this->tenant->id,
            ]);
            // Don't rethrow — tenant DB exists; admin can fix R2 later via Central Admin
        }
    }
}

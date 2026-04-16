<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Tenant;
use Illuminate\Console\Command;

class BackfillSetupStatus extends Command
{
    protected $signature = 'tenants:backfill-setup-status';

    protected $description = 'Set setup_status to completed for existing tenants that already have products or site_logo';

    public function handle(): int
    {
        $tenants = Tenant::where('setup_status', 'pending')->get();

        if ($tenants->isEmpty()) {
            $this->info('No tenants with pending setup status found.');

            return self::SUCCESS;
        }

        $this->info("Found {$tenants->count()} tenant(s) with pending setup status.");

        $bar = $this->output->createProgressBar($tenants->count());
        $completed = 0;
        $skipped = 0;

        foreach ($tenants as $tenant) {
            try {
                $shouldComplete = false;

                $tenant->run(function () use (&$shouldComplete) {
                    // Check if tenant has site_logo setting
                    $hasLogo = \App\Models\Setting::where('key', 'site_logo')
                        ->whereNotNull('value')
                        ->where('value', '!=', '')
                        ->exists();

                    // Check if tenant has any products
                    $hasProducts = \App\Models\Product::exists();

                    $shouldComplete = $hasLogo || $hasProducts;
                });

                if ($shouldComplete) {
                    $tenant->update(['setup_status' => 'completed']);
                    $completed++;
                } else {
                    $skipped++;
                }
            } catch (\Throwable $e) {
                $this->warn("Failed for tenant {$tenant->id}: {$e->getMessage()}");
                $skipped++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Done: {$completed} marked completed, {$skipped} kept pending.");

        return self::SUCCESS;
    }
}

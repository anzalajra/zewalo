<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Providers\CentralSettingsServiceProvider;
use App\Services\Storage\R2StorageService;
use Illuminate\Console\Command;

/**
 * Verify every scope that writes to R2 (central + each tenant prefix) is actually writable.
 *
 * Usage:
 *   php artisan r2:probe                 # probe central + all tenants
 *   php artisan r2:probe --central       # probe central only
 *   php artisan r2:probe --tenant=abc    # probe a single tenant
 */
class ProbeR2Writes extends Command
{
    protected $signature = 'r2:probe
                            {--central : Probe only the central prefix}
                            {--tenant= : Probe a single tenant by ID}';

    protected $description = 'Write-test the R2 bucket for central and tenant scopes to catch credential/permission issues early';

    public function handle(R2StorageService $service): int
    {
        CentralSettingsServiceProvider::ensureR2Config(force: true);

        if (! $service->isConfigured()) {
            $this->error('R2 is not configured. Set credentials in Central Admin → R2 Storage Settings first.');

            return self::FAILURE;
        }

        $this->info('R2 bucket: '.config('filesystems.disks.r2.bucket'));
        $this->info('Endpoint:  '.config('filesystems.disks.r2.endpoint'));
        $this->newLine();

        if ($tenantId = $this->option('tenant')) {
            $result = $service->probe('tenant_'.$tenantId);
            $this->renderResult("tenant_{$tenantId}", $result);

            return $result['success'] ? self::SUCCESS : self::FAILURE;
        }

        if ($this->option('central')) {
            $result = $service->probe('central');
            $this->renderResult('central', $result);

            return $result['success'] ? self::SUCCESS : self::FAILURE;
        }

        $this->info('Probing central + all tenants...');
        $this->newLine();

        $results = $service->probeAll();
        $failures = 0;

        foreach ($results as $r) {
            $this->renderResult($r['scope'], $r);
            if (! $r['success']) {
                $failures++;
            }
        }

        $this->newLine();
        $this->info(sprintf(
            'Summary: %d total, %d OK, %d FAILED.',
            count($results),
            count($results) - $failures,
            $failures,
        ));

        return $failures === 0 ? self::SUCCESS : self::FAILURE;
    }

    protected function renderResult(string $scope, array $result): void
    {
        $icon = $result['success'] ? '<fg=green>✓</>' : '<fg=red>✗</>';
        $latency = $result['latency_ms'].'ms';

        $this->line(sprintf(
            ' %s  %-40s  %s  %s',
            $icon,
            $scope,
            $latency,
            $result['message'],
        ));
    }
}

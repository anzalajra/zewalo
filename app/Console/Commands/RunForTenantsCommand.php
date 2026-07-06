<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Tenant;
use App\Services\TenantIssueReporter;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Runs an artisan command inside every operational tenant's database context.
 *
 * Zewalo's tenant-scoped scheduled jobs (rentals:check-late, reminders,
 * depreciation, recurring rentals, maintenance flag-due, ...) query tenant
 * models on the default connection, which only resolves once tenancy is
 * initialized. This wrapper iterates the tenants, initializes each one via
 * Stancl's Tenant::run(), and isolates failures per tenant so a single broken
 * tenant DB does not abort the run for everyone else. Failures are reported to
 * the central Tenant Issues tracker.
 *
 * Usage (in routes/console.php):
 *   Schedule::command('tenants:run-scoped rentals:check-late')->everyFiveMinutes();
 */
class RunForTenantsCommand extends Command
{
    protected $signature = 'tenants:run-scoped
                            {subcommand : Artisan command name to run inside each tenant context}
                            {--tenants=* : Limit to specific tenant IDs (default: all operational tenants)}
                            {--include-suspended : Also run for suspended tenants}';

    protected $description = 'Run an artisan command inside every operational tenant database context, isolating errors per tenant.';

    public function handle(): int
    {
        $sub = (string) $this->argument('subcommand');

        $query = Tenant::query();

        $only = $this->option('tenants');
        if (! empty($only)) {
            $query->whereIn('id', $only);
        } elseif (! $this->option('include-suspended')) {
            // Operational tenants only — skip suspended so scheduled jobs
            // never touch shut-off accounts.
            $query->whereIn('status', ['active', 'trial', 'grace_period']);
        }

        $tenants = $query->get();

        if ($tenants->isEmpty()) {
            $this->info("No tenants matched — nothing to run for '{$sub}'.");

            return self::SUCCESS;
        }

        $this->info("Running '{$sub}' for {$tenants->count()} tenant(s)...");

        $succeeded = 0;
        $failed = 0;

        foreach ($tenants as $tenant) {
            $hadError = false;

            try {
                $tenant->run(function () use ($sub, $tenant, &$hadError) {
                    try {
                        $this->callSilent($sub);
                    } catch (Throwable $e) {
                        // Reported here while tenancy is still initialized so
                        // TenantIssueReporter attributes it to the right tenant.
                        $hadError = true;
                        $this->reportFailure($sub, $tenant, $e);
                    }
                });
            } catch (Throwable $e) {
                // Tenancy initialization itself failed (e.g. tenant DB down);
                // the inner reporter never ran, so log with explicit context.
                $hadError = true;
                Log::error("tenants:run-scoped could not initialize tenant {$tenant->id} for '{$sub}'", [
                    'tenant_id' => $tenant->id,
                    'command' => $sub,
                    'error' => $e->getMessage(),
                ]);
            }

            $hadError ? $failed++ : $succeeded++;
        }

        $this->info("Done. Succeeded: {$succeeded}, Failed: {$failed}.");

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }

    protected function reportFailure(string $sub, Tenant $tenant, Throwable $e): void
    {
        Log::error("tenants:run-scoped: '{$sub}' failed for tenant {$tenant->id}", [
            'tenant_id' => $tenant->id,
            'command' => $sub,
            'error' => $e->getMessage(),
        ]);

        try {
            TenantIssueReporter::reportException(
                e: $e,
                code: 'SCHEDULED_COMMAND_FAILED',
                title: "Scheduled command '{$sub}' failed",
                area: 'scheduler',
                severity: 'error',
                context: ['command' => $sub, 'tenant_id' => $tenant->id],
            );
        } catch (Throwable) {
            // Never let error-reporting failure abort the tenant loop.
        }
    }
}

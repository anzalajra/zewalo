<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Filament\Clusters\Settings\SettingsCluster;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Read-only health diagnostics for the tenant admin (Settings cluster).
 *
 * Adapted for Zewalo's multi-tenant / Postgres / R2 setup: the dangerous, host-global
 * actions from the single-tenant original (run migrations, storage:link, bootstrap-cache
 * deletion) are intentionally omitted — those belong to Central Admin, not a tenant, and
 * would affect the whole shared container. Only tenant-safe cache clearing and log
 * truncation are exposed.
 */
class SystemCheckup extends Page
{
    protected static ?string $cluster = SettingsCluster::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cpu-chip';

    protected static ?string $navigationLabel = 'System Checkup';

    protected static ?int $navigationSort = 11;

    protected string $view = 'filament.clusters.settings.pages.system-checkup';

    protected function getViewData(): array
    {
        return [
            'systemInfo' => [
                'PHP Version' => phpversion(),
                'Laravel Version' => app()->version(),
                'Database Size' => $this->checkDatabaseSize(),
            ],
            'checks' => [
                'database' => $this->checkDatabase(),
                'storage' => $this->checkStorage(),
                'cache' => $this->checkCache(),
                'logs' => $this->checkLogs(),
                'queue' => $this->checkFailedJobs(),
            ],
        ];
    }

    protected function checkDatabaseSize(): string
    {
        try {
            // Postgres: size of the current database.
            $row = DB::selectOne('SELECT pg_database_size(current_database()) AS size');
            $bytes = (int) ($row->size ?? 0);

            return round($bytes / 1024 / 1024, 2).' MB';
        } catch (\Throwable $e) {
            return 'Unknown';
        }
    }

    protected function checkDatabase(): array
    {
        try {
            DB::connection()->getPdo();

            return ['status' => 'ok', 'message' => 'Connected', 'color' => 'success'];
        } catch (\Throwable $e) {
            return ['status' => 'error', 'message' => $e->getMessage(), 'color' => 'danger'];
        }
    }

    protected function checkStorage(): array
    {
        if (is_writable(storage_path())) {
            return ['status' => 'ok', 'message' => 'Writable', 'color' => 'success'];
        }

        return ['status' => 'error', 'message' => 'Not Writable', 'color' => 'danger'];
    }

    protected function checkCache(): array
    {
        try {
            Cache::put('system_check', 'ok', 10);
            if (Cache::get('system_check') === 'ok') {
                return ['status' => 'ok', 'message' => 'Working', 'color' => 'success'];
            }
        } catch (\Throwable $e) {
            // ignore
        }

        return ['status' => 'error', 'message' => 'Not Working', 'color' => 'danger'];
    }

    protected function checkLogs(): array
    {
        $logPath = storage_path('logs/laravel.log');
        if (file_exists($logPath)) {
            $sizeMb = round(filesize($logPath) / 1024 / 1024, 2);
            if ($sizeMb > 50) {
                return ['status' => 'warning', 'message' => "Large Log File ({$sizeMb}MB)", 'color' => 'warning'];
            }

            return ['status' => 'ok', 'message' => "Normal ({$sizeMb}MB)", 'color' => 'success'];
        }

        return ['status' => 'ok', 'message' => 'No Log File', 'color' => 'success'];
    }

    protected function checkFailedJobs(): array
    {
        try {
            $count = DB::table('failed_jobs')->count();
            if ($count > 0) {
                return ['status' => 'warning', 'message' => "{$count} Failed Jobs", 'color' => 'warning'];
            }

            return ['status' => 'ok', 'message' => 'No Failed Jobs', 'color' => 'success'];
        } catch (\Throwable $e) {
            return ['status' => 'error', 'message' => 'Queue Check Failed', 'color' => 'danger'];
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('refresh')
                ->label('Refresh Status')
                ->icon('heroicon-o-arrow-path')
                ->action(fn () => $this->dispatch('$refresh')),
        ];
    }

    public function clearCacheAction(): Action
    {
        return Action::make('clear_cache')
            ->label('Clear Cache')
            ->icon('heroicon-o-trash')
            ->color('warning')
            ->requiresConfirmation()
            ->action(function () {
                try {
                    Artisan::call('config:clear');
                    Artisan::call('view:clear');
                    Artisan::call('cache:clear');

                    Notification::make()
                        ->title('Cache cleared')
                        ->body('Configuration, view, and application cache cleared.')
                        ->success()
                        ->send();
                } catch (\Throwable $e) {
                    Notification::make()->title('Cache clearing failed')->body($e->getMessage())->danger()->send();
                }
            });
    }

    public function cleanLogsAction(): Action
    {
        return Action::make('clean_logs')
            ->label('Truncate Logs')
            ->icon('heroicon-o-document-text')
            ->color('gray')
            ->requiresConfirmation()
            ->action(function () {
                try {
                    $logPath = storage_path('logs/laravel.log');
                    if (file_exists($logPath)) {
                        $sizeInMB = round(filesize($logPath) / 1024 / 1024, 2);
                        file_put_contents($logPath, '');

                        Notification::make()
                            ->title('Logs truncated')
                            ->body("Cleared {$sizeInMB} MB of log data.")
                            ->success()
                            ->send();
                    } else {
                        Notification::make()->title('No log file found')->info()->send();
                    }
                } catch (\Throwable $e) {
                    Notification::make()->title('Log truncation failed')->body($e->getMessage())->danger()->send();
                }
            });
    }
}

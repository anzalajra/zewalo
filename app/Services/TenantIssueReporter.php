<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Tenant;
use App\Models\TenantIssue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

/**
 * Reports tenant-side errors to the central database so superadmins
 * can see what went wrong for which tenant without digging through logs.
 *
 * Stays safe to call from inside tenant context or central context —
 * it always writes to the central connection via TenantIssue model.
 */
class TenantIssueReporter
{
    /**
     * Report an exception that occurred inside tenant context.
     *
     * Returns the short error reference (e.g. "ZWL-ERR-9F3C2A") that
     * should be shown to the end-user so they can cite it when asking
     * for support — it maps to TenantIssue.id.
     */
    public static function reportException(
        Throwable $e,
        string $code,
        string $title,
        string $area = 'tenant',
        string $severity = 'error',
        array $context = [],
    ): string {
        $issue = TenantIssue::create(array_merge(
            self::baseAttributes($code, $title, $area, $severity, $context),
            [
                'message' => $e->getMessage() !== '' ? $e->getMessage() : $e::class,
                'exception_class' => $e::class,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'stack_trace' => self::truncateTrace($e->getTraceAsString()),
            ]
        ));

        Log::error("[TenantIssue {$code}] {$title}: {$e->getMessage()}", [
            'tenant_id' => $issue->tenant_id,
            'issue_id' => $issue->id,
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ]);

        return self::reference($issue->id);
    }

    /**
     * Report a non-exception issue (validation, configuration, etc).
     */
    public static function report(
        string $code,
        string $title,
        string $message,
        string $area = 'tenant',
        string $severity = 'warning',
        array $context = [],
    ): string {
        $issue = TenantIssue::create(array_merge(
            self::baseAttributes($code, $title, $area, $severity, $context),
            ['message' => $message]
        ));

        return self::reference($issue->id);
    }

    protected static function baseAttributes(string $code, string $title, string $area, string $severity, array $context): array
    {
        $tenant = self::currentTenant();

        return [
            'tenant_id' => $tenant?->id,
            'tenant_name' => $tenant?->name,
            'code' => $code,
            'area' => $area,
            'severity' => $severity,
            'title' => Str::limit($title, 250, ''),
            'context' => $context ?: null,
            'url' => request()?->fullUrl(),
            'user_email' => Auth::user()?->email,
        ];
    }

    protected static function currentTenant(): ?Tenant
    {
        try {
            if (function_exists('tenant') && tenant()) {
                $t = tenant();

                return $t instanceof Tenant ? $t : Tenant::find($t->getTenantKey());
            }
        } catch (Throwable) {
            // no-op: fall through to null
        }

        return null;
    }

    protected static function truncateTrace(string $trace, int $max = 20000): string
    {
        return strlen($trace) > $max ? substr($trace, 0, $max) . "\n...[truncated]" : $trace;
    }

    public static function reference(int $id): string
    {
        return 'ZWL-ERR-' . str_pad((string) $id, 6, '0', STR_PAD_LEFT);
    }
}

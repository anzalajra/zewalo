<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectToSetupWizard
{
    protected array $allowedPaths = [
        'admin/setup-wizard',
        'livewire/update',
        'admin/subscription-billing',
        'subscription-expired',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $tenant = tenant();

        if (! $tenant || ! $tenant->needsSetup()) {
            return $next($request);
        }

        // Always allow whitelisted paths
        foreach ($this->allowedPaths as $path) {
            if ($request->is($path . '*') || $request->is($path)) {
                return $next($request);
            }
        }

        // Only auto-redirect once per session
        if (! session()->has('setup_wizard_redirected')) {
            session()->put('setup_wizard_redirected', true);

            return redirect()->to('/admin/setup-wizard');
        }

        return $next($request);
    }
}

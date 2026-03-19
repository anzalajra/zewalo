<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to redirect central domain requests to the central panel.
 *
 * For multi-tenancy setup:
 * - Central domains (localhost, zewalo.com) → Redirect to /central
 * - Tenant domains (tenant.localhost) → Allow normal access
 */
class RedirectCentralDomainToPanel
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $centralDomains = config('tenancy.central_domains', []);

        // Check if current host is a central domain
        $isCentralDomain = in_array($host, $centralDomains, true);

        if ($isCentralDomain) {
            $path = $request->path();

            // Allow access to public routes, registration, login, landing page, and assets
            if ($path === '/' ||
                str_starts_with($path, 'admin') ||
                str_starts_with($path, 'register-tenant') ||
                str_starts_with($path, 'login-tenant') ||
                str_starts_with($path, 'pricing') ||
                str_starts_with($path, 'feature') ||
                str_starts_with($path, 'contact') ||
                str_starts_with($path, 'careers') ||
                str_starts_with($path, 'about-us') ||
                str_starts_with($path, 'blog') ||
                str_starts_with($path, 'livewire') ||
                str_starts_with($path, '_debugbar') ||
                str_starts_with($path, 'storage') ||
                str_starts_with($path, 'build') ||
                str_starts_with($path, 'vendor') ||
                str_starts_with($path, 'css') ||
                str_starts_with($path, 'js') ||
                str_starts_with($path, 'fonts') ||
                str_starts_with($path, 'icons') ||
                str_starts_with($path, 'filament') ||
                str_starts_with($path, 'masuk') ||
                str_starts_with($path, 'api/payment') ||
                str_starts_with($path, 'impersonate') ||
                $path === 'up') {
                return $next($request);
            }

            // Redirect unknown routes on central domain to home
            return redirect('/');
        }

        return $next($request);
    }
}

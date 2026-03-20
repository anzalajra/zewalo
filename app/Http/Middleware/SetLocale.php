<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Services\GeoIpService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    protected array $supportedLocales = ['id', 'en'];

    public function __construct(
        protected GeoIpService $geoIpService,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $locale = $this->resolveLocale($request);

        app()->setLocale($locale);
        session(['locale' => $locale]);

        // Store currency/region in session for pricing
        $currency = $this->geoIpService->getCurrency($request);
        $region = $this->geoIpService->getRegion($request);
        session(['currency' => $currency, 'region' => $region]);

        $response = $next($request);

        return $response;
    }

    protected function resolveLocale(Request $request): string
    {
        // 1. Explicit query parameter (?lang=en)
        if ($request->has('lang') && in_array($request->query('lang'), $this->supportedLocales)) {
            $locale = $request->query('lang');
            cookie()->queue('zewalo_locale', $locale, 60 * 24 * 365);

            return $locale;
        }

        // 2. Session (persisted from previous request)
        if (session()->has('locale') && in_array(session('locale'), $this->supportedLocales)) {
            return session('locale');
        }

        // 3. Cookie (persisted across sessions)
        $cookieLocale = $request->cookie('zewalo_locale');
        if ($cookieLocale && in_array($cookieLocale, $this->supportedLocales)) {
            return $cookieLocale;
        }

        // 4. GeoIP detection
        $geoLocale = $this->geoIpService->getLocale($request);
        if (in_array($geoLocale, $this->supportedLocales)) {
            return $geoLocale;
        }

        // 5. Browser Accept-Language header
        $preferredLanguage = $request->getPreferredLanguage($this->supportedLocales);
        if ($preferredLanguage) {
            return $preferredLanguage;
        }

        // 6. Default
        return config('app.locale', 'id');
    }
}

<?php

declare(strict_types=1);

namespace App\Services;

use GeoIp2\Database\Reader;
use Illuminate\Http\Request;

class GeoIpService
{
    /**
     * Get the country code from the request.
     * Primary: Cloudflare CF-IPCountry header.
     * Fallback: MaxMind GeoLite2 local database (dev/staging).
     */
    public function getCountryCode(Request $request): ?string
    {
        // Primary: Cloudflare header
        $country = $request->header('CF-IPCountry');
        if ($country && $country !== 'XX' && $country !== 'T1') {
            return strtoupper($country);
        }

        // Fallback: MaxMind GeoLite2 for local/staging
        if (app()->environment('local', 'staging')) {
            return $this->lookupMaxMind($request->ip());
        }

        return null;
    }

    /**
     * Get the currency for the request based on GeoIP.
     * Indonesia -> IDR, everything else -> USD.
     */
    public function getCurrency(Request $request): string
    {
        $country = $this->getCountryCode($request);

        return ($country === 'ID') ? 'IDR' : 'USD';
    }

    /**
     * Get the region for the request.
     */
    public function getRegion(Request $request): string
    {
        $country = $this->getCountryCode($request);

        return ($country === 'ID') ? 'indonesia' : 'global';
    }

    /**
     * Get the default locale based on GeoIP.
     */
    public function getLocale(Request $request): string
    {
        $country = $this->getCountryCode($request);

        return ($country === 'ID') ? 'id' : 'en';
    }

    /**
     * Lookup country code using MaxMind GeoLite2 database.
     */
    protected function lookupMaxMind(?string $ip): ?string
    {
        if (!$ip || in_array($ip, ['127.0.0.1', '::1'])) {
            return config('app.geoip_default_country', 'ID');
        }

        $dbPath = storage_path('app/geoip/GeoLite2-Country.mmdb');
        if (!file_exists($dbPath)) {
            return null;
        }

        try {
            $reader = new Reader($dbPath);
            $record = $reader->country($ip);

            return $record->country->isoCode;
        } catch (\Exception) {
            return null;
        }
    }
}

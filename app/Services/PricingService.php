<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Number;

class PricingService
{
    public function __construct(
        protected GeoIpService $geoIpService,
    ) {}

    /**
     * Get regional pricing for a plan based on the request's geo location.
     *
     * @return array{currency: string, amount_monthly: float, amount_yearly: float, formatted_monthly: string, formatted_yearly: string, gateway_code: ?string}|null
     */
    public function getRegionalPricing(SubscriptionPlan $plan, Request $request): ?array
    {
        $currency = $this->geoIpService->getCurrency($request);
        $price = null;

        try {
            $price = $plan->priceFor($currency);

            // Fallback to IDR if requested currency not available
            if (! $price && $currency !== 'IDR') {
                $currency = 'IDR';
                $price = $plan->priceFor('IDR');
            }
        } catch (\Illuminate\Database\QueryException $e) {
            // plan_prices table may not exist yet
        }

        // Fallback to plan's default pricing
        if (! $price) {
            $fallbackCurrency = $plan->currency ?? 'IDR';

            return [
                'currency' => $fallbackCurrency,
                'amount_monthly' => (float) $plan->price_monthly,
                'amount_yearly' => (float) $plan->price_yearly,
                'formatted_monthly' => $this->formatCurrency((float) $plan->price_monthly, $fallbackCurrency),
                'formatted_yearly' => $this->formatCurrency((float) $plan->price_yearly, $fallbackCurrency),
                'gateway_code' => null,
            ];
        }

        return [
            'currency' => $price->currency,
            'amount_monthly' => (float) $price->amount_monthly,
            'amount_yearly' => (float) $price->amount_yearly,
            'formatted_monthly' => $this->formatCurrency((float) $price->amount_monthly, $price->currency),
            'formatted_yearly' => $this->formatCurrency((float) $price->amount_yearly, $price->currency),
            'gateway_code' => $price->payment_gateway_code,
        ];
    }

    /**
     * Get all plans with their regional pricing.
     *
     * @return Collection<int, array{plan: SubscriptionPlan, pricing: array}>
     */
    public function getAllPlansWithPricing(Request $request): Collection
    {
        try {
            $plans = SubscriptionPlan::active()
                ->orderBy('sort_order')
                ->with('prices')
                ->get();
        } catch (\Illuminate\Database\QueryException $e) {
            // plan_prices table may not exist yet — load plans without prices
            $plans = SubscriptionPlan::active()
                ->orderBy('sort_order')
                ->get();
        }

        return $plans->map(fn (SubscriptionPlan $plan) => [
            'plan' => $plan,
            'pricing' => $this->getRegionalPricing($plan, $request),
        ]);
    }

    /**
     * Format a currency amount for display.
     */
    public function formatCurrency(float $amount, string $currency): string
    {
        if ($amount == 0) {
            return match ($currency) {
                'USD' => '$0',
                default => 'Rp 0',
            };
        }

        return match ($currency) {
            'USD' => '$'.Number::format($amount, 2),
            'IDR' => 'Rp '.Number::format($amount, 0, locale: 'id'),
            default => $currency.' '.Number::format($amount, 2),
        };
    }

    /**
     * Convert amount to smallest currency unit for payment processing.
     * USD: dollars → cents (19.00 → 1900)
     * IDR: already whole numbers (99000 → 99000)
     */
    public function toSmallestUnit(float $amount, string $currency): int
    {
        return match ($currency) {
            'USD' => (int) round($amount * 100),
            default => (int) round($amount),
        };
    }
}

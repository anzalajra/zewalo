<?php

declare(strict_types=1);

namespace App\Services\Payment;

use App\Enums\SaasInvoiceStatus;
use App\Models\PaymentMethod;
use App\Models\SaasInvoice;
use App\Models\SubscriptionPlan;
use App\Models\Tenant;
use App\Models\TenantSubscription;
use App\Services\PricingService;
use Illuminate\Support\Collection;
use RuntimeException;

class SubscriptionCheckoutService
{
    public function __construct(
        protected PricingService $pricingService,
        protected PaymentService $paymentService,
    ) {}

    /**
     * Get all active plans with pricing resolved for the tenant's locked region.
     *
     * @return Collection<int, array{plan: SubscriptionPlan, pricing: ?array}>
     */
    public function getPlansForTenant(Tenant $tenant): Collection
    {
        $plans = SubscriptionPlan::active()
            ->orderBy('sort_order')
            ->with('prices')
            ->get();

        return $plans->map(fn (SubscriptionPlan $plan) => [
            'plan' => $plan,
            'pricing' => $this->pricingService->getPricingForTenantRegion($plan, $tenant->region ?? 'intl'),
        ]);
    }

    /**
     * Get payment methods available for a tenant's region.
     * Region 'id' → Duitku + LemonSqueezy methods.
     * Region 'intl' → LemonSqueezy methods ONLY.
     */
    public function getPaymentMethodsForTenant(Tenant $tenant): Collection
    {
        $allowedGateways = $tenant->getAvailableGatewayCodes();

        return PaymentMethod::active()
            ->whereHas('paymentGateway', fn ($q) => $q->where('is_active', true)
                ->whereIn('code', $allowedGateways)
            )
            ->with('paymentGateway')
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * Create a SaasInvoice + TenantSubscription for a checkout.
     * Price is resolved from backend based on locked region — never from frontend.
     */
    public function initiateCheckout(Tenant $tenant, SubscriptionPlan $plan, string $billingCycle): SaasInvoice
    {
        $region = $tenant->region ?? 'intl';
        $pricing = $this->pricingService->getPricingForTenantRegion($plan, $region);

        if (! $pricing) {
            throw new RuntimeException('No pricing available for this plan in your region.');
        }

        $amount = $billingCycle === 'yearly'
            ? $pricing['amount_yearly']
            : $pricing['amount_monthly'];

        if ($amount <= 0) {
            throw new RuntimeException('This plan is free and does not require payment.');
        }

        // Create TenantSubscription (pending until payment confirmed)
        $subscription = TenantSubscription::create([
            'tenant_id' => $tenant->id,
            'subscription_plan_id' => $plan->id,
            'previous_plan_id' => $tenant->subscription_plan_id,
            'status' => 'pending',
            'price' => $amount,
            'currency' => $pricing['currency'],
            'billing_cycle' => $billingCycle,
            'started_at' => now(),
            'ends_at' => $billingCycle === 'yearly' ? now()->addYear() : now()->addMonth(),
        ]);

        // Create SaasInvoice
        $invoice = SaasInvoice::create([
            'tenant_id' => $tenant->id,
            'tenant_subscription_id' => $subscription->id,
            'amount' => $amount,
            'tax' => 0,
            'total' => $amount,
            'currency' => $pricing['currency'],
            'status' => SaasInvoiceStatus::Pending,
            'issued_at' => now(),
            'due_at' => now()->addDay(),
        ]);

        return $invoice;
    }
}

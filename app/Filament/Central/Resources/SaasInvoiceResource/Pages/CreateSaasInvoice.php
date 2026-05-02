<?php

namespace App\Filament\Central\Resources\SaasInvoiceResource\Pages;

use App\Filament\Central\Resources\SaasInvoiceResource;
use App\Models\SubscriptionPlan;
use App\Models\TenantSubscription;
use Filament\Resources\Pages\CreateRecord;

class CreateSaasInvoice extends CreateRecord
{
    protected static string $resource = SaasInvoiceResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Form fields `subscription_plan_id` and `billing_cycle` are dehydrated=false,
        // so pull them from the raw form state instead.
        $state = $this->form->getRawState();
        $planId = $state['subscription_plan_id'] ?? null;
        $billingCycle = $state['billing_cycle'] ?? 'monthly';

        // If no existing subscription was selected but a plan was, auto-create one.
        if (empty($data['tenant_subscription_id']) && $planId && ! empty($data['tenant_id'])) {
            $plan = SubscriptionPlan::find($planId);

            if ($plan) {
                $startedAt = $data['issued_at'] ?? now();
                $endsAt = $billingCycle === 'yearly'
                    ? \Carbon\Carbon::parse($startedAt)->addYear()
                    : \Carbon\Carbon::parse($startedAt)->addMonth();

                $sub = TenantSubscription::create([
                    'tenant_id' => $data['tenant_id'],
                    'subscription_plan_id' => $plan->id,
                    'started_at' => $startedAt,
                    'ends_at' => $endsAt,
                    'status' => 'pending',
                    'price' => $billingCycle === 'yearly'
                        ? (float) ($plan->price_yearly ?? 0)
                        : (float) ($plan->price_monthly ?? 0),
                    'currency' => $data['currency'] ?? 'IDR',
                    'billing_cycle' => $billingCycle,
                    'notes' => 'Auto-created from manual SaaS invoice',
                ]);

                $data['tenant_subscription_id'] = $sub->id;
            }
        }

        return $data;
    }
}

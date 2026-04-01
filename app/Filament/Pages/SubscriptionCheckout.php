<?php

namespace App\Filament\Pages;

use App\Models\PaymentMethod;
use App\Models\SaasInvoice;
use App\Models\SubscriptionPlan;
use App\Services\Payment\PaymentService;
use App\Services\Payment\SubscriptionCheckoutService;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Livewire\Attributes\Computed;

class SubscriptionCheckout extends Page
{
    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $title = 'Upgrade Plan';

    protected string $view = 'filament.pages.subscription-checkout';

    // Step: 1 = select plan, 2 = select payment, 3 = payment instructions
    public int $step = 1;

    public ?int $selectedPlanId = null;

    public string $billingCycle = 'monthly';

    public ?int $selectedPaymentMethodId = null;

    public ?int $invoiceId = null;

    public ?array $paymentInstructions = null;

    public array $plans = [];

    public ?string $tenantRegion = null;

    public function mount(): void
    {
        $tenant = tenant();

        if (! $tenant) {
            return;
        }

        $this->tenantRegion = $tenant->region ?? 'intl';

        $checkoutService = app(SubscriptionCheckoutService::class);
        $plansWithPricing = $checkoutService->getPlansForTenant($tenant);

        $this->plans = $plansWithPricing
            ->filter(fn ($item) => $item['pricing'] !== null)
            ->map(fn ($item) => [
                'id' => $item['plan']->id,
                'name' => $item['plan']->name,
                'slug' => $item['plan']->slug,
                'description' => $item['plan']->description,
                'features' => $item['plan']->features ?? [],
                'is_featured' => $item['plan']->is_featured,
                'pricing' => $item['pricing'],
                'max_users' => $item['plan']->max_users,
                'max_products' => $item['plan']->max_products,
                'max_rental_transactions_per_month' => $item['plan']->max_rental_transactions_per_month,
            ])
            ->values()
            ->toArray();

        // Pre-select current plan if upgrading
        $currentPlanId = $tenant->subscription_plan_id;
        if ($currentPlanId) {
            $this->selectedPlanId = $currentPlanId;
        }
    }

    #[Computed]
    public function paymentMethods(): \Illuminate\Support\Collection
    {
        $tenant = tenant();

        if (! $tenant) {
            return collect();
        }

        return app(SubscriptionCheckoutService::class)->getPaymentMethodsForTenant($tenant);
    }

    public function selectPlan(int $planId): void
    {
        $this->selectedPlanId = $planId;
    }

    public function goToPayment(): void
    {
        if (! $this->selectedPlanId) {
            Notification::make()
                ->title('Pilih paket terlebih dahulu.')
                ->warning()
                ->send();

            return;
        }

        // Check if selecting current plan
        $tenant = tenant();
        if ($tenant && $tenant->subscription_plan_id === $this->selectedPlanId && $tenant->subscriptionActive()) {
            Notification::make()
                ->title('Anda sudah berlangganan paket ini.')
                ->info()
                ->send();

            return;
        }

        $this->step = 2;
    }

    public function backToPlans(): void
    {
        $this->step = 1;
        $this->selectedPaymentMethodId = null;
        $this->paymentInstructions = null;
        $this->invoiceId = null;
    }

    public function initiatePayment(): void
    {
        if (! $this->selectedPlanId || ! $this->selectedPaymentMethodId) {
            Notification::make()
                ->title('Pilih metode pembayaran terlebih dahulu.')
                ->warning()
                ->send();

            return;
        }

        $tenant = tenant();
        $plan = SubscriptionPlan::find($this->selectedPlanId);
        $method = PaymentMethod::find($this->selectedPaymentMethodId);

        if (! $tenant || ! $plan || ! $method) {
            Notification::make()
                ->title('Data tidak valid.')
                ->danger()
                ->send();

            return;
        }

        try {
            $checkoutService = app(SubscriptionCheckoutService::class);
            $invoice = $checkoutService->initiateCheckout($tenant, $plan, $this->billingCycle);
            $this->invoiceId = $invoice->id;

            $result = app(PaymentService::class)->createPayment($invoice, $method);
            $this->paymentInstructions = $result;
            $this->step = 3;

            Notification::make()
                ->title('Pembayaran berhasil dibuat. Ikuti instruksi di bawah.')
                ->success()
                ->send();
        } catch (\Throwable $e) {
            Notification::make()
                ->title('Gagal membuat pembayaran: '.$e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function checkPaymentStatus(): void
    {
        if (! $this->invoiceId) {
            return;
        }

        $invoice = SaasInvoice::find($this->invoiceId);
        if (! $invoice) {
            return;
        }

        $invoice->refresh();

        if ($invoice->isPaid()) {
            Notification::make()
                ->title('Pembayaran berhasil! Subscription Anda telah diaktifkan.')
                ->success()
                ->send();

            $this->redirect(SubscriptionBilling::getUrl());

            return;
        }

        // Try polling gateway status
        if ($invoice->gateway_reference_id && $invoice->payment_gateway_id) {
            try {
                $status = app(PaymentService::class)->checkPaymentStatus($invoice);

                if (($status['statusCode'] ?? '') === '00') {
                    $this->redirect(SubscriptionBilling::getUrl());

                    return;
                }
            } catch (\Throwable $e) {
                // Silent fail on status check
            }
        }
    }
}

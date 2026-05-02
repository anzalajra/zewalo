<?php

declare(strict_types=1);

namespace App\Filament\Concerns;

use App\Models\PaymentMethod;
use App\Models\SaasInvoice;
use App\Services\Payment\PaymentService;
use App\Services\Payment\SubscriptionCheckoutService;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;

/**
 * Shared payment-modal flow used by SubscriptionBilling list page and
 * SubscriptionInvoiceDetail page. Host must call $this->loadInvoices() (optional)
 * after a successful payment if it caches invoices.
 */
trait InteractsWithSubscriptionPayment
{
    public ?int $selectedInvoiceId = null;

    public ?int $selectedPaymentMethodId = null;

    public ?array $paymentInstructions = null;

    public bool $showPaymentModal = false;

    #[Computed]
    public function paymentMethods(): Collection
    {
        $tenant = tenant();

        if ($tenant) {
            return app(SubscriptionCheckoutService::class)->getPaymentMethodsForTenant($tenant);
        }

        return PaymentMethod::active()
            ->whereHas('paymentGateway', fn ($q) => $q->where('is_active', true))
            ->with('paymentGateway')
            ->orderBy('sort_order')
            ->get();
    }

    public function selectInvoice(int $invoiceId): void
    {
        $this->selectedInvoiceId = $invoiceId;
        $this->selectedPaymentMethodId = null;
        $this->paymentInstructions = null;
        $this->showPaymentModal = true;
    }

    public function closePaymentModal(): void
    {
        $this->showPaymentModal = false;
        $this->selectedInvoiceId = null;
        $this->selectedPaymentMethodId = null;
        $this->paymentInstructions = null;
    }

    public function initiatePayment(): void
    {
        if (! $this->selectedInvoiceId || ! $this->selectedPaymentMethodId) {
            Notification::make()
                ->title('Pilih metode pembayaran terlebih dahulu.')
                ->warning()
                ->send();

            return;
        }

        $invoice = SaasInvoice::find($this->selectedInvoiceId);
        $method = PaymentMethod::find($this->selectedPaymentMethodId);

        if (! $invoice || ! $method) {
            Notification::make()
                ->title('Invoice atau metode pembayaran tidak ditemukan.')
                ->danger()
                ->send();

            return;
        }

        // Defence-in-depth: ensure invoice belongs to current tenant
        $tenant = tenant();
        if ($tenant && (string) $invoice->tenant_id !== (string) $tenant->id) {
            Notification::make()
                ->title('Tidak diizinkan.')
                ->danger()
                ->send();

            return;
        }

        if ($invoice->isPaid()) {
            Notification::make()
                ->title('Invoice ini sudah dibayar.')
                ->info()
                ->send();
            $this->closePaymentModal();
            $this->refreshAfterPayment();

            return;
        }

        try {
            $result = app(PaymentService::class)->createPayment($invoice, $method);
            $this->paymentInstructions = $result;
            $this->refreshAfterPayment();

            Notification::make()
                ->title('Pembayaran berhasil dibuat. Silakan ikuti instruksi di bawah.')
                ->success()
                ->send();
        } catch (\Throwable $e) {
            Log::error('Subscription payment failed', [
                'invoice' => $this->selectedInvoiceId,
                'error' => $e->getMessage(),
            ]);

            Notification::make()
                ->title('Gagal membuat pembayaran')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function checkPaymentStatus(): void
    {
        if (! $this->selectedInvoiceId) {
            return;
        }

        $invoice = SaasInvoice::find($this->selectedInvoiceId);
        if (! $invoice) {
            return;
        }

        $invoice->refresh();

        if ($invoice->isPaid()) {
            Notification::make()
                ->title('Pembayaran berhasil! Subscription Anda telah diaktifkan.')
                ->success()
                ->send();

            $this->closePaymentModal();
            $this->refreshAfterPayment();

            return;
        }

        if ($invoice->gateway_reference_id && $invoice->payment_gateway_id) {
            try {
                $status = app(PaymentService::class)->checkPaymentStatus($invoice);

                if (($status['statusCode'] ?? '') === '00') {
                    $this->closePaymentModal();
                    $this->refreshAfterPayment();

                    return;
                }
            } catch (\Throwable) {
                // silent
            }
        }
    }

    /**
     * Hook for host page to refresh local state (invoice list, plan info, etc.)
     * after successful payment or status change. Override as needed.
     */
    protected function refreshAfterPayment(): void
    {
        if (method_exists($this, 'loadInvoices')) {
            $this->loadInvoices();
        }
    }
}

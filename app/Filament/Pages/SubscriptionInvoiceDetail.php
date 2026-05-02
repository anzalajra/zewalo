<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\InteractsWithSubscriptionPayment;
use App\Models\SaasInvoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Filament\Panel;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SubscriptionInvoiceDetail extends Page
{
    use InteractsWithSubscriptionPayment;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $title = 'Invoice Detail';

    protected static ?string $slug = 'subscription-billing/invoices';

    protected string $view = 'filament.pages.subscription-invoice-detail';

    public SaasInvoice $invoice;

    public static function getRoutePath(Panel $panel): string
    {
        return '/'.static::getSlug($panel).'/{record}';
    }

    public function mount(int|string $record): void
    {
        $tenant = tenant();

        $invoice = SaasInvoice::with(['tenantSubscription.subscriptionPlan', 'paymentMethod', 'paymentGateway', 'tenant'])
            ->find($record);

        if (! $invoice || ! $tenant || (string) $invoice->tenant_id !== (string) $tenant->id) {
            throw new NotFoundHttpException('Invoice tidak ditemukan.');
        }

        $this->invoice = $invoice;
    }

    public function getTitle(): string
    {
        return 'Invoice '.$this->invoice->invoice_number;
    }

    protected function getHeaderActions(): array
    {
        $actions = [
            Action::make('download')
                ->label('Download Invoice')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action(fn () => $this->downloadPdf()),
        ];

        if (! $this->invoice->isPaid()) {
            $actions[] = Action::make('pay')
                ->label('Bayar Sekarang')
                ->icon('heroicon-o-credit-card')
                ->color('primary')
                ->action(fn () => $this->selectInvoice($this->invoice->id));
        }

        $actions[] = Action::make('back')
            ->label('Kembali')
            ->icon('heroicon-o-arrow-left')
            ->color('gray')
            ->outlined()
            ->url(SubscriptionBilling::getUrl());

        return $actions;
    }

    public function downloadPdf(): StreamedResponse
    {
        $invoice = $this->invoice->loadMissing(['tenantSubscription.subscriptionPlan', 'paymentMethod', 'tenant']);

        $pdf = Pdf::loadView('pdf.saas-invoice', [
            'invoice' => $invoice,
            'logoUrl' => $this->resolveCentralLogoDataUri(),
            'siteName' => \App\Services\CentralBrandingService::siteName(),
        ])->setPaper('a4');

        $filename = 'Invoice-'.$invoice->invoice_number.'.pdf';

        return response()->streamDownload(
            fn () => print ($pdf->output()),
            $filename,
            ['Content-Type' => 'application/pdf']
        );
    }

    /**
     * Fetch the central branding logo and return it as a base64 data URI for dompdf.
     * Returns null when no logo is configured or fetch fails.
     */
    protected function resolveCentralLogoDataUri(): ?string
    {
        if (! \App\Services\CentralBrandingService::hasLogo()) {
            return null;
        }

        $path = \App\Models\CentralSetting::get('branding_logo');
        if (! $path) {
            return null;
        }

        try {
            \App\Providers\CentralSettingsServiceProvider::ensureR2Config();
            $disk = \Illuminate\Support\Facades\Storage::disk('r2');
            if (! $disk->exists($path)) {
                return null;
            }

            $contents = $disk->get($path);
            $mime = $disk->mimeType($path) ?: 'image/png';

            return 'data:'.$mime.';base64,'.base64_encode($contents);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Failed to embed central logo into SaaS invoice PDF', [
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    protected function refreshAfterPayment(): void
    {
        $this->invoice->refresh();
        $this->invoice->loadMissing(['tenantSubscription.subscriptionPlan', 'paymentMethod', 'paymentGateway']);
    }
}

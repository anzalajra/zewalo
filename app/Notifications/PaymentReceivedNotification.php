<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\SaasInvoice;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentReceivedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public SaasInvoice $invoice,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $tenant = $this->invoice->tenant;

        return (new MailMessage)
            ->subject('Pembayaran Diterima - '.$this->invoice->invoice_number)
            ->markdown('emails.central.payment-received', [
                'tenantName'    => $tenant->name ?? 'Tenant',
                'invoiceNumber' => $this->invoice->invoice_number,
                'total'         => $this->invoice->total,
                'paidAt'        => $this->invoice->paid_at?->format('d M Y H:i') ?? '-',
                'detailUrl'     => url('/admin/subscription-billing'),
            ]);
    }
}

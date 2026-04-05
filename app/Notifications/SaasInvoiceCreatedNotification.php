<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\SaasInvoice;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SaasInvoiceCreatedNotification extends Notification
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
            ->subject('Invoice Langganan Baru - '.$this->invoice->invoice_number)
            ->markdown('emails.central.saas-invoice-created', [
                'tenantName'    => $tenant->name ?? 'Tenant',
                'invoiceNumber' => $this->invoice->invoice_number,
                'period'        => $this->invoice->created_at->format('M Y'),
                'total'         => $this->invoice->total,
                'dueAt'         => $this->invoice->due_at->format('d M Y'),
                'paymentUrl'    => url('/admin/subscription-billing'),
            ]);
    }
}

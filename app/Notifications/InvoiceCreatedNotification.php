<?php

namespace App\Notifications;

use App\Models\Invoice;
use App\Models\Setting;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoiceCreatedNotification extends Notification
{
    use Queueable;

    public Invoice $invoice;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function via(object $notifiable): array
    {
        $channels = [];

        if (Setting::get('notification_app_enabled', true)) {
            $channels[] = 'database';
        }

        if ((tenant()?->hasFeature(\App\Enums\TenantFeature::EmailNotification) ?? true) && Setting::get('notification_email_enabled', true) && Setting::get('notify_new_invoice', true)) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        $customerName = $this->invoice->user?->name ?? $this->invoice->customer?->name ?? 'Unknown';
        $rental = $this->invoice->rental;

        return (new MailMessage)
            ->subject('Invoice Baru - '.$this->invoice->number)
            ->markdown('emails.tenant.invoice-created', [
                'invoiceNumber' => $this->invoice->number,
                'rentalCode'    => $rental?->rental_code ?? '-',
                'customerName'  => $customerName,
                'total'         => $this->invoice->total,
                'dueDate'       => $this->invoice->due_date?->format('d M Y') ?? '-',
                'invoiceUrl'    => url("/admin/invoices/{$this->invoice->id}"),
            ]);
    }

    public function toDatabase(object $notifiable): array
    {
        $customerName = $this->invoice->user?->name ?? 'Unknown';

        return FilamentNotification::make()
            ->title('New Invoice')
            ->body("Invoice {$this->invoice->number} created for {$customerName}")
            ->icon('heroicon-o-document-text')
            ->color('success')
            ->actions([
                \Filament\Actions\Action::make('view')
                    ->button()
                    ->url("/admin/invoices/{$this->invoice->id}")
                    ->markAsRead(),
            ])
            ->getDatabaseMessage();
    }
}

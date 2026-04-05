<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionSuspendedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Tenant $tenant,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Akun Anda Telah Disuspend - '.config('app.name'))
            ->markdown('emails.central.subscription-suspended', [
                'tenantName' => $this->tenant->name ?? 'Tenant',
                'paymentUrl' => url('/admin/subscription-billing'),
            ]);
    }
}

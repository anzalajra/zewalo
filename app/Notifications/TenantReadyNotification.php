<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TenantReadyNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly string $storeName,
        public readonly string $domain,
        public readonly string $adminEmail,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $scheme = parse_url(config('app.url', 'http://localhost'), PHP_URL_SCHEME) ?: 'http';
        $loginUrl = $scheme.'://'.$this->domain.'/admin/login';

        return (new MailMessage)
            ->subject("Toko Anda Siap! — {$this->storeName}")
            ->markdown('emails.central.tenant-ready', [
                'storeName'  => $this->storeName,
                'domain'     => $this->domain,
                'adminEmail' => $this->adminEmail,
                'loginUrl'   => $loginUrl,
            ]);
    }
}

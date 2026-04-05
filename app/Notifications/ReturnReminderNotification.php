<?php

namespace App\Notifications;

use App\Models\Rental;
use App\Models\Setting;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReturnReminderNotification extends Notification
{
    use Queueable;

    public $rental;

    public function __construct(Rental $rental)
    {
        $this->rental = $rental;
    }

    public function via(object $notifiable): array
    {
        $channels = [];
        if (Setting::get('notification_app_enabled', true)) {
            $channels[] = 'database';
        }
        if ((tenant()?->hasFeature(\App\Enums\TenantFeature::EmailNotification) ?? true) && Setting::get('notification_email_enabled', true)) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pengingat Pengembalian - '.$this->rental->rental_code)
            ->markdown('emails.tenant.return-reminder', [
                'customerName' => $notifiable->name,
                'rentalCode'   => $this->rental->rental_code,
                'returnDate'   => $this->rental->end_date->format('d M Y'),
                'location'     => Setting::get('address', config('app.name')),
                'rentalUrl'    => url('/rentals/'.$this->rental->id),
                'storeName'    => Setting::get('site_name', config('app.name')),
            ]);
    }

    public function toDatabase(object $notifiable): array
    {
        return FilamentNotification::make()
            ->title('Return Reminder')
            ->body("Reminder: Return for booking {$this->rental->rental_code} is due tomorrow.")
            ->icon('heroicon-o-arrow-path')
            ->actions([
                \Filament\Actions\Action::make('view')
                    ->button()
                    ->url("/rentals/{$this->rental->id}")
                    ->markAsRead(),
            ])
            ->getDatabaseMessage();
    }
}

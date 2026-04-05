<?php

namespace App\Notifications;

use App\Models\Rental;
use App\Models\Setting;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingConfirmedNotification extends Notification
{
    use Queueable;

    public $rental;

    /**
     * Create a new notification instance.
     */
    public function __construct(Rental $rental)
    {
        $this->rental = $rental;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
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

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pemesanan Dikonfirmasi - '.$this->rental->rental_code)
            ->markdown('emails.tenant.booking-confirmed', [
                'customerName' => $notifiable->name,
                'rentalCode'   => $this->rental->rental_code,
                'startDate'    => $this->rental->start_date->format('d M Y'),
                'endDate'      => $this->rental->end_date->format('d M Y'),
                'total'        => $this->rental->total,
                'rentalUrl'    => url('/rentals/'.$this->rental->id),
                'storeName'    => Setting::get('site_name', config('app.name')),
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return FilamentNotification::make()
            ->title('Booking Confirmed')
            ->body("Your booking {$this->rental->rental_code} has been confirmed.")
            ->icon('heroicon-o-check-circle')
            ->actions([
                \Filament\Actions\Action::make('view')
                    ->button()
                    ->url("/rentals/{$this->rental->id}")
                    ->markAsRead(),
            ])
            ->getDatabaseMessage();
    }
}

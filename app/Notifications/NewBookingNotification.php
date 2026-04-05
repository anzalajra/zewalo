<?php

namespace App\Notifications;

use App\Models\Rental;
use App\Models\Setting;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewBookingNotification extends Notification
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

        if ((tenant()?->hasFeature(\App\Enums\TenantFeature::EmailNotification) ?? true) && Setting::get('notification_email_enabled', true) && Setting::get('notify_new_rental', true)) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $customerName = $this->rental->user?->name ?? $this->rental->customer?->name ?? 'Unknown';

        return (new MailMessage)
            ->subject('Pemesanan Baru - '.$this->rental->rental_code)
            ->markdown('emails.tenant.new-booking', [
                'rentalCode'   => $this->rental->rental_code,
                'customerName' => $customerName,
                'startDate'    => $this->rental->start_date?->format('d M Y'),
                'endDate'      => $this->rental->end_date?->format('d M Y'),
                'total'        => $this->rental->total,
                'rentalUrl'    => url("/admin/rentals/{$this->rental->id}"),
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        $customerName = $this->rental->user?->name ?? 'Unknown';

        return FilamentNotification::make()
            ->title('New Booking')
            ->body("New booking {$this->rental->rental_code} from {$customerName}")
            ->icon('heroicon-o-shopping-bag')
            ->actions([
                \Filament\Actions\Action::make('view')
                    ->button()
                    ->url("/admin/rentals/{$this->rental->id}")
                    ->markAsRead(),
            ])
            ->getDatabaseMessage();
    }
}

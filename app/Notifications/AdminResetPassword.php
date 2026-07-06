<?php

namespace App\Notifications;

use App\Models\EmailLog;
use Filament\Auth\Notifications\ResetPassword as FilamentResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Branded password-reset email for the tenant Admin panel.
 *
 * Filament's RequestPasswordReset page resolves the base
 * `Filament\Auth\Notifications\ResetPassword` from the container and assigns
 * the signed reset URL to `$this->url` before sending. We bind this subclass
 * to that FQCN in AppServiceProvider so the admin flow keeps Filament's token
 * handling but sends the same branded email used elsewhere in the app.
 *
 * Mailer config (SMTP/SES) is taken from the central admin settings applied at
 * boot — same pipeline as every other tenant notification. Queued to mirror
 * CustomerResetPassword; QueueTenancyBootstrapper restores tenant context.
 */
class AdminResetPassword extends FilamentResetPassword
{
    public function toMail(object $notifiable): MailMessage
    {
        $broker = config('auth.defaults.passwords', 'users');
        $expiryMinutes = (int) config("auth.passwords.{$broker}.expire", 60);

        $subject = 'Reset Password Admin - '.config('app.name');

        try {
            $mail = (new MailMessage)
                ->subject($subject)
                ->markdown('emails.tenant.admin-reset-password', [
                    'adminName' => $notifiable->name ?? '',
                    'resetUrl' => $this->url,
                    'storeName' => config('app.name'),
                    'expiryMinutes' => $expiryMinutes,
                ]);

            EmailLog::logSent(
                to: $notifiable->email,
                subject: $subject,
                mailableClass: self::class,
                userId: $notifiable->id ?? null,
            );

            return $mail;
        } catch (\Throwable $e) {
            EmailLog::logFailed(
                to: $notifiable->email,
                subject: $subject,
                mailableClass: self::class,
                errorMessage: $e->getMessage(),
                userId: $notifiable->id ?? null,
            );
            throw $e;
        }
    }
}

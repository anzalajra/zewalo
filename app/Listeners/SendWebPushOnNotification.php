<?php

namespace App\Listeners;

use App\Models\Setting;
use App\Services\WebPushService;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Support\Facades\Log;

/**
 * Mirrors every database-channel notification to a web push (passive) so admins
 * with the installed PWA get a device notification without changing any existing
 * Notification class — as long as its via() returns 'database', push follows.
 * Master switch + per-class blocklist are tenant Settings.
 */
class SendWebPushOnNotification
{
    public function __construct(protected WebPushService $push) {}

    public function handle(NotificationSent $event): void
    {
        // Only mirror the database channel — that is what populates the bell.
        if ($event->channel !== 'database') {
            return;
        }

        if (! Setting::get('pwa_admin_push_enabled', true)) {
            return;
        }

        $notifiable = $event->notifiable;
        if (! method_exists($notifiable, 'getKey')) {
            return;
        }
        $userId = $notifiable->getKey();
        if (! $userId) {
            return;
        }

        // Per-event opt-out: suppress the push if the class is in the blocklist.
        $class = get_class($event->notification);
        $blockedKey = 'pwa_admin_push_block_' . $this->classKey($class);
        if (Setting::get($blockedKey, false)) {
            return;
        }

        $payload = $this->buildPayload($event->notification, $notifiable);
        if (! $payload) {
            return;
        }

        try {
            $this->push->sendToUser((int) $userId, $payload);
        } catch (\Throwable $e) {
            Log::warning('Web push dispatch failed: ' . $e->getMessage());
        }
    }

    protected function buildPayload($notification, $notifiable): ?array
    {
        $title = (string) (Setting::get('pwa_admin_name') ?: (Setting::get('site_name') ?: 'Admin'));
        $body = '';
        $url = '/admin';

        if (method_exists($notification, 'toDatabase')) {
            $data = $notification->toDatabase($notifiable);
            if (is_array($data)) {
                $title = $data['title'] ?? $title;
                $body = $data['body'] ?? $data['message'] ?? $body;
                if (! empty($data['actions']) && is_array($data['actions'])) {
                    foreach ($data['actions'] as $action) {
                        if (! empty($action['url'])) {
                            $url = $action['url'];
                            break;
                        }
                    }
                }
            }
        } elseif (method_exists($notification, 'toArray')) {
            $data = $notification->toArray($notifiable);
            $body = is_array($data) ? ($data['message'] ?? '') : '';
        }

        return [
            'title' => $title,
            'body' => is_string($body) ? strip_tags($body) : '',
            'url' => $url,
            'tag' => class_basename($notification),
        ];
    }

    protected function classKey(string $class): string
    {
        return strtolower(str_replace('\\', '_', $class));
    }
}

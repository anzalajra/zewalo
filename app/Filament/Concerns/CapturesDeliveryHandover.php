<?php

namespace App\Filament\Concerns;

use App\Models\DeliveryItem;
use App\Services\Storage\TenantStorageService;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;

/**
 * Shared delivery-handover capture for the Pickup (OUT) and Return (IN) operation pages.
 *
 * Captures the recipient name and a drawn signature (base64 PNG data URL from an Alpine
 * canvas pad) onto the operation's Delivery, and optionally attaches handover photos to
 * the delivery items. Signature is stored inline in deliveries.recipient_signature
 * (longText); photos go to R2 under the tenant prefix and their paths into
 * delivery_items.photos (json). Both the page host must expose `$this->delivery`.
 */
trait CapturesDeliveryHandover
{
    public ?string $handoverRecipient = null;

    /** Base64 PNG data URL captured from the signature canvas. */
    public ?string $handoverSignature = null;

    /** Livewire-bound temporary upload for a handover photo. */
    public $handoverPhoto = null;

    public bool $showHandover = false;

    public function openHandover(): void
    {
        $this->handoverRecipient = $this->delivery?->recipient_name
            ?? $this->rental?->customer?->name;
        $this->handoverSignature = $this->delivery?->recipient_signature;
        $this->showHandover = true;
    }

    public function closeHandover(): void
    {
        $this->showHandover = false;
    }

    public function saveHandover(): void
    {
        if (! $this->delivery) {
            return;
        }

        $signature = $this->handoverSignature;
        // Guard: only accept a genuine data URL (an empty canvas still exports a tiny PNG).
        if ($signature && ! Str::startsWith($signature, 'data:image')) {
            $signature = null;
        }

        $this->delivery->update([
            'recipient_name' => $this->handoverRecipient ?: null,
            'recipient_signature' => $signature,
            'signed_at' => ($signature || $this->handoverRecipient) ? now() : null,
        ]);

        $this->showHandover = false;

        Notification::make()
            ->title('Tanda terima disimpan')
            ->success()
            ->send();
    }

    /**
     * Store the currently-bound handover photo on R2 (tenant prefix) and append its path
     * to a specific delivery item's photos array. Called from the handover panel per item.
     */
    public function addItemPhoto(int $itemId): void
    {
        $item = DeliveryItem::find($itemId);
        if (! $item || ! $this->handoverPhoto) {
            return;
        }

        try {
            $service = app(TenantStorageService::class);
            $path = $service->store($this->handoverPhoto, 'delivery-photos');

            $photos = is_array($item->photos) ? $item->photos : [];
            $photos[] = $path;
            $item->update(['photos' => $photos]);

            $this->reset('handoverPhoto');
            $this->delivery?->refresh();

            Notification::make()->title('Foto ditambahkan')->success()->send();
        } catch (\Throwable $e) {
            Notification::make()->title('Gagal menyimpan foto')->body($e->getMessage())->danger()->send();
        }
    }
}

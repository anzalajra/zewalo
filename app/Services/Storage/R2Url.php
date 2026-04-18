<?php

declare(strict_types=1);

namespace App\Services\Storage;

use App\Providers\CentralSettingsServiceProvider;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Generate display URLs for private R2 objects.
 *
 * R2 bucket is private — direct Storage::disk('r2')->url($path) returns 404.
 * Use signed temporary URLs instead (default 60 min, matches Laravel's FilesystemAdapter ttl).
 */
class R2Url
{
    /**
     * Get a signed URL for displaying a private R2 object.
     * Returns null when $path is empty. Falls back to public url on sign failure.
     */
    public static function signed(?string $path, int $minutes = 60): ?string
    {
        if (! $path) {
            return null;
        }

        CentralSettingsServiceProvider::ensureR2Config();

        try {
            /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
            $disk = Storage::disk('r2');

            return $disk->temporaryUrl($path, now()->addMinutes($minutes));
        } catch (\Throwable $e) {
            Log::warning('R2Url: failed to sign URL, falling back to public url', [
                'path' => $path,
                'error' => $e->getMessage(),
            ]);

            try {
                return Storage::disk('r2')->url($path);
            } catch (\Throwable) {
                return null;
            }
        }
    }
}

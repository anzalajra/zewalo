<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

class CentralSetting extends Model
{
    protected $connection = 'central';

    protected $table = 'central_settings';

    protected $fillable = [
        'group',
        'key',
        'value',
        'is_encrypted',
        'label',
        'sort_order',
    ];

    protected $casts = [
        'is_encrypted' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saved(function (CentralSetting $setting) {
            Cache::forget("central_setting.{$setting->key}");
        });
        static::deleted(function (CentralSetting $setting) {
            Cache::forget("central_setting.{$setting->key}");
        });
    }

    public static function get(string $key, $default = null)
    {
        return Cache::remember("central_setting.{$key}", 3600, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();

            if (! $setting) {
                return $default;
            }

            if ($setting->is_encrypted && $setting->value !== null) {
                try {
                    return Crypt::decryptString($setting->value);
                } catch (\Exception $e) {
                    return $default;
                }
            }

            return $setting->value;
        });
    }

    public static function set(string $key, $value, bool $encrypted = false, string $group = 'general'): void
    {
        $setting = self::firstOrNew(['key' => $key]);
        $setting->group = $group;
        $setting->is_encrypted = $encrypted;

        if ($encrypted && $value !== null && $value !== '') {
            $setting->value = Crypt::encryptString($value);
        } else {
            $setting->value = $value;
        }

        if (! $setting->exists) {
            $setting->label = ucwords(str_replace(['r2_', 'mail_', '_'], ['', '', ' '], $key));
        }

        $setting->save();
    }

    public static function getGroup(string $group): array
    {
        return self::where('group', $group)
            ->orderBy('sort_order')
            ->get()
            ->mapWithKeys(function ($setting) {
                $value = $setting->value;

                if ($setting->is_encrypted && $value !== null) {
                    try {
                        $value = Crypt::decryptString($value);
                    } catch (\Exception $e) {
                        $value = null;
                    }
                }

                return [$setting->key => $value];
            })
            ->toArray();
    }
}

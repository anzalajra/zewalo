<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    public const SYSTEM_SLUG_ACCESSORIES_KITS = 'accessories-kits';

    // Kolom yang boleh diisi massal
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'is_active',
        'sort_order',
        'is_visible_on_storefront',
    ];

    // Tipe data khusus
    protected $casts = [
        'is_active' => 'boolean',
        'is_visible_on_storefront' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeVisibleOnStorefront(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->where('is_visible_on_storefront', true)
            ->where('slug', '!=', self::SYSTEM_SLUG_ACCESSORIES_KITS);
    }

    public function scopeExcludeSystem(Builder $query): Builder
    {
        return $query->where('slug', '!=', self::SYSTEM_SLUG_ACCESSORIES_KITS);
    }

    public function isSystem(): bool
    {
        return $this->slug === self::SYSTEM_SLUG_ACCESSORIES_KITS;
    }

    // Auto-generate slug dari name
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('name') && empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
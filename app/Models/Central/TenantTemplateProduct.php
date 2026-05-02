<?php

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class TenantTemplateProduct extends Model
{
    protected $connection = 'central';

    protected $fillable = [
        'tenant_template_id',
        'tenant_template_product_category_id',
        'tenant_template_brand_id',
        'name',
        'slug',
        'description',
        'daily_rate',
        'buffer_time',
        'image_path',
        'is_visible_on_frontend',
        'sort_order',
    ];

    protected $casts = [
        'daily_rate' => 'decimal:2',
        'buffer_time' => 'integer',
        'is_visible_on_frontend' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(TenantTemplate::class, 'tenant_template_id');
    }

    public function productCategory(): BelongsTo
    {
        return $this->belongsTo(TenantTemplateProductCategory::class, 'tenant_template_product_category_id');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(TenantTemplateBrand::class, 'tenant_template_brand_id');
    }

    public function units(): HasMany
    {
        return $this->hasMany(TenantTemplateProductUnit::class);
    }

    public function variations(): HasMany
    {
        return $this->hasMany(TenantTemplateProductVariation::class);
    }
}

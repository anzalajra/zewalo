<?php

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class TenantTemplateBrand extends Model
{
    protected $connection = 'central';

    protected $fillable = [
        'tenant_template_id',
        'name',
        'slug',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($brand) {
            if (empty($brand->slug)) {
                $brand->slug = Str::slug($brand->name);
            }
        });
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(TenantTemplate::class, 'tenant_template_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(TenantTemplateProduct::class);
    }
}

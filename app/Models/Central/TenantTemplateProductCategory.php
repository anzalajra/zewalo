<?php

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class TenantTemplateProductCategory extends Model
{
    protected $connection = 'central';

    protected $fillable = [
        'tenant_template_id',
        'name',
        'slug',
        'description',
        'sort_order',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
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

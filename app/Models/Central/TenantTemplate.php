<?php

namespace App\Models\Central;

use App\Models\TenantCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TenantTemplate extends Model
{
    protected $connection = 'central';

    protected $fillable = [
        'tenant_category_id',
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function tenantCategory(): BelongsTo
    {
        return $this->belongsTo(TenantCategory::class);
    }

    public function brands(): HasMany
    {
        return $this->hasMany(TenantTemplateBrand::class);
    }

    public function productCategories(): HasMany
    {
        return $this->hasMany(TenantTemplateProductCategory::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(TenantTemplateProduct::class);
    }
}

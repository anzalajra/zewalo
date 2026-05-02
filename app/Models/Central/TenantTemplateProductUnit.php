<?php

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TenantTemplateProductUnit extends Model
{
    protected $connection = 'central';

    protected $fillable = [
        'tenant_template_product_id',
        'serial_suffix',
        'condition',
        'status',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(TenantTemplateProduct::class, 'tenant_template_product_id');
    }

    public function kits(): HasMany
    {
        return $this->hasMany(TenantTemplateUnitKit::class);
    }
}

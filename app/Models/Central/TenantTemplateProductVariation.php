<?php

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TenantTemplateProductVariation extends Model
{
    protected $connection = 'central';

    protected $fillable = [
        'tenant_template_product_id',
        'name',
        'daily_rate',
    ];

    protected $casts = [
        'daily_rate' => 'decimal:2',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(TenantTemplateProduct::class, 'tenant_template_product_id');
    }
}

<?php

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TenantTemplateProductComponent extends Model
{
    protected $connection = 'central';

    protected $fillable = [
        'parent_template_product_id',
        'child_template_product_id',
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(TenantTemplateProduct::class, 'parent_template_product_id');
    }

    public function child(): BelongsTo
    {
        return $this->belongsTo(TenantTemplateProduct::class, 'child_template_product_id');
    }
}

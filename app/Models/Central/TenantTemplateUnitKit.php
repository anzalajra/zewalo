<?php

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TenantTemplateUnitKit extends Model
{
    protected $connection = 'central';

    protected $fillable = [
        'tenant_template_product_unit_id',
        'name',
        'serial_suffix',
        'track_by_serial',
        'condition',
        'notes',
    ];

    protected $casts = [
        'track_by_serial' => 'boolean',
    ];

    protected $attributes = [
        'track_by_serial' => true,
    ];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(TenantTemplateProductUnit::class, 'tenant_template_product_unit_id');
    }
}

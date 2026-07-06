<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DepreciationRun extends Model
{
    protected $fillable = [
        'date',
        'period',
        'total_amount',
        'items_processed',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(DepreciationRunItem::class);
    }
}

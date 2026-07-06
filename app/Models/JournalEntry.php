<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class JournalEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_number',
        'date',
        'description',
        'reference_type',
        'reference_id',
        'user_id',
        'reversal_of_id',
        'reversed_at',
    ];

    protected $casts = [
        'date' => 'date',
        'reversed_at' => 'datetime',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(JournalEntryItem::class);
    }

    public function reference(): MorphTo
    {
        return $this->morphTo();
    }

    /** The original entry this row reverses (null unless this is a reversal). */
    public function reversalOf(): BelongsTo
    {
        return $this->belongsTo(self::class, 'reversal_of_id');
    }

    /** Reversal entries created against this one. */
    public function reversals(): HasMany
    {
        return $this->hasMany(self::class, 'reversal_of_id');
    }

    /** True when this entry is itself a reversal of another entry. */
    public function isReversal(): bool
    {
        return $this->reversal_of_id !== null;
    }

    /** True when this entry has already been reversed. */
    public function isReversed(): bool
    {
        return $this->reversed_at !== null;
    }
}

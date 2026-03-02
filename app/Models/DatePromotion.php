<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DatePromotion extends Model
{
    protected $fillable = [
        'name',
        'description',
        'type',
        'value',
        'max_discount_amount',
        'promo_date',
        'recurring_yearly',
        'is_active',
        'priority',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'promo_date' => 'date',
        'recurring_yearly' => 'boolean',
        'is_active' => 'boolean',
        'priority' => 'integer',
    ];

    public const TYPE_PERCENTAGE = 'percentage';
    public const TYPE_FIXED = 'fixed';

    public static function getTypeOptions(): array
    {
        return [
            self::TYPE_PERCENTAGE => 'Percentage (%)',
            self::TYPE_FIXED => 'Fixed Amount (Rp)',
        ];
    }

    /**
     * Check if promotion is valid for given date
     */
    public function isValidForDate(\Carbon\Carbon $date): bool
    {
        if (!$this->is_active) return false;
        
        if ($this->recurring_yearly) {
            return $date->month === $this->promo_date->month 
                && $date->day === $this->promo_date->day;
        }
        
        return $date->isSameDay($this->promo_date);
    }

    /**
     * Calculate discount for given amount
     */
    public function calculateDiscount(float $amount): float
    {
        $discount = $this->type === self::TYPE_PERCENTAGE
            ? ($amount * $this->value / 100)
            : $this->value;

        if ($this->max_discount_amount && $discount > $this->max_discount_amount) {
            $discount = $this->max_discount_amount;
        }

        return round($discount, 2);
    }

    /**
     * Get applicable date promotion for given date
     */
    public static function getApplicablePromotion(\Carbon\Carbon $date): ?self
    {
        return self::where('is_active', true)
            ->get()
            ->filter(fn ($promo) => $promo->isValidForDate($date))
            ->sortByDesc('priority')
            ->first();
    }
}

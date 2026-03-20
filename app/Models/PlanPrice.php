<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanPrice extends Model
{
    /**
     * Always use central database.
     */
    protected $connection = 'central';

    protected $fillable = [
        'subscription_plan_id',
        'currency',
        'amount_monthly',
        'amount_yearly',
        'payment_gateway_code',
    ];

    protected $casts = [
        'amount_monthly' => 'decimal:2',
        'amount_yearly' => 'decimal:2',
    ];

    public function subscriptionPlan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }
}

<?php

namespace App\Models;

use App\Services\TaxService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rental extends Model
{
    protected $fillable = [
        'rental_code',
        'user_id',
        'discount_id',
        'daily_discount_id',
        'daily_discount_amount',
        'date_promotion_id',
        'date_promotion_amount',
        'category_discount_amount',
        'category_name',
        'quotation_id',
        'invoice_id',
        'discount_code',
        'start_date',
        'end_date',
        'returned_date',
        'status',
        'subtotal',
        'discount',
        'discount_type',
        'total',
        'late_fee',
        'deposit',
        'deposit_type',
        'security_deposit_amount',
        'security_deposit_status',
        'revenue_recognized_at',
        'down_payment_amount',
        'down_payment_status',
        'notes',
        'activity_log',
        'pricing_period',
        'fulfillment_method',
        'delivery_address',
        'delivery_contact',
        'delivery_notes',
        'custom_fields',
        'is_recurring',
        'recurrence_interval',
        'recurrence_next_date',
        'recurrence_end_date',
        'recurrence_parent_id',
        'tax_base',
        'ppn_rate',
        'tax_name',
        'ppn_amount',
        'pph_rate',
        'pph_amount',
        'price_includes_tax',
        'is_taxable',
        'payment_method',
        'transfer_proof_path',
        'payment_verified_at',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'returned_date' => 'datetime',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'daily_discount_amount' => 'decimal:2',
        'date_promotion_amount' => 'decimal:2',
        'category_discount_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'late_fee' => 'decimal:2',
        'deposit' => 'decimal:2',
        'security_deposit_amount' => 'decimal:2',
        'revenue_recognized_at' => 'datetime',
        'down_payment_amount' => 'decimal:2',
        'activity_log' => 'array',
        'custom_fields' => 'array',
        'is_recurring' => 'boolean',
        'recurrence_next_date' => 'date',
        'recurrence_end_date' => 'date',
        'tax_base' => 'decimal:2',
        'ppn_rate' => 'decimal:2',
        'ppn_amount' => 'decimal:2',
        'pph_rate' => 'decimal:2',
        'pph_amount' => 'decimal:2',
        'price_includes_tax' => 'boolean',
        'is_taxable' => 'boolean',
        'payment_verified_at' => 'datetime',
    ];

    public const STATUS_QUOTATION = 'quotation';

    public const STATUS_CONFIRMED = 'confirmed';

    public const STATUS_ACTIVE = 'active';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_CANCELLED = 'cancelled';

    public const STATUS_LATE_PICKUP = 'late_pickup';

    public const STATUS_LATE_RETURN = 'late_return';

    public const STATUS_PARTIAL_RETURN = 'partial_return';

    // Quotation whose start_date passed without ever being confirmed (dead-end, like cancelled).
    public const STATUS_EXPIRED = 'expired';

    protected static function booted()
    {
        static::created(function ($rental) {
            // Admin Notification - only send to users with admin/super_admin roles
            $admins = \App\Models\User::role(['super_admin', 'admin', 'staff'])->get();
            \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\NewBookingNotification($rental));

            // Customer Notification
            if ($rental->customer) {
                $rental->customer->notify(new \App\Notifications\BookingConfirmedNotification($rental));
            }
        });

        static::updated(function ($rental) {
            // Notify when rental is completed
            if ($rental->isDirty('status') && $rental->status === self::STATUS_COMPLETED) {
                // Notify admins
                $admins = \App\Models\User::role(['super_admin', 'admin', 'staff'])->get();
                \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\RentalCompletedNotification($rental));

                // Notify customer
                if ($rental->user) {
                    $rental->user->notify(new \App\Notifications\RentalCompletedNotification($rental));
                }
            }
        });

        static::saved(function ($rental) {
            $rental->refreshUnitStatuses();
        });

        static::deleting(function ($rental) {
            $units = $rental->items->map(fn ($item) => $item->productUnit)->filter();

            static::deleted(function () use ($units) {
                foreach ($units as $unit) {
                    $unit->refreshStatus();
                }
            });
        });
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($rental) {
            if (empty($rental->rental_code)) {
                $rental->rental_code = self::generateRentalCode();
            }
        });
    }

    public static function generateRentalCode(): string
    {
        $prefix = 'RNT';
        $date = now()->format('Ymd');

        // Find the last rental code for today directly from the code pattern
        // This is more robust than relying on created_at
        $lastRental = self::where('rental_code', 'like', $prefix.$date.'%')
            ->orderBy('rental_code', 'desc')
            ->first();

        $sequence = $lastRental ? intval(substr($lastRental->rental_code, -4)) + 1 : 1;

        // Ensure uniqueness with a loop
        do {
            $code = $prefix.$date.str_pad($sequence, 4, '0', STR_PAD_LEFT);
            $exists = self::where('rental_code', $code)->exists();
            if ($exists) {
                $sequence++;
            }
        } while ($exists);

        return $code;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @deprecated Use user() instead
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function discountRelation(): BelongsTo
    {
        return $this->belongsTo(Discount::class, 'discount_id');
    }

    public function dailyDiscount(): BelongsTo
    {
        return $this->belongsTo(DailyDiscount::class);
    }

    public function datePromotion(): BelongsTo
    {
        return $this->belongsTo(DatePromotion::class);
    }

    /**
     * Append an entry to the JSON activity log (audit trail) without re-triggering
     * observers / total recalculation. Use this — never append stamps to `notes` —
     * for MOVE/SWAP, status transitions, late-fee/discount adjustments, cancellations.
     */
    public function logActivity(string $message, string $type = 'general', ?string $user = null): void
    {
        $log = $this->activity_log ?? [];

        $log[] = [
            'at' => now()->toIso8601String(),
            'type' => $type,
            'message' => $message,
            'user' => $user ?? (auth()->user()?->email ?? 'system'),
        ];

        $this->activity_log = $log;

        if ($this->exists) {
            $this->updateQuietly(['activity_log' => $log]);
        }
    }

    /**
     * Single source of truth for rendering the ordered, non-zero discount layers
     * (category → daily promo → date promo → manual/coupon). Reused by admin views,
     * the rental editor summary, storefront checkout/cart, and quotation/invoice PDFs.
     *
     * @return array<int, array{key:string, label:string, amount:float}>
     */
    public function discountBreakdown(): array
    {
        $lines = [];

        $category = (float) ($this->category_discount_amount ?? 0);
        if ($category > 0) {
            $label = 'Diskon Kategori';
            if (! empty($this->category_name)) {
                $label .= ' ('.$this->category_name.')';
            }
            $lines[] = ['key' => 'category', 'label' => $label, 'amount' => $category];
        }

        $daily = (float) ($this->daily_discount_amount ?? 0);
        if ($daily > 0) {
            $lines[] = [
                'key' => 'daily',
                'label' => $this->dailyDiscount?->name ?? 'Diskon Promo Harian',
                'amount' => $daily,
            ];
        }

        $date = (float) ($this->date_promotion_amount ?? 0);
        if ($date > 0) {
            $lines[] = [
                'key' => 'date',
                'label' => $this->datePromotion?->name ?? 'Diskon Promo Tanggal',
                'amount' => $date,
            ];
        }

        $manual = $this->discount_type === 'percent'
            ? ((float) ($this->subtotal ?? 0)) * (((float) ($this->discount ?? 0)) / 100)
            : (float) ($this->discount ?? 0);
        if ($manual > 0) {
            if (! empty($this->discount_code)) {
                $label = 'Kupon '.$this->discount_code;
            } else {
                $label = $this->discountRelation?->name ?? 'Diskon Manual';
            }
            $lines[] = ['key' => 'manual', 'label' => $label, 'amount' => $manual];
        }

        return $lines;
    }

    /** The recurring source rental this quotation was generated from (if any). */
    public function recurrenceParent(): BelongsTo
    {
        return $this->belongsTo(Rental::class, 'recurrence_parent_id');
    }

    /** Quotations generated from this rental's recurrence schedule. */
    public function recurrenceChildren(): HasMany
    {
        return $this->hasMany(Rental::class, 'recurrence_parent_id');
    }

    /**
     * Clone this recurring rental into a fresh QUOTATION for the next cycle.
     * Dates shift to recurrence_next_date (preserving span); items copy as ghost
     * slots (unit assigned at confirm time). Financials/recognition reset.
     */
    public function replicateForRecurrence(): self
    {
        $len = (int) abs($this->start_date->diffInDays($this->end_date));
        $newStart = \Carbon\Carbon::parse($this->recurrence_next_date)
            ->setTimeFrom($this->start_date);

        $new = $this->replicate([
            'rental_code', 'status', 'returned_date', 'activity_log',
            'revenue_recognized_at', 'quotation_id', 'invoice_id',
            'down_payment_status', 'security_deposit_status',
        ]);
        $new->status = self::STATUS_QUOTATION;
        $new->start_date = $newStart;
        $new->end_date = $newStart->copy()->addDays($len);
        $new->recurrence_parent_id = $this->id;
        $new->is_recurring = false;           // the child is not itself a recurring source
        $new->recurrence_interval = null;
        $new->recurrence_next_date = null;
        $new->recurrence_end_date = null;
        $new->save();                          // rental_code auto-generated in boot()

        foreach ($this->items as $it) {
            $copy = $it->replicate(['product_unit_id']); // ghost slot — assign at confirm time
            $copy->product_unit_id = null;
            $copy->rental_id = $new->id;
            $copy->save();                     // subtotal via RentalItem hook, total via observer
        }

        return $new;
    }

    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function journalEntry(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(JournalEntry::class, 'reference');
    }

    public function items(): HasMany
    {
        return $this->hasMany(RentalItem::class);
    }

    public function deliveries(): HasMany
    {
        return $this->hasMany(Delivery::class);
    }

    // ─── Multi-tier pricing (billing period) ───

    /** Number of whole billing periods between two dates for a given period unit. */
    public static function periodsBetween($start, $end, string $period): int
    {
        $s = \Carbon\Carbon::parse($start);
        $e = \Carbon\Carbon::parse($end);
        $hours = max(1, (int) $s->diffInHours($e));

        return match ($period) {
            'hour' => max(1, (int) ceil($hours)),
            'week' => max(1, (int) ceil($hours / 24 / 7)),
            'month' => max(1, (int) ceil($hours / 24 / 30)),
            default => max(1, (int) ceil($hours / 24)),
        };
    }

    /** Human (Indonesian) label for the rental's billing period. */
    public function periodLabel(): string
    {
        return self::periodLabelFor($this->pricing_period ?? 'day');
    }

    /** Human (Indonesian) label for a given billing period. */
    public static function periodLabelFor(?string $period): string
    {
        return [
            'hour' => 'jam',
            'day' => 'hari',
            'week' => 'minggu',
            'month' => 'bulan',
        ][$period ?? 'day'] ?? 'hari';
    }

    /**
     * Auto-select the cheapest billing tier for a whole rental/cart given each line's
     * per-period rate map. The customer just picks dates and the system charges
     * whichever tier costs the least for that duration. The `hour` tier is only a
     * candidate for sub-day rentals; day/week/month apply to a full day and up.
     * Ties resolve to the finer (earlier) period.
     *
     * @param  array<int,array{rates:array<string,float>, quantity?:int}>  $lines
     * @return array{period:string, periods:int, total:float, totals:array<string,float>, counts:array<string,int>, candidates:array<int,string>}
     */
    public static function optimalPricing(array $lines, $start, $end): array
    {
        $hours = 24;
        try {
            $hours = max(1, (int) \Carbon\Carbon::parse($start)->diffInHours(\Carbon\Carbon::parse($end)));
        } catch (\Throwable $e) {
            // Fall back to a day-length window on unparseable dates.
        }

        $candidates = $hours < 24 ? ['hour', 'day'] : ['day', 'week', 'month'];

        $counts = [];
        $totals = [];
        foreach ($candidates as $p) {
            $counts[$p] = self::periodsBetween($start, $end, $p);
            $sum = 0.0;
            foreach ($lines as $line) {
                $rate = (float) ($line['rates'][$p] ?? 0);
                $sum += $rate * max(1, (int) ($line['quantity'] ?? 1)) * $counts[$p];
            }
            $totals[$p] = round($sum, 2);
        }

        $period = $candidates[0];
        foreach ($candidates as $p) {
            if ($totals[$p] < $totals[$period]) {
                $period = $p;
            }
        }

        return [
            'period' => $period,
            'periods' => $counts[$period] ?? 1,
            'total' => $totals[$period] ?? 0.0,
            'totals' => $totals,
            'counts' => $counts,
            'candidates' => $candidates,
        ];
    }

    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_QUOTATION => 'Quotation',
            self::STATUS_CONFIRMED => 'Confirmed',
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_CANCELLED => 'Cancelled',
            self::STATUS_LATE_PICKUP => 'Late Pickup',
            self::STATUS_LATE_RETURN => 'Late Return',
            self::STATUS_PARTIAL_RETURN => 'Partial Return',
            self::STATUS_EXPIRED => 'Expired',
        ];
    }

    /** Human label for a status value (falls back to the raw value). */
    public static function getStatusLabel(string $status): string
    {
        return self::getStatusOptions()[$status] ?? $status;
    }

    public static function getStatusColor(string $status): string
    {
        return match ($status) {
            self::STATUS_QUOTATION => 'warning',
            self::STATUS_CONFIRMED => 'info',
            self::STATUS_ACTIVE => 'success',
            self::STATUS_COMPLETED => 'purple',
            self::STATUS_CANCELLED => 'gray',
            self::STATUS_PARTIAL_RETURN => 'orange',
            self::STATUS_LATE_PICKUP, self::STATUS_LATE_RETURN => 'danger',
            self::STATUS_EXPIRED => 'gray',
            default => 'gray',
        };
    }

    /**
     * Check if the rental can be edited
     */
    public function canBeEdited(): bool
    {
        return in_array($this->status, [
            self::STATUS_QUOTATION,
            self::STATUS_CONFIRMED,
            self::STATUS_LATE_PICKUP,
            self::STATUS_ACTIVE,
            self::STATUS_LATE_RETURN,
            self::STATUS_PARTIAL_RETURN,
            // Expired is a soft timeout — an expired quote can be re-dated / re-confirmed to revive it.
            self::STATUS_EXPIRED,
        ]);
    }

    /**
     * Get the real-time status of the rental
     */
    public function getRealTimeStatus(): string
    {
        if (in_array($this->status, [self::STATUS_COMPLETED, self::STATUS_CANCELLED, self::STATUS_EXPIRED])) {
            return $this->status;
        }

        $now = now();

        // A quotation whose pickup date passed without being confirmed expires (dead-end).
        if ($this->status === self::STATUS_QUOTATION && $this->start_date < $now) {
            return self::STATUS_EXPIRED;
        }

        // A confirmed booking past its pickup date that hasn't been picked up is late.
        if ($this->status === self::STATUS_CONFIRMED && $this->start_date < $now) {
            return self::STATUS_LATE_PICKUP;
        }

        // Check for Partial Return condition (Dynamic)
        $hasPartialReturn = $this->deliveries->where('type', Delivery::TYPE_IN)->count() > 1;

        if ($this->end_date < $now) {
            if ($this->status === self::STATUS_ACTIVE || $this->status === self::STATUS_PARTIAL_RETURN || ($this->status === self::STATUS_ACTIVE && $hasPartialReturn)) {
                return self::STATUS_LATE_RETURN;
            }
        }

        if ($this->status === self::STATUS_ACTIVE && $hasPartialReturn) {
            return self::STATUS_PARTIAL_RETURN;
        }

        return $this->status;
    }

    /**
     * Check and update late status in database
     */
    public function checkAndUpdateLateStatus(): void
    {
        if (in_array($this->status, [self::STATUS_COMPLETED, self::STATUS_CANCELLED, self::STATUS_EXPIRED])) {
            return;
        }

        $now = now();
        $newStatus = $this->status;

        // Unconfirmed quotation past its pickup date → expired; confirmed → late pickup.
        if ($this->status === self::STATUS_QUOTATION && $this->start_date < $now) {
            $newStatus = self::STATUS_EXPIRED;
        }

        if ($this->status === self::STATUS_CONFIRMED && $this->start_date < $now) {
            $newStatus = self::STATUS_LATE_PICKUP;
        }

        if (($this->status === self::STATUS_ACTIVE || $this->status === self::STATUS_PARTIAL_RETURN) && $this->end_date < $now) {
            $newStatus = self::STATUS_LATE_RETURN;
        }

        if ($this->status !== $newStatus) {
            $this->update(['status' => $newStatus]);
            $this->refreshUnitStatuses();
        }
    }

    /**
     * Refresh all product unit statuses associated with this rental
     */
    public function refreshUnitStatuses(): void
    {
        foreach ($this->items as $item) {
            if ($item->productUnit) {
                $item->productUnit->refreshStatus();

                // Also refresh linked components (Children)
                foreach ($item->productUnit->kits as $kit) {
                    if ($kit->linked_unit_id) {
                        // We need to fetch the linked unit if not loaded
                        $linkedUnit = $kit->linkedUnit ?? \App\Models\ProductUnit::find($kit->linked_unit_id);
                        if ($linkedUnit) {
                            $linkedUnit->refreshStatus();
                        }
                    }
                }

                // Also refresh parent units (if this item is a component)
                // (Though usually parent status depends on component availability, not vice versa for "Rented" status,
                // but for "Scheduled" it might matter. Let's be safe.)
                $parentUnitIds = \App\Models\UnitKit::where('linked_unit_id', $item->product_unit_id)->pluck('unit_id');
                if ($parentUnitIds->isNotEmpty()) {
                    \App\Models\ProductUnit::whereIn('id', $parentUnitIds)->each(fn ($u) => $u->refreshStatus());
                }
            }
        }
    }

    /**
     * Check availability of rental items (conflicts with other active rentals)
     */
    public function checkAvailability(): array
    {
        if (! $this->relationLoaded('items')) {
            $this->load(['items.productUnit.kits']);
        }

        \Illuminate\Support\Facades\Log::info("Checking availability for Rental {$this->id} ({$this->rental_code})");
        $conflicts = [];

        foreach ($this->items as $item) {
            // Get all related unit IDs that would cause a conflict
            // 1. The unit itself
            $conflictUnitIds = [$item->product_unit_id];

            // 2. Parent units (Units that use this unit as a kit)
            // If I rent a Lens, I cannot rent the Camera Kit that contains it.
            $parentIds = \App\Models\UnitKit::where('linked_unit_id', $item->product_unit_id)->pluck('unit_id')->toArray();
            $conflictUnitIds = array_merge($conflictUnitIds, $parentIds);

            // 3. Child units (Units that are kits of this unit)
            // If I rent a Camera Kit, I cannot rent the Lens inside it.
            if ($item->productUnit) {
                $childIds = $item->productUnit->kits()->whereNotNull('linked_unit_id')->pluck('linked_unit_id')->toArray();
                $conflictUnitIds = array_merge($conflictUnitIds, $childIds);
            }

            $conflictUnitIds = array_unique($conflictUnitIds);

            \Illuminate\Support\Facades\Log::info("Item {$item->id} (Unit {$item->product_unit_id}) conflicts with units: ".implode(',', $conflictUnitIds));

            // Check if any of the conflicting units are already rented in an overlapping period
            $conflictingRentals = self::where('id', '!=', $this->id)
                ->whereIn('status', [self::STATUS_QUOTATION, self::STATUS_CONFIRMED, self::STATUS_ACTIVE, self::STATUS_LATE_PICKUP, self::STATUS_LATE_RETURN])
                ->where(function ($query) {
                    $query->whereBetween('start_date', [$this->start_date, $this->end_date])
                        ->orWhereBetween('end_date', [$this->start_date, $this->end_date])
                        ->orWhere(function ($q) {
                            $q->where('start_date', '<=', $this->start_date)
                                ->where('end_date', '>=', $this->end_date);
                        });
                })
                ->whereHas('items', function ($query) use ($conflictUnitIds) {
                    $query->where(function ($q) use ($conflictUnitIds) {
                        // 1. Direct match (Item IS one of the conflict units)
                        $q->whereIn('product_unit_id', $conflictUnitIds)
                        // 2. Item CONTAINS one of the conflict units (Item is a Parent of a conflict unit)
                            ->orWhereHas('productUnit', function ($pu) use ($conflictUnitIds) {
                                $pu->whereHas('kits', function ($k) use ($conflictUnitIds) {
                                    $k->whereIn('linked_unit_id', $conflictUnitIds);
                                });
                            });
                        // 3. Item IS CONTAINED BY one of the conflict units (Item is a Child of a conflict unit)
                        // This is covered by "Direct match" if $conflictUnitIds was expanded correctly to include parents.
                        // (e.g. My item = Parent. $conflictUnitIds includes Child.
                        // Their item = Child. Child IS in $conflictUnitIds. -> Direct match.)
                    });
                })
                ->with('customer')
                ->get();

            if ($conflictingRentals->isNotEmpty()) {
                \Illuminate\Support\Facades\Log::warning("Conflict detected for Rental {$this->id} with rentals: ".$conflictingRentals->pluck('rental_code')->implode(', '));
                $conflicts[] = [
                    'product_unit' => $item->productUnit,
                    'conflicting_rentals' => $conflictingRentals,
                ];
            }
        }

        return $conflicts;
    }

    /**
     * Resolve conflicts by removing conflicting items from other rentals
     */
    public function resolveConflicts(array $conflicts): void
    {
        foreach ($conflicts as $conflict) {
            $unit = $conflict['product_unit'];
            $conflictingRentals = $conflict['conflicting_rentals'];

            foreach ($conflictingRentals as $rental) {
                // Find the conflicting item
                $item = $rental->items()
                    ->where('product_unit_id', $unit->id)
                    ->first();

                if ($item) {
                    $item->delete();

                    // Recalculate totals for the other rental
                    $rental->refresh();
                    $subtotal = $rental->items->sum('subtotal');

                    // Update subtotal
                    $rental->subtotal = $subtotal;

                    // Recalculate total (keeping existing discount amount for fixed, or logic for percent)
                    // Since applyDiscount logic is complex, we'll do a simple update here
                    // assuming the admin will review the other rental if needed.
                    $rental->total = max(0, $subtotal - $rental->discount);

                    $rental->save();
                }
            }
        }
    }

    /**
     * Validate pickup and change status to active
     */
    public function validatePickup(): void
    {
        if (! in_array($this->status, [self::STATUS_CONFIRMED, self::STATUS_LATE_PICKUP])) {
            throw new \Exception('Cannot validate pickup for this rental status.');
        }

        // Check availability of rental items (conflicts with other active rentals)
        $conflicts = $this->checkAvailability();
        if (! empty($conflicts)) {
            $messages = [];
            foreach ($conflicts as $conflict) {
                $unitName = $conflict['product_unit']->product->name;
                $serial = $conflict['product_unit']->serial_number;

                $rentalInfo = $conflict['conflicting_rentals']->map(function ($r) {
                    $customerName = $r->customer->name ?? 'Unknown';

                    return "{$r->rental_code} ($customerName)";
                })->implode(', ');

                $messages[] = "$unitName ($serial) vs $rentalInfo";
            }
            $unitList = implode('; ', $messages);
            throw new \Exception("Cannot validate pickup. The following units have scheduling conflicts: $unitList. Please swap them.");
        }

        // Check if any unit is physically unavailable (e.g. still rented/late return from another customer)
        foreach ($this->items as $item) {
            if ($item->productUnit) {
                // Refresh status first to be sure
                $item->productUnit->refreshStatus();
                $unit = $item->productUnit;

                // 1. Check direct unit status
                if (in_array($unit->status, [ProductUnit::STATUS_RENTED, ProductUnit::STATUS_MAINTENANCE])) {
                    throw new \Exception("Unit {$unit->serial_number} ({$unit->product->name}) is currently {$unit->status}. Please swap the unit in the list before validating pickup.");
                }

                // 2. Check Components (if this is a Kit)
                // If I am picking up a Kit, all its components must be available
                $componentIds = $unit->kits()
                    ->whereNotNull('linked_unit_id')
                    ->pluck('linked_unit_id')
                    ->toArray();

                if (! empty($componentIds)) {
                    $unavailableComponents = ProductUnit::whereIn('id', $componentIds)
                        ->whereIn('status', [ProductUnit::STATUS_RENTED, ProductUnit::STATUS_MAINTENANCE])
                        ->get();

                    if ($unavailableComponents->isNotEmpty()) {
                        $comp = $unavailableComponents->first();
                        throw new \Exception("Component Unit {$comp->serial_number} ({$comp->product->name}) inside Kit {$unit->product->name} is currently {$comp->status}. Cannot pickup this kit.");
                    }
                }

                // 3. Check Parent Kits (if this is a Component)
                // If I am picking up a Component, the Kit containing it must not be rented out
                $parentIds = \App\Models\UnitKit::where('linked_unit_id', $unit->id)
                    ->pluck('unit_id')
                    ->toArray();

                if (! empty($parentIds)) {
                    $unavailableParents = ProductUnit::whereIn('id', $parentIds)
                        ->whereIn('status', [ProductUnit::STATUS_RENTED, ProductUnit::STATUS_MAINTENANCE])
                        ->get();

                    if ($unavailableParents->isNotEmpty()) {
                        $parent = $unavailableParents->first();
                        throw new \Exception("This unit is part of Kit {$parent->serial_number} ({$parent->product->name}) which is currently {$parent->status}. Cannot pickup this unit.");
                    }
                }
            }
        }

        // Check if all items with kits have their kits checked
        foreach ($this->items as $item) {
            if ($item->productUnit->kits->count() > 0) {
                $checkedKits = $item->rentalItemKits->count();
                // Filter out broken/lost kits to match attachKitsFromUnit logic
                $totalKits = $item->productUnit->kits->whereNotIn('condition', ['broken', 'lost'])->count();

                if ($checkedKits < $totalKits) {
                    throw new \Exception('All kit items must be checked before validating pickup.');
                }
            }
        }

        $this->update(['status' => self::STATUS_ACTIVE]);

        // Update product unit statuses to Rented
        $this->refreshUnitStatuses();
    }

    /**
     * Check if the rental can be deleted
     */
    public function canBeDeleted(): bool
    {
        return $this->status === self::STATUS_QUOTATION;
    }

    public function applyDiscount(Discount $discount): void
    {
        $discountAmount = $discount->calculateDiscount($this->subtotal);
        $this->discount_id = $discount->id;
        $this->discount_code = $discount->code;
        $this->discount = $discountAmount;
        $this->discount_type = 'fixed';
        $this->total = $this->subtotal - $discountAmount;
        // Deposit logic: if percent, it auto-adjusts via accessor. If fixed, it stays.
        // We do NOT overwrite deposit here to respect manual overrides.
        $this->save();
        $discount->incrementUsage();
    }

    public function removeDiscount(): void
    {
        $this->discount_id = null;
        $this->discount_code = null;
        $this->discount = 0;
        $this->discount_type = 'fixed';
        $this->total = $this->subtotal;
        // We do NOT overwrite deposit here to respect manual overrides.
        $this->save();
    }

    public function recalculateTotal(): void
    {
        // 1. Calculate Subtotal (Items)
        $this->subtotal = $this->items()->sum('subtotal');

        // 2. Calculate Discount
        // Manual/coupon layer...
        $manualDiscount = 0;
        if ($this->discountRelation) {
            $manualDiscount = $this->discountRelation->calculateDiscount($this->subtotal);
        } else {
            if ($this->discount_type === 'percent') {
                $manualDiscount = $this->subtotal * ($this->discount / 100);
            } else {
                $manualDiscount = $this->discount;
            }
        }

        // ...plus the promotion layers (category / daily / date), mirroring
        // RentalObserver::recalculateTotals() so every recalc path agrees on the
        // total. Without this, recalc would silently drop promo discounts and
        // inflate the total of a promo rental.
        $discountAmount = $manualDiscount
            + ($this->daily_discount_amount ?? 0)
            + ($this->date_promotion_amount ?? 0)
            + ($this->category_discount_amount ?? 0);

        // 3. Calculate Tax Base (DPP)
        // DPP = (Subtotal - Discount) + Late Fee
        $netSubtotal = max(0, $this->subtotal - $discountAmount);
        $lateFee = $this->late_fee ?? 0;
        $taxableAmount = $netSubtotal + $lateFee;

        // 4. Calculate Tax (Using TaxService for International Support)
        // Retrieve Customer
        $customer = $this->user ?? User::find($this->user_id);

        $taxResult = TaxService::calculateTax(
            $taxableAmount,
            $this->is_taxable,
            $this->price_includes_tax,
            $customer
        );

        $this->tax_base = $taxResult['tax_base'];
        $this->ppn_amount = $taxResult['tax_amount'];
        $this->ppn_rate = $taxResult['tax_rate'];
        $this->tax_name = $taxResult['tax_name'];

        // PPh Calculation (if applicable) - kept separate as it is withholding tax
        $pphAmount = 0;
        $taxEnabled = filter_var(\App\Models\Setting::get('tax_enabled', true), FILTER_VALIDATE_BOOLEAN);

        if ($taxEnabled && $this->is_taxable && $this->pph_rate > 0) {
            $pphAmount = $this->tax_base * ($this->pph_rate / 100);
        }
        $this->pph_amount = $pphAmount;

        // 5. Calculate Final Total
        // Total = TaxableAmount (if inclusive) OR (TaxableAmount + Tax) (if exclusive)
        // PLUS Deposit (Non-taxable)

        $totalBill = 0;
        if ($this->is_taxable && ! $this->price_includes_tax) {
            $totalBill = $taxableAmount + $this->ppn_amount;
        } else {
            $totalBill = $taxableAmount;
        }

        // Add Deposit
        // Deposit calculation based on Net Subtotal (Rental Value)
        $depositValue = 0;
        if ($this->deposit_type === 'percent') {
            $depositValue = $netSubtotal * ($this->deposit / 100);
        } else {
            $depositValue = $this->deposit;
        }

        $this->security_deposit_amount = $depositValue;

        $this->total = $totalBill + $depositValue;

        $this->save();
    }

    public function getDiscountAmountAttribute(): float
    {
        if ($this->discount_type === 'percent') {
            return $this->subtotal * ($this->discount / 100);
        }

        return (float) $this->discount;
    }

    public function getDepositAmountAttribute(): float
    {
        // Deposit based on Subtotal - Discount (Net Rental Value)
        $discountAmount = $this->discount_amount;
        $netSubtotal = max(0, $this->subtotal - $discountAmount);

        if ($this->deposit_type === 'percent') {
            return $netSubtotal * ($this->deposit / 100);
        }

        return (float) $this->deposit;
    }

    public static function calculateDeposit(float $amount): float
    {
        // Check if deposit is enabled
        $enabled = Setting::get('deposit_enabled', true);
        if (! $enabled) {
            return 0;
        }

        $type = Setting::get('deposit_type', 'percentage');

        // Determine default amount based on old setting if available
        $defaultAmount = 30;
        if ($type === 'percentage') {
            $oldValue = Setting::get('deposit_percentage');
            if ($oldValue !== null) {
                $defaultAmount = $oldValue;
            }
        }

        $settingAmount = Setting::get('deposit_amount', $defaultAmount);

        if ($type === 'percentage') {
            return $amount * ($settingAmount / 100);
        }

        return $settingAmount;
    }

    public static function calculateLateFee(float $dailyRate, int $daysLate): float
    {
        $type = Setting::get('late_fee_type', 'percentage');

        $defaultAmount = 10;
        if ($type === 'percentage') {
            $oldValue = Setting::get('late_fee_percentage');
            if ($oldValue !== null) {
                $defaultAmount = $oldValue;
            }
        }

        $amount = Setting::get('late_fee_amount', $defaultAmount);

        if ($type === 'percentage') {
            return ($dailyRate * ($amount / 100)) * $daysLate;
        }

        return $amount * $daysLate;
    }

    /**
     * Calculate the late fee for this rental, honoring the configured late-fee
     * mode (flat_per_day / per_unit_per_day / percentage_per_day / full_daily_rate
     * / tiered), per-product overrides, and per-item partial-return windows.
     */
    public function calculateOverdueFee(): float
    {
        if (! $this->end_date || $this->end_date->isFuture()) {
            return 0;
        }

        $mode = $this->resolveLateFeeMode();
        $amount = (float) Setting::get('late_fee_amount', 0);

        // Per-item IN-delivery check-in times are needed to scope each item's
        // overdue window (partial returns: an item returned earlier stops
        // accruing at its own return time, not the rental-wide "now").
        $this->loadMissing('items.deliveryItems.delivery');

        // Item fisik yang ter-assign (abaikan ghost slot tanpa unit).
        $items = $this->items->whereNotNull('product_unit_id');

        // Jam telat PER ITEM, dihitung dari waktu kembali efektif item itu sendiri
        // (checked_at di delivery IN bila sudah dikembalikan, atau now() bila masih di luar).
        $hoursLateFor = fn (RentalItem $item): float => max(
            0.0,
            (float) $this->end_date->diffInHours($this->effectiveReturnTime($item), false)
        );

        // Tiered mode dihitung per-jam, per-item (menghormati override produk).
        if ($mode === 'tiered') {
            $tiers = json_decode(Setting::get('late_fee_tiers', '[]'), true);
            $tiers = is_array($tiers) ? $tiers : [];

            $fee = 0.0;
            foreach ($items as $item) {
                $hours = $hoursLateFor($item);
                if ($hours <= 0) {
                    continue;
                }
                $fee += $this->tieredLateFeeForItem($item, $tiers, $hours);
            }

            return round($fee, 2);
        }

        // Flat per hari adalah denda TINGKAT RENTAL (bukan per item): satu nominal
        // per hari selama MASIH ada item yang belum kembali. Pakai jendela telat
        // terlama di antara semua item.
        if ($mode === 'flat_per_day') {
            $maxHours = 0.0;
            foreach ($items as $item) {
                $maxHours = max($maxHours, $hoursLateFor($item));
            }

            return $maxHours > 0 ? round($amount * (int) ceil($maxHours / 24), 2) : 0.0;
        }

        // Mode per-item per-hari: tiap item dibulatkan ke atas berdasarkan jam telatnya sendiri.
        $fee = 0.0;
        foreach ($items as $item) {
            $hours = $hoursLateFor($item);
            if ($hours <= 0) {
                continue;
            }

            $overdueDays = (int) ceil($hours / 24);
            $qty = $item->quantity ?? 1;

            $fee += match ($mode) {
                // Override produk (jika ada) menggantikan nominal global per unit.
                'per_unit_per_day' => (
                    ($item->productUnit?->product?->late_fee_daily_amount !== null
                        ? (float) $item->productUnit->product->late_fee_daily_amount
                        : $amount)
                    * $qty * $overdueDays
                ),
                'percentage_per_day' => $this->lateFeeDailyBase($item) * $qty * ($amount / 100) * $overdueDays,
                default => $this->lateFeeDailyBase($item) * $qty * $overdueDays, // full_daily_rate
            };
        }

        return round($fee, 2);
    }

    /**
     * Resolve the configured late-fee mode, deriving from the legacy late_fee_type
     * setting when the newer late_fee_mode is unset (backward compatibility).
     */
    protected function resolveLateFeeMode(): string
    {
        $mode = Setting::get('late_fee_mode');
        if ($mode === null) {
            $oldType = Setting::get('late_fee_type');
            $mode = match ($oldType) {
                'fixed' => 'flat_per_day',
                'percentage' => 'percentage_per_day',
                default => 'full_daily_rate',
            };
        }

        return $mode;
    }

    /**
     * The effective return moment for a single rental item, used to scope its late
     * fee to its own overdue window. For an item already checked back in (possibly
     * in an earlier partial-return batch) this is the EARLIEST time it was checked
     * in on an IN delivery; for an item still out it is now() (still accruing).
     *
     * Relies on `items.deliveryItems.delivery` being loaded (callers do so).
     */
    protected function effectiveReturnTime(RentalItem $item): \Illuminate\Support\Carbon
    {
        $earliest = null;

        foreach ($item->deliveryItems as $deliveryItem) {
            // Only unit-level rows on IN (return) deliveries that are actually checked.
            if ($deliveryItem->rental_item_kit_id !== null
                || ! $deliveryItem->is_checked
                || $deliveryItem->checked_at === null
                || $deliveryItem->delivery?->type !== Delivery::TYPE_IN) {
                continue;
            }

            if ($earliest === null || $deliveryItem->checked_at->lt($earliest)) {
                $earliest = $deliveryItem->checked_at;
            }
        }

        return $earliest ?? now();
    }

    /**
     * Tarif dasar denda harian per unit untuk sebuah item:
     * pakai override produk bila di-set, jika tidak pakai tarif sewa harian item.
     */
    protected function lateFeeDailyBase(RentalItem $item): float
    {
        $override = $item->productUnit?->product?->late_fee_daily_amount;

        return $override !== null ? (float) $override : (float) $item->daily_rate;
    }

    /**
     * Hitung denda tiered untuk satu item berdasarkan jam telat.
     * Setelah tier terakhir, tiap 24 jam berikutnya = +1× tarif dasar harian.
     */
    protected function tieredLateFeeForItem(RentalItem $item, array $tiers, float $hoursLate): float
    {
        $qty = $item->quantity ?? 1;
        $base = $this->lateFeeDailyBase($item); // tarif dasar harian per unit

        // Tanpa tier → fallback: tarif harian penuh per hari.
        if (empty($tiers)) {
            return $base * (int) ceil($hoursLate / 24) * $qty;
        }

        // Urutkan tier menaik berdasarkan up_to_hours.
        usort($tiers, fn ($a, $b) => (float) ($a['up_to_hours'] ?? 0) <=> (float) ($b['up_to_hours'] ?? 0));

        $chargePerUnit = function (array $tier) use ($base): float {
            $value = (float) ($tier['amount'] ?? 0);

            return ($tier['charge_type'] ?? 'percentage') === 'fixed'
                ? $value                       // Rp tetap per unit
                : $base * ($value / 100);      // % dari tarif harian
        };

        $lastTier = end($tiers);
        $lastHours = (float) ($lastTier['up_to_hours'] ?? 0);

        // Masih dalam jangkauan tier → ambil tier pertama yang menampung jam telat.
        if ($hoursLate <= $lastHours) {
            foreach ($tiers as $tier) {
                if ($hoursLate <= (float) ($tier['up_to_hours'] ?? 0)) {
                    return $chargePerUnit($tier) * $qty;
                }
            }
        }

        // Lewat tier terakhir → charge tier terakhir + tiap 24 jam berikutnya 1× tarif harian.
        $extraDays = (int) ceil(($hoursLate - $lastHours) / 24);
        $perUnit = $chargePerUnit($lastTier) + ($extraDays * $base);

        return $perUnit * $qty;
    }

    /**
     * Human-readable breakdown of how calculateOverdueFee() arrives at the late fee, for
     * transparency in the return settlement modal. Mirrors the exact same inputs/logic;
     * the authoritative `fee` is taken straight from calculateOverdueFee() so the rincian
     * total always matches what is actually charged.
     *
     * @return array{
     *   is_late: bool, fee: float, mode: string, mode_label: string,
     *   hours_late: float, overdue_days: int, end_date: ?string, now: string,
     *   amount_setting: float, summary: ?string,
     *   lines: array<int, array{label:string, detail:string, amount:float}>
     * }
     */
    public function lateFeeBreakdown(): array
    {
        $now = now();

        $result = [
            'is_late' => false,
            'fee' => 0.0,
            'mode' => '',
            'mode_label' => '',
            'hours_late' => 0.0,
            'overdue_days' => 0,
            'end_date' => $this->end_date?->format('d M Y H:i'),
            'now' => $now->format('d M Y H:i'),
            'amount_setting' => 0.0,
            'summary' => null,
            'lines' => [],
        ];

        if (! $this->end_date || $this->end_date->isFuture()) {
            return $result;
        }

        $mode = $this->resolveLateFeeMode();
        $amount = (float) Setting::get('late_fee_amount', 0);

        // Per-item return times (partial returns) — same source as calculateOverdueFee().
        $this->loadMissing('items.deliveryItems.delivery');
        $items = $this->items->whereNotNull('product_unit_id');

        // Jam & hari telat per item, dari waktu kembali efektif item itu sendiri.
        $hoursLateFor = fn (RentalItem $item): float => max(
            0.0,
            (float) $this->end_date->diffInHours($this->effectiveReturnTime($item), false)
        );
        $daysLateFor = fn (RentalItem $item): int => (int) ceil($hoursLateFor($item) / 24);

        // Telat tingkat rental = jendela terlama di antara semua item.
        $maxHours = 0.0;
        foreach ($items as $item) {
            $maxHours = max($maxHours, $hoursLateFor($item));
        }

        if ($maxHours <= 0) {
            return $result;
        }

        // Apakah ada item yang sudah dikembalikan lebih awal (mis. partial return)?
        $hasEarlyReturns = false;
        foreach ($items as $item) {
            if ($hoursLateFor($item) < $maxHours) {
                $hasEarlyReturns = true;
                break;
            }
        }

        $overdueDays = (int) ceil($maxHours / 24);

        $labels = [
            'flat_per_day' => 'Flat per hari',
            'per_unit_per_day' => 'Per unit per hari',
            'percentage_per_day' => 'Persentase tarif harian / hari',
            'full_daily_rate' => 'Tarif sewa harian penuh / hari',
            'tiered' => 'Bertingkat (tiered)',
        ];

        $result['is_late'] = true;
        $result['mode'] = $mode;
        $result['mode_label'] = $labels[$mode] ?? $mode;
        $result['hours_late'] = round($maxHours, 1);
        $result['overdue_days'] = $overdueDays;
        $result['amount_setting'] = $amount;
        $result['fee'] = $this->calculateOverdueFee();

        if ($hasEarlyReturns) {
            $result['summary'] = 'Beberapa item sudah dikembalikan lebih awal — denda dihitung per item dari waktu kembali masing-masing.';
        }

        // Suffix penjelas untuk item yang kembali lebih awal / masih di luar.
        $itemNote = function (RentalItem $item) use ($maxHours, $hoursLateFor): string {
            $h = $hoursLateFor($item);
            if ($h <= 0) {
                return ' · dikembalikan tepat waktu';
            }
            if ($h < $maxHours) {
                return ' · dikembalikan lebih awal';
            }

            return '';
        };

        $lines = [];

        if ($mode === 'tiered') {
            $tiers = json_decode(Setting::get('late_fee_tiers', '[]'), true);
            $tiers = is_array($tiers) ? $tiers : [];

            foreach ($items as $item) {
                $qty = $item->quantity ?? 1;
                $h = $hoursLateFor($item);
                $lines[] = [
                    'label' => $this->lateFeeItemLabel($item),
                    'detail' => $qty.' unit · tarif harian Rp'.number_format($this->lateFeeDailyBase($item), 0, ',', '.')
                        .' · '.round($h, 1).' jam telat'.$itemNote($item),
                    'amount' => $h > 0 ? round($this->tieredLateFeeForItem($item, $tiers, $h), 2) : 0.0,
                ];
            }

            $result['lines'] = $lines;

            return $result;
        }

        switch ($mode) {
            case 'flat_per_day':
                $lines[] = [
                    'label' => 'Tarif flat (tingkat rental)',
                    'detail' => 'Rp'.number_format($amount, 0, ',', '.').' × '.$overdueDays.' hari'
                        .' (selama masih ada item belum kembali)',
                    'amount' => round($amount * $overdueDays, 2),
                ];
                break;

            case 'per_unit_per_day':
                foreach ($items as $item) {
                    $qty = $item->quantity ?? 1;
                    $days = $daysLateFor($item);
                    $override = $item->productUnit?->product?->late_fee_daily_amount;
                    $perUnit = $override !== null ? (float) $override : $amount;
                    $lines[] = [
                        'label' => $this->lateFeeItemLabel($item),
                        'detail' => $qty.' unit × Rp'.number_format($perUnit, 0, ',', '.')
                            .($override !== null ? ' (override produk)' : '').' × '.$days.' hari'.$itemNote($item),
                        'amount' => round($perUnit * $qty * $days, 2),
                    ];
                }
                break;

            case 'percentage_per_day':
                foreach ($items as $item) {
                    $qty = $item->quantity ?? 1;
                    $days = $daysLateFor($item);
                    $dailyBase = $this->lateFeeDailyBase($item);
                    $lines[] = [
                        'label' => $this->lateFeeItemLabel($item),
                        'detail' => $qty.' × Rp'.number_format($dailyBase, 0, ',', '.')
                            .' × '.rtrim(rtrim(number_format($amount, 2, ',', ''), '0'), ',').'%'
                            .' × '.$days.' hari'.$itemNote($item),
                        'amount' => round($dailyBase * $qty * ($amount / 100) * $days, 2),
                    ];
                }
                break;

            default: // full_daily_rate
                foreach ($items as $item) {
                    $qty = $item->quantity ?? 1;
                    $days = $daysLateFor($item);
                    $dailyBase = $this->lateFeeDailyBase($item);
                    $lines[] = [
                        'label' => $this->lateFeeItemLabel($item),
                        'detail' => $qty.' × Rp'.number_format($dailyBase, 0, ',', '.').' × '.$days.' hari'.$itemNote($item),
                        'amount' => round($dailyBase * $qty * $days, 2),
                    ];
                }
                break;
        }

        $result['lines'] = $lines;

        return $result;
    }

    /** Product (+ variation) label for a rental item, used in the late fee breakdown. */
    protected function lateFeeItemLabel(RentalItem $item): string
    {
        $product = $item->productUnit?->product?->name ?? $item->product?->name ?? 'Item';
        $variation = $item->productUnit?->variation?->name ?? null;

        return $product.($variation ? ' ('.$variation.')' : '');
    }

    /**
     * Complete the rental on return.
     *
     * @param  float|null  $lateFee  When provided (e.g. a manual adjustment or waiver from
     *                               the settlement modal), it is used as-is. When null the
     *                               fee is auto-calculated from the overdue window.
     */
    public function validateReturn(?float $lateFee = null): void
    {
        // Check if all items (main units and kits) in the latest Delivery IN are checked
        $deliveryIn = $this->deliveries->where('type', Delivery::TYPE_IN)->sortByDesc('id')->first();

        if (! $deliveryIn || ! $deliveryIn->allItemsChecked()) {
            throw new \Exception('All items must be checked in the Delivery Note before validating return.');
        }

        $this->returned_date = now();

        // Honor an explicitly provided late fee (manual override / waiver); otherwise
        // auto-calculate. Previously this always recomputed and silently discarded any
        // manual adjustment made during the return settlement.
        $this->late_fee = $lateFee ?? $this->calculateOverdueFee();

        $this->status = self::STATUS_COMPLETED;

        // Full recalculation (subtotal, discount, tax, deposit, total) so the stored total
        // stays consistent with the rest of the app and the linked invoice. The old
        // "subtotal - discount + lateFee" shortcut dropped tax and deposit from the total.
        // recalculateTotal() persists the row.
        $this->recalculateTotal();

        // Update product unit statuses based on return condition
        // We iterate all IN deliveries to find the condition for each item
        $inDeliveries = $this->deliveries()->where('type', Delivery::TYPE_IN)->with('items')->get();

        foreach ($this->items as $item) {
            if ($item->productUnit) {
                // Find the delivery item for this rental item in ANY IN delivery
                $condition = null;

                foreach ($inDeliveries as $delivery) {
                    $dItem = $delivery->items
                        ->where('rental_item_id', $item->id)
                        ->whereNull('rental_item_kit_id')
                        ->first();

                    if ($dItem && $dItem->condition) {
                        $condition = $dItem->condition;
                    }
                }

                if ($condition && in_array($condition, ['broken', 'lost'])) {
                    $item->productUnit->update(['status' => ProductUnit::STATUS_MAINTENANCE]);
                } else {
                    $item->productUnit->refreshStatus();
                }
            }
        }
    }

    /**
     * Reopen a COMPLETED rental back to ACTIVE so its items / total can be corrected and
     * the return redone.
     *
     * Operational revert only:
     *  - status → ACTIVE, returned_date cleared;
     *  - the latest IN delivery is reopened (DRAFT + items unchecked) so the return
     *    checklist can be redone and units stop reading as "returned";
     *  - unit statuses are recomputed (non-damaged units go back to RENTED).
     *
     * It deliberately does NOT reverse the financial entries posted by the previous
     * completion (revenue recognition / deposit settlement) — those must be reviewed
     * before completing again to avoid double counting.
     */
    public function reopenFromCompleted(): void
    {
        if ($this->status !== self::STATUS_COMPLETED) {
            throw new \RuntimeException('Only completed rentals can be reopened.');
        }

        $this->status = self::STATUS_ACTIVE;
        $this->returned_date = null;
        // Allow revenue to be recognized again when this rental is re-completed
        // (IFRS mode); prevents a permanent gap in the ledger after a reopen.
        $this->revenue_recognized_at = null;
        $this->save();

        // Reopen the final return so refreshStatus() no longer sees the items as checked-in.
        $deliveryIn = $this->deliveries()
            ->where('type', Delivery::TYPE_IN)
            ->orderByDesc('id')
            ->first();

        if ($deliveryIn) {
            $deliveryIn->update(['status' => Delivery::STATUS_DRAFT]);

            foreach ($deliveryIn->items as $item) {
                $item->update(['is_checked' => false, 'checked_at' => null]);
                if ($item->rental_item_kit_id && $item->rentalItemKit) {
                    $item->rentalItemKit->update(['is_returned' => false]);
                }
            }
        }

        // Non-damaged units return to RENTED; damaged/maintenance units stay out.
        foreach ($this->items as $item) {
            $item->productUnit?->refreshStatus();
        }
    }

    /**
     * Keep the rental's outstanding balance reflected in an invoice (Accounts
     * Receivable). If an invoice already exists, re-aggregate it; otherwise issue one
     * only when money is actually owed. In advanced finance mode the GL is posted
     * canonically (revenue-once, PPN split, advances reclassified). Returns
     * ['invoice' => ?Invoice, 'action' => 'recalc'|'created'|'none', 'reopened' => bool].
     */
    public function syncOutstandingInvoice(string $noteContext = 'update'): array
    {
        if ($this->invoice_id) {
            $invoice = Invoice::find($this->invoice_id);
            if (! $invoice) {
                return ['invoice' => null, 'action' => 'none', 'reopened' => false];
            }

            $previousStatus = $invoice->status;
            $invoice->recalculate();

            return [
                'invoice' => $invoice,
                'action' => 'recalc',
                'reopened' => $previousStatus === Invoice::STATUS_PAID && $invoice->status !== Invoice::STATUS_PAID,
            ];
        }

        // No invoice yet — only issue one when money is actually owed.
        $alreadyPaid = (float) $this->rentalIncomeTransactions()->sum('amount');
        $outstanding = (float) $this->total - $alreadyPaid;

        if ($outstanding <= 0.01) {
            return ['invoice' => null, 'action' => 'none', 'reopened' => false];
        }

        $invoice = Invoice::create([
            'user_id' => $this->user_id,
            'quotation_id' => $this->quotation_id,
            'date' => now(),
            'due_date' => now()->addDays(7),
            'status' => Invoice::STATUS_WAITING_FOR_PAYMENT,
            'subtotal' => $this->subtotal,
            'tax_base' => $this->tax_base ?? $this->subtotal,
            'ppn_rate' => $this->ppn_rate ?? 0,
            'ppn_amount' => $this->ppn_amount ?? 0,
            'tax' => $this->ppn_amount ?? 0,
            'late_fee' => $this->late_fee ?? 0,
            'total' => $this->total,
            'is_taxable' => $this->is_taxable ?? false,
            'price_includes_tax' => $this->price_includes_tax ?? false,
            'notes' => 'Generated ('.$noteContext.') for Rental '.$this->rental_code,
        ]);

        // Dr Piutang / Cr Revenue-or-Deferred + PPN (2-1400) + Denda (4-1200),
        // standard-aware and idempotent (no-op in simple mode).
        \App\Services\RentalAccountingService::postInvoiceIssued($invoice);

        $advanceTotal = 0.0;
        foreach ($this->rentalIncomeTransactions()->get() as $transaction) {
            $transaction->reference()->associate($invoice);
            if (! str_contains((string) $transaction->description, 'Invoice #')) {
                $transaction->description = $transaction->description.' (Inv #'.$invoice->number.')';
            }
            $transaction->save();
            $advanceTotal += (float) $transaction->amount;
        }

        // Any pre-invoice advance (Cr 2-1300) now settles the receivable it prepaid:
        // Dr Uang Muka (2-1300) / Cr Piutang (1-1200).
        \App\Services\RentalAccountingService::reclassifyAdvanceToReceivable($invoice, $advanceTotal);

        // Link first so the rental is included, then recalc paid_amount/status.
        $this->update(['invoice_id' => $invoice->id]);
        $invoice->recalculate();

        return ['invoice' => $invoice, 'action' => 'created', 'reopened' => false];
    }

    /**
     * Income transactions linked to this rental (and its originating quotation, if any).
     */
    protected function rentalIncomeTransactions(): \Illuminate\Database\Eloquent\Builder
    {
        return FinanceTransaction::query()
            ->where(function ($query) {
                $query->where('reference_type', self::class)
                    ->where('reference_id', $this->id);

                if ($this->quotation_id) {
                    $query->orWhere(function ($q) {
                        $q->where('reference_type', Quotation::class)
                            ->where('reference_id', $this->quotation_id);
                    });
                }
            })
            ->where('type', FinanceTransaction::TYPE_INCOME);
    }

    /**
     * Create delivery documents (Out and In) for this rental
     */
    public function createDeliveries(): void
    {
        // Skip delivery creation if Deliveries feature is disabled
        if (! (tenant()?->hasFeature(\App\Enums\TenantFeature::Deliveries) ?? true)) {
            return;
        }

        // Ensure all rental items have their kits attached first
        foreach ($this->items as $item) {
            $item->attachKitsFromUnit();
        }
        $this->load('items.rentalItemKits');

        // Create or Update Delivery Out (SJK)
        $deliveryOut = $this->deliveries()->where('type', Delivery::TYPE_OUT)->first();
        if (! $deliveryOut) {
            $deliveryOut = Delivery::create([
                'rental_id' => $this->id,
                'type' => Delivery::TYPE_OUT,
                'date' => $this->start_date,
                'status' => Delivery::STATUS_DRAFT,
            ]);
        }

        if ($deliveryOut->status === Delivery::STATUS_DRAFT || $deliveryOut->items()->count() === 0) {
            foreach ($this->items as $item) {
                // Skip empty "ghost" slots (no unit assigned) — nothing physical to hand out.
                if (! $item->product_unit_id || ! $item->productUnit) {
                    continue;
                }

                // Main Unit
                $deliveryOut->items()->firstOrCreate([
                    'rental_item_id' => $item->id,
                    'rental_item_kit_id' => null,
                ], [
                    'is_checked' => false,
                    'condition' => $item->productUnit->condition,
                ]);

                // Kits
                foreach ($item->rentalItemKits as $kit) {
                    $deliveryOut->items()->firstOrCreate([
                        'rental_item_id' => $item->id,
                        'rental_item_kit_id' => $kit->id,
                    ], [
                        'is_checked' => false,
                        'condition' => $kit->condition_out,
                    ]);
                }
            }
        }

        // Create or Update Delivery In (SJM)
        // Find a non-completed Delivery IN (to avoid re-populating completed partial return deliveries)
        $deliveryIn = $this->deliveries()->where('type', Delivery::TYPE_IN)
            ->where('status', '!=', Delivery::STATUS_COMPLETED)
            ->first();

        if (! $deliveryIn) {
            // Only create if no Delivery IN exists at all (first time)
            if (! $this->deliveries()->where('type', Delivery::TYPE_IN)->exists()) {
                $deliveryIn = Delivery::create([
                    'rental_id' => $this->id,
                    'type' => Delivery::TYPE_IN,
                    'date' => $this->end_date,
                    'status' => Delivery::STATUS_DRAFT,
                ]);
            } else {
                // All Delivery INs are completed (from partial returns), nothing to sync
                return;
            }
        }

        if ($deliveryIn->status === Delivery::STATUS_DRAFT) {
            // Kits flagged "not taken" on the OUT delivery were never handed to the
            // customer, so there is nothing to receive back — skip them entirely so
            // they don't appear in the return checklist or block its completion gate.
            // The flag is usually set AFTER the IN row was first created at pickup time,
            // so also delete any stale IN row for those kits (not just skip new ones).
            $outNotTakenKitIds = $deliveryOut
                ? $deliveryOut->items()->where('not_taken', true)->pluck('rental_item_kit_id')->filter()->all()
                : [];

            if (! empty($outNotTakenKitIds)) {
                $deliveryIn->items()->whereIn('rental_item_kit_id', $outNotTakenKitIds)->delete();
            }

            foreach ($this->items as $item) {
                // Skip empty "ghost" slots (no unit assigned) — nothing physical to receive back.
                if (! $item->product_unit_id || ! $item->productUnit) {
                    continue;
                }

                // Main Unit
                $deliveryIn->items()->firstOrCreate([
                    'rental_item_id' => $item->id,
                    'rental_item_kit_id' => null,
                ], [
                    'is_checked' => false,
                ]);

                // Kits
                foreach ($item->rentalItemKits as $kit) {
                    if (in_array($kit->id, $outNotTakenKitIds, true)) {
                        continue;
                    }

                    $deliveryIn->items()->firstOrCreate([
                        'rental_item_id' => $item->id,
                        'rental_item_kit_id' => $kit->id,
                    ], [
                        'is_checked' => false,
                    ]);
                }
            }
        }
    }

    /**
     * Realign draft delivery schedule dates to the rental's current start/end dates.
     * Only touches draft rows (never completed/in-progress deliveries) so editing a
     * rental's dates keeps its surat jalan in sync. Called after createDeliveries().
     */
    public function syncDeliveryDates(): void
    {
        $this->deliveries()
            ->where('type', Delivery::TYPE_OUT)
            ->where('status', Delivery::STATUS_DRAFT)
            ->update([
                'date' => $this->start_date,
                'scheduled_at' => $this->start_date,
            ]);

        $this->deliveries()
            ->where('type', Delivery::TYPE_IN)
            ->where('status', Delivery::STATUS_DRAFT)
            ->update([
                'date' => $this->end_date,
                'scheduled_at' => $this->end_date,
            ]);
    }

    /**
     * Cancel the rental with a reason
     *
     * @param  string  $reason  The reason for cancellation
     *
     * @throws \Exception If rental cannot be cancelled
     */
    public function cancelRental(string $reason): void
    {
        // Validate that rental can be cancelled
        if (! in_array($this->status, [self::STATUS_QUOTATION, self::STATUS_CONFIRMED, self::STATUS_LATE_PICKUP])) {
            throw new \Exception('Cannot cancel this rental. Only quotation, confirmed or late pickup rentals can be cancelled.');
        }

        // Release all product units back to available/scheduled
        foreach ($this->items as $item) {
            if ($item->productUnit) {
                $item->productUnit->refreshStatus();
            }
        }

        // Decrement discount usage if a discount was applied
        if ($this->discountRelation) {
            $this->discountRelation->decrement('usage_count');
        }

        // Update rental status and save cancel reason
        $this->update([
            'status' => self::STATUS_CANCELLED,
            'cancel_reason' => $reason,
        ]);

        // Cancel all associated deliveries
        $this->deliveries()->update(['status' => Delivery::STATUS_CANCELLED]);
    }

    /**
     * Check if the rental can be cancelled
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, [
            self::STATUS_QUOTATION,
            self::STATUS_CONFIRMED,
            self::STATUS_LATE_PICKUP,
        ]);
    }
}

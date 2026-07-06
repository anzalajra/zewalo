<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Invoice extends Model
{
    protected $fillable = [
        'number',
        'quotation_id',
        'user_id',
        'date',
        'due_date',
        'subtotal',
        'tax',
        'total',
        'paid_amount',
        'late_fee',
        'status',
        'notes',
        'tax_base',
        'ppn_rate',
        'tax_name',
        'ppn_amount',
        'pph_rate',
        'pph_amount',
        'is_taxable',
        'price_includes_tax',
        'tax_invoice_number',
        'tax_invoice_date',
        'currency',
        'exchange_rate',
        'pph23_withheld',
        'pph23_rate',
        'pph23_amount',
        'pph23_bukti_potong_number',
    ];

    protected $casts = [
        'date' => 'date',
        'due_date' => 'date',
        'tax_invoice_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'late_fee' => 'decimal:2',
        'tax_base' => 'decimal:2',
        'ppn_rate' => 'decimal:2',
        'ppn_amount' => 'decimal:2',
        'pph_rate' => 'decimal:2',
        'pph_amount' => 'decimal:2',
        'is_taxable' => 'boolean',
        'price_includes_tax' => 'boolean',
        'exchange_rate' => 'decimal:6',
        'pph23_withheld' => 'boolean',
        'pph23_rate' => 'decimal:2',
        'pph23_amount' => 'decimal:2',
    ];

    public const STATUS_SENT = 'sent';

    public const STATUS_NEGOTIATION = 'negotiation';

    public const STATUS_WAITING_FOR_PAYMENT = 'waiting_for_payment';

    public const STATUS_PAID = 'paid';

    public const STATUS_PARTIAL = 'partial';

    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_SENT => 'Sent',
            self::STATUS_NEGOTIATION => 'Negotiation',
            self::STATUS_WAITING_FOR_PAYMENT => 'Waiting for Payment',
            self::STATUS_PAID => 'Paid',
            self::STATUS_PARTIAL => 'Partial',
        ];
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($invoice) {
            if (empty($invoice->number)) {
                $invoice->number = self::generateNumber();
            }
        });

        static::created(function ($invoice) {
            // Notify admins about new invoice
            $admins = User::role(['super_admin', 'admin', 'staff'])->get();
            \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\InvoiceCreatedNotification($invoice));
        });
    }

    public static function generateNumber(): string
    {
        $prefix = 'INV';
        $date = now()->format('Ymd');
        $last = self::whereDate('created_at', today())->latest()->first();
        $sequence = $last ? intval(substr($last->number, -4)) + 1 : 1;

        return $prefix.$date.str_pad($sequence, 4, '0', STR_PAD_LEFT);
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

    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class);
    }

    public function rentals(): HasMany
    {
        return $this->hasMany(Rental::class);
    }

    public function journalEntry(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(JournalEntry::class, 'reference');
    }

    public function transactions(): MorphMany
    {
        return $this->morphMany(FinanceTransaction::class, 'reference');
    }

    public function recalculate(): void
    {
        // Aggregate from Rentals
        $rentals = $this->rentals;

        // Subtotal (Sum of Rental Subtotals)
        $this->subtotal = $rentals->sum('subtotal');

        // Tax Base
        $this->tax_base = $rentals->sum('tax_base');

        // Taxes
        $this->ppn_amount = $rentals->sum('ppn_amount');
        $this->pph_amount = $rentals->sum('pph_amount');

        // Set Tax details from first rental (assuming uniform rate)
        if ($rentals->isNotEmpty()) {
            $firstRental = $rentals->first();
            $this->ppn_rate = $firstRental->ppn_rate;
            $this->tax_name = $firstRental->tax_name;
            $this->is_taxable = $firstRental->is_taxable;
            $this->price_includes_tax = $firstRental->price_includes_tax;
        }

        // Late Fee
        $this->late_fee = $rentals->sum('late_fee');

        // Total (Sum of Rental Totals - which includes everything)
        $this->total = $rentals->sum('total');

        // Recalculate paid amount from transactions
        $this->paid_amount = $this->transactions()
            ->where('type', FinanceTransaction::TYPE_INCOME)
            ->sum('amount');

        // PPh 23 withheld by the customer counts toward settlement: the customer pays
        // net cash but the withheld amount is our prepaid-tax credit, so the invoice is
        // fully settled once cash paid + PPh23 credit ≥ total.
        $settled = (float) $this->paid_amount + (float) ($this->pph23_amount ?? 0);

        // Update status based on settlement
        if ($settled >= (float) $this->total - 0.01) {
            $this->status = self::STATUS_PAID;
        } elseif ($settled > 0) {
            $this->status = self::STATUS_PARTIAL;
        } else {
            $this->status = self::STATUS_WAITING_FOR_PAYMENT;
        }

        $this->save();
    }

    /**
     * Outstanding balance (total − paid). The single place this math lives — used
     * by the table balance column, action ->visible() guards, and the View page.
     */
    public function getBalanceAttribute(): float
    {
        // PPh 23 withheld is a settled portion (prepaid-tax credit), so it reduces the
        // outstanding cash balance alongside actual payments.
        return round((float) $this->total - (float) $this->paid_amount - (float) ($this->pph23_amount ?? 0), 2);
    }
}

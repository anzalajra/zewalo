<?php

namespace App\Observers;

use App\Models\Rental;
use App\Models\User;
use App\Notifications\BookingConfirmedNotification;
use App\Notifications\NewBookingNotification;
use App\Services\TaxService;
use Illuminate\Support\Facades\Notification;

class RentalObserver
{
    public function created(Rental $rental): void
    {
        // Recalculate after items are saved
        $this->recalculateTotals($rental);

        // Notify Admins
        $admins = User::role(['super_admin', 'admin'])->get();
        Notification::send($admins, new NewBookingNotification($rental));
    }

    public function updated(Rental $rental): void
    {
        $this->recalculateTotals($rental);

        // Audit every status transition to the activity log (single source for the
        // "Log Aktivitas" card). Uses updateQuietly internally so it won't re-loop.
        if ($rental->wasChanged('status')) {
            $options = Rental::getStatusOptions();
            $from = $rental->getOriginal('status');
            $to = $rental->status;
            $fromLabel = $options[$from] ?? $from;
            $toLabel = $options[$to] ?? $to;
            $rental->logActivity("Status: {$fromLabel} → {$toLabel}", 'status');

            // Preventive-maintenance usage tracking: bump each rented unit's counter when
            // a rental finishes, so maintenance:flag-due can flag units after N rentals.
            if ($to === Rental::STATUS_COMPLETED && $from !== Rental::STATUS_COMPLETED) {
                $unitIds = $rental->items()->whereNotNull('product_unit_id')->pluck('product_unit_id')->all();
                if (! empty($unitIds)) {
                    \App\Models\ProductUnit::whereIn('id', $unitIds)->increment('rentals_since_last_maintenance');
                }
            }
        }

        // Notify Customer if status changed to confirmed
        if ($rental->isDirty('status') && $rental->status === 'confirmed') {
            if ($rental->customer) {
                $rental->customer->notify(new BookingConfirmedNotification($rental));
            }
        }
    }

    protected function recalculateTotals(Rental $rental): void
    {
        $subtotal = $rental->items()->sum('subtotal');

        // Manual/coupon discount layer...
        $discountAmount = 0;
        if ($rental->discount_type === 'percent') {
            $discountAmount = $subtotal * (($rental->discount ?? 0) / 100);
        } else {
            $discountAmount = $rental->discount ?? 0;
        }

        // ...plus the promotion layers (category / daily / date), matching
        // Rental::recalculateTotal(). Without this, changing an item silently
        // dropped promo discounts and inflated a promo rental's total.
        $discountAmount += ($rental->category_discount_amount ?? 0)
            + ($rental->daily_discount_amount ?? 0)
            + ($rental->date_promotion_amount ?? 0);

        $taxableAmount = max(0, $subtotal - $discountAmount);

        // Calculate Tax using TaxService
        $taxResult = TaxService::calculateTax(
            $taxableAmount,
            $rental->is_taxable ?? false,
            $rental->price_includes_tax ?? false,
            $rental->customer
        );

        $total = $taxResult['total'];
        $ppnAmount = $taxResult['tax_amount'];
        $taxBase = $taxResult['tax_base'];

        if (
            abs(($rental->subtotal ?? 0) - $subtotal) > 0.01 ||
            abs(($rental->total ?? 0) - $total) > 0.01 ||
            abs(($rental->ppn_amount ?? 0) - $ppnAmount) > 0.01
        ) {
            $rental->updateQuietly([
                'subtotal' => $subtotal,
                'tax_base' => $taxBase,
                'ppn_amount' => $ppnAmount,
                'total' => $total,
                'ppn_rate' => $taxResult['tax_rate'],
                'tax_name' => $taxResult['tax_name'],
            ]);
        }
    }
}

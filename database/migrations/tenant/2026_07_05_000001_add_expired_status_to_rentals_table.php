<?php

use App\Helpers\DatabaseHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Add the 'expired' status to the rentals status enum.
     *
     * A never-confirmed quotation whose pickup date passes becomes 'expired'
     * (a dead-end, like cancelled) instead of being auto-promoted to
     * 'late_pickup'. See Rental::checkAndUpdateLateStatus() and
     * CheckLateRentals command.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        DatabaseHelper::modifyEnumColumn(
            'rentals',
            'status',
            ['quotation', 'confirmed', 'active', 'completed', 'cancelled', 'late_pickup', 'late_return', 'partial_return', 'expired'],
            'quotation',
            false
        );
    }

    /**
     * Reverse the migration.
     *
     * Warning: any rows already flipped to 'expired' must be re-mapped before
     * running this, or the CHECK constraint will reject them.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        // Move any expired rows back to cancelled so the tighter constraint holds.
        DB::table('rentals')->where('status', 'expired')->update(['status' => 'cancelled']);

        DatabaseHelper::modifyEnumColumn(
            'rentals',
            'status',
            ['quotation', 'confirmed', 'active', 'completed', 'cancelled', 'late_pickup', 'late_return', 'partial_return'],
            'quotation',
            false
        );
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Kit "Not Taken" at pickup + kit "auto scan with parent".
     *
     * - delivery_items.not_taken: per-pickup-occurrence flag for a kit the
     *   customer declined; it then vanishes from the return checklist.
     * - unit_kits.auto_scan_with_parent: small accessories hidden from the scan
     *   list that get auto-checked when their parent unit is scanned.
     */
    public function up(): void
    {
        Schema::table('delivery_items', function (Blueprint $table) {
            if (! Schema::hasColumn('delivery_items', 'not_taken')) {
                $table->boolean('not_taken')->default(false)->after('checked_at');
            }
        });

        Schema::table('unit_kits', function (Blueprint $table) {
            if (! Schema::hasColumn('unit_kits', 'auto_scan_with_parent')) {
                $table->boolean('auto_scan_with_parent')->default(false)->after('serial_number');
            }
        });
    }

    public function down(): void
    {
        Schema::table('delivery_items', function (Blueprint $table) {
            if (Schema::hasColumn('delivery_items', 'not_taken')) {
                $table->dropColumn('not_taken');
            }
        });

        Schema::table('unit_kits', function (Blueprint $table) {
            if (Schema::hasColumn('unit_kits', 'auto_scan_with_parent')) {
                $table->dropColumn('auto_scan_with_parent');
            }
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Columns backing the return-settlement v2, activity log, explicit
     * category-discount layer, and single-shot revenue recognition.
     */
    public function up(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            if (! Schema::hasColumn('rentals', 'category_discount_amount')) {
                $table->decimal('category_discount_amount', 12, 2)->default(0)->after('date_promotion_amount');
            }
            if (! Schema::hasColumn('rentals', 'category_name')) {
                $table->string('category_name')->nullable()->after('category_discount_amount');
            }
            if (! Schema::hasColumn('rentals', 'revenue_recognized_at')) {
                $table->timestamp('revenue_recognized_at')->nullable()->after('security_deposit_status');
            }
            if (! Schema::hasColumn('rentals', 'activity_log')) {
                $table->json('activity_log')->nullable()->after('notes');
            }
        });

        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'late_fee_daily_amount')) {
                // Per-product override for the daily late fee. Null = use the
                // tenant-wide late_fee_mode/late_fee_amount setting.
                $table->decimal('late_fee_daily_amount', 12, 2)->nullable()->after('id');
            }
        });

        Schema::table('delivery_items', function (Blueprint $table) {
            if (! Schema::hasColumn('delivery_items', 'checked_at')) {
                // When an IN-delivery row was actually checked back in. Lets the
                // late-fee calc scope each item's overdue window to its own return
                // time (partial returns). Null → item treated as still out (now()).
                $table->timestamp('checked_at')->nullable()->after('is_checked');
            }
        });
    }

    public function down(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            foreach (['category_discount_amount', 'category_name', 'revenue_recognized_at', 'activity_log'] as $col) {
                if (Schema::hasColumn('rentals', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'late_fee_daily_amount')) {
                $table->dropColumn('late_fee_daily_amount');
            }
        });

        Schema::table('delivery_items', function (Blueprint $table) {
            if (Schema::hasColumn('delivery_items', 'checked_at')) {
                $table->dropColumn('checked_at');
            }
        });
    }
};

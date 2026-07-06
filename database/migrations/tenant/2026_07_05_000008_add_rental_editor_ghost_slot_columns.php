<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Ghost-slot support for the ported RentalEditor (Fase 3) + variation multi-tier
 * pricing. Rental items may now carry their own product / variation identity and
 * an unassigned serial (product_unit_id NULL) so the editor can persist "empty
 * slots" ("+N kosong") that get filled later via pickup / Transfer. `rate_type`
 * and `sort_order` mirror the FTV schema. Product variations gain the same
 * period-rate columns already added to products in 000007. All guarded with
 * hasColumn so it is safe to (re-)run against any tenant.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rental_items', function (Blueprint $table) {
            if (! Schema::hasColumn('rental_items', 'product_id')) {
                $table->unsignedBigInteger('product_id')->nullable()->after('product_unit_id');
            }
            if (! Schema::hasColumn('rental_items', 'product_variation_id')) {
                $table->unsignedBigInteger('product_variation_id')->nullable()->after('product_id');
            }
            if (! Schema::hasColumn('rental_items', 'rate_type')) {
                $table->string('rate_type')->nullable()->default('day')->after('daily_rate');
            }
            if (! Schema::hasColumn('rental_items', 'sort_order')) {
                $table->integer('sort_order')->default(0)->after('discount');
            }
        });

        // Ghost slots need a nullable product_unit_id. Guard the ALTER so re-runs
        // (and installs that already made it nullable) are no-ops.
        if (Schema::hasColumn('rental_items', 'product_unit_id')) {
            try {
                Schema::table('rental_items', function (Blueprint $table) {
                    $table->unsignedBigInteger('product_unit_id')->nullable()->change();
                });
            } catch (\Throwable $e) {
                // Column already nullable (or DB driver rejected the no-op change) — safe to ignore.
            }
        }

        Schema::table('product_variations', function (Blueprint $table) {
            if (! Schema::hasColumn('product_variations', 'hourly_rate')) {
                $table->decimal('hourly_rate', 12, 2)->nullable()->after('daily_rate');
            }
            if (! Schema::hasColumn('product_variations', 'weekly_rate')) {
                $table->decimal('weekly_rate', 12, 2)->nullable()->after('hourly_rate');
            }
            if (! Schema::hasColumn('product_variations', 'monthly_rate')) {
                $table->decimal('monthly_rate', 12, 2)->nullable()->after('weekly_rate');
            }
        });
    }

    public function down(): void
    {
        Schema::table('rental_items', function (Blueprint $table) {
            foreach (['product_id', 'product_variation_id', 'rate_type', 'sort_order'] as $c) {
                if (Schema::hasColumn('rental_items', $c)) {
                    $table->dropColumn($c);
                }
            }
        });

        Schema::table('product_variations', function (Blueprint $table) {
            foreach (['hourly_rate', 'weekly_rate', 'monthly_rate'] as $c) {
                if (Schema::hasColumn('product_variations', $c)) {
                    $table->dropColumn($c);
                }
            }
        });
    }
};

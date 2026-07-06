<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Preventive-maintenance usage counter: number of rentals a unit has served since its
 * last maintenance. Read by maintenance:flag-due to flag units for preventive service
 * once they exceed the configured threshold (Setting maintenance_preventive_rental_count).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_units', function (Blueprint $table) {
            if (! Schema::hasColumn('product_units', 'rentals_since_last_maintenance')) {
                $table->unsignedInteger('rentals_since_last_maintenance')->default(0);
            }
        });
    }

    public function down(): void
    {
        Schema::table('product_units', function (Blueprint $table) {
            if (Schema::hasColumn('product_units', 'rentals_since_last_maintenance')) {
                $table->dropColumn('rentals_since_last_maintenance');
            }
        });
    }
};

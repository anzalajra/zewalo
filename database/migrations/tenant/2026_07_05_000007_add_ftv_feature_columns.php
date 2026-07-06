<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Additive columns backing the ported warehouse-ftv features: multi-tier
 * pricing, custom fields, rental fulfillment method, recurring rentals, and
 * delivery photos / signature / routing. All guarded with hasColumn so they
 * are safe to (re-)run against any tenant.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            if (! Schema::hasColumn('rentals', 'pricing_period')) {
                $table->string('pricing_period')->nullable()->after('end_date');
            }
            if (! Schema::hasColumn('rentals', 'fulfillment_method')) {
                $table->string('fulfillment_method')->default('pickup')->after('pricing_period');
            }
            if (! Schema::hasColumn('rentals', 'delivery_address')) {
                $table->text('delivery_address')->nullable()->after('fulfillment_method');
            }
            if (! Schema::hasColumn('rentals', 'delivery_contact')) {
                $table->string('delivery_contact')->nullable()->after('delivery_address');
            }
            if (! Schema::hasColumn('rentals', 'delivery_notes')) {
                $table->text('delivery_notes')->nullable()->after('delivery_contact');
            }
            if (! Schema::hasColumn('rentals', 'custom_fields')) {
                $table->json('custom_fields')->nullable()->after('notes');
            }
            if (! Schema::hasColumn('rentals', 'is_recurring')) {
                $table->boolean('is_recurring')->default(false)->after('custom_fields');
            }
            if (! Schema::hasColumn('rentals', 'recurrence_interval')) {
                $table->string('recurrence_interval')->nullable()->after('is_recurring');
            }
            if (! Schema::hasColumn('rentals', 'recurrence_next_date')) {
                $table->date('recurrence_next_date')->nullable()->after('recurrence_interval');
            }
            if (! Schema::hasColumn('rentals', 'recurrence_end_date')) {
                $table->date('recurrence_end_date')->nullable()->after('recurrence_next_date');
            }
            if (! Schema::hasColumn('rentals', 'recurrence_parent_id')) {
                $table->unsignedBigInteger('recurrence_parent_id')->nullable()->after('recurrence_end_date');
            }
        });

        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'hourly_rate')) {
                $table->decimal('hourly_rate', 12, 2)->nullable()->after('daily_rate');
            }
            if (! Schema::hasColumn('products', 'weekly_rate')) {
                $table->decimal('weekly_rate', 12, 2)->nullable()->after('hourly_rate');
            }
            if (! Schema::hasColumn('products', 'monthly_rate')) {
                $table->decimal('monthly_rate', 12, 2)->nullable()->after('weekly_rate');
            }
            if (! Schema::hasColumn('products', 'custom_fields')) {
                $table->json('custom_fields')->nullable();
            }
        });

        Schema::table('delivery_items', function (Blueprint $table) {
            if (! Schema::hasColumn('delivery_items', 'photos')) {
                $table->json('photos')->nullable()->after('condition');
            }
        });

        Schema::table('deliveries', function (Blueprint $table) {
            if (! Schema::hasColumn('deliveries', 'recipient_name')) {
                $table->string('recipient_name')->nullable();
            }
            if (! Schema::hasColumn('deliveries', 'recipient_signature')) {
                $table->longText('recipient_signature')->nullable();
            }
            if (! Schema::hasColumn('deliveries', 'signed_at')) {
                $table->timestamp('signed_at')->nullable();
            }
            if (! Schema::hasColumn('deliveries', 'scheduled_at')) {
                $table->dateTime('scheduled_at')->nullable();
            }
            if (! Schema::hasColumn('deliveries', 'address')) {
                $table->text('address')->nullable();
            }
            if (! Schema::hasColumn('deliveries', 'sort_order')) {
                $table->integer('sort_order')->default(0);
            }
        });
    }

    public function down(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            foreach ([
                'pricing_period', 'fulfillment_method', 'delivery_address', 'delivery_contact',
                'delivery_notes', 'custom_fields', 'is_recurring', 'recurrence_interval',
                'recurrence_next_date', 'recurrence_end_date', 'recurrence_parent_id',
            ] as $c) {
                if (Schema::hasColumn('rentals', $c)) {
                    $table->dropColumn($c);
                }
            }
        });

        Schema::table('products', function (Blueprint $table) {
            foreach (['hourly_rate', 'weekly_rate', 'monthly_rate', 'custom_fields'] as $c) {
                if (Schema::hasColumn('products', $c)) {
                    $table->dropColumn($c);
                }
            }
        });

        Schema::table('delivery_items', function (Blueprint $table) {
            if (Schema::hasColumn('delivery_items', 'photos')) {
                $table->dropColumn('photos');
            }
        });

        Schema::table('deliveries', function (Blueprint $table) {
            foreach (['recipient_name', 'recipient_signature', 'signed_at', 'scheduled_at', 'address', 'sort_order'] as $c) {
                if (Schema::hasColumn('deliveries', $c)) {
                    $table->dropColumn($c);
                }
            }
        });
    }
};

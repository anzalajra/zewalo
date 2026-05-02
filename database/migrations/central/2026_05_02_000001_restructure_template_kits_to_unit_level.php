<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('central')->create('tenant_template_unit_kits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_template_product_unit_id')
                ->constrained('tenant_template_product_units')
                ->cascadeOnDelete();
            $table->string('name');
            $table->string('serial_suffix')->nullable();
            $table->boolean('track_by_serial')->default(true);
            $table->enum('condition', ['excellent', 'good', 'fair', 'poor'])->default('excellent');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::connection('central')->dropIfExists('tenant_template_product_components');
    }

    public function down(): void
    {
        Schema::connection('central')->create('tenant_template_product_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_template_product_id')
                ->constrained('tenant_template_products')
                ->cascadeOnDelete();
            $table->foreignId('child_template_product_id')
                ->constrained('tenant_template_products')
                ->cascadeOnDelete();
            $table->integer('quantity')->default(1);
            $table->timestamps();

            $table->unique(
                ['parent_template_product_id', 'child_template_product_id'],
                'tmpl_components_unique'
            );
        });

        Schema::connection('central')->dropIfExists('tenant_template_unit_kits');
    }
};

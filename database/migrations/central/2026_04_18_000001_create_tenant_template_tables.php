<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('central')->create('tenant_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_category_id')
                ->unique()
                ->constrained('tenant_categories')
                ->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::connection('central')->create('tenant_template_brands', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_template_id')
                ->constrained('tenant_templates')
                ->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->boolean('is_default')->default(false);
            $table->timestamps();

            $table->unique(['tenant_template_id', 'slug']);
        });

        Schema::connection('central')->create('tenant_template_product_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_template_id')
                ->constrained('tenant_templates')
                ->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['tenant_template_id', 'slug']);
        });

        Schema::connection('central')->create('tenant_template_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_template_id')
                ->constrained('tenant_templates')
                ->cascadeOnDelete();
            $table->foreignId('tenant_template_product_category_id')
                ->constrained('tenant_template_product_categories')
                ->cascadeOnDelete();
            $table->foreignId('tenant_template_brand_id')
                ->nullable()
                ->constrained('tenant_template_brands')
                ->nullOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->decimal('daily_rate', 12, 2)->default(0);
            $table->integer('buffer_time')->default(0);
            $table->string('image_path')->nullable();
            $table->boolean('is_visible_on_frontend')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['tenant_template_id', 'slug']);
        });

        Schema::connection('central')->create('tenant_template_product_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_template_product_id')
                ->constrained('tenant_template_products')
                ->cascadeOnDelete();
            $table->string('serial_suffix');
            $table->enum('condition', ['excellent', 'good', 'fair', 'poor'])->default('good');
            $table->enum('status', ['available', 'rented', 'maintenance', 'retired'])->default('available');
            $table->timestamps();
        });

        Schema::connection('central')->create('tenant_template_product_variations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_template_product_id')
                ->constrained('tenant_template_products')
                ->cascadeOnDelete();
            $table->string('name');
            $table->decimal('daily_rate', 12, 2)->nullable();
            $table->timestamps();
        });

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
    }

    public function down(): void
    {
        Schema::connection('central')->dropIfExists('tenant_template_product_components');
        Schema::connection('central')->dropIfExists('tenant_template_product_variations');
        Schema::connection('central')->dropIfExists('tenant_template_product_units');
        Schema::connection('central')->dropIfExists('tenant_template_products');
        Schema::connection('central')->dropIfExists('tenant_template_product_categories');
        Schema::connection('central')->dropIfExists('tenant_template_brands');
        Schema::connection('central')->dropIfExists('tenant_templates');
    }
};

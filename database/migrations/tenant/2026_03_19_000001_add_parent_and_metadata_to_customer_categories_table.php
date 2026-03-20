<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customer_categories', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->after('id')->constrained('customer_categories')->nullOnDelete();
            $table->string('description')->nullable()->after('name');
            $table->text('icon')->nullable()->after('description');
            $table->integer('sort_order')->default(0)->after('icon');
        });
    }

    public function down(): void
    {
        Schema::table('customer_categories', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['parent_id', 'description', 'icon', 'sort_order']);
        });
    }
};

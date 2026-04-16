<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('central')->table('tenants', function (Blueprint $table) {
            $table->string('setup_status')->default('pending')->after('tenant_category_id');
        });
    }

    public function down(): void
    {
        Schema::connection('central')->table('tenants', function (Blueprint $table) {
            $table->dropColumn('setup_status');
        });
    }
};

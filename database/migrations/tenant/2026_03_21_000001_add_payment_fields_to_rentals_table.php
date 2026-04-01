<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->after('notes');
            $table->string('transfer_proof_path')->nullable()->after('payment_method');
            $table->timestamp('payment_verified_at')->nullable()->after('transfer_proof_path');
        });
    }

    public function down(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'transfer_proof_path', 'payment_verified_at']);
        });
    }
};

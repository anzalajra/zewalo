<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Server-saved Bluetooth label designs for the LuckPrinter editor (per tenant).
     */
    public function up(): void
    {
        if (Schema::hasTable('label_templates')) {
            return;
        }

        Schema::create('label_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            // The editor's serialize() output: {v, label, orientation, elements}.
            $table->json('design');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('label_templates');
    }
};

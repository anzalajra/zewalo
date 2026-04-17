<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('central')->create('tenant_issues', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id')->nullable()->index();
            $table->string('tenant_name')->nullable();
            $table->string('code', 64)->index(); // e.g. SETUP_WIZARD_SEED_FAILED
            $table->string('area', 64)->default('tenant')->index(); // setup_wizard, queue, upload, etc.
            $table->string('severity', 16)->default('error')->index(); // error, warning, critical
            $table->string('title');
            $table->text('message');
            $table->string('exception_class')->nullable();
            $table->string('file')->nullable();
            $table->unsignedInteger('line')->nullable();
            $table->longText('stack_trace')->nullable();
            $table->json('context')->nullable();
            $table->string('url')->nullable();
            $table->string('user_email')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->string('resolved_by')->nullable();
            $table->text('resolution_note')->nullable();
            $table->timestamps();

            $table->index(['resolved_at', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::connection('central')->dropIfExists('tenant_issues');
    }
};

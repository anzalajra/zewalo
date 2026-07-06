<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Journal reversal support: a reversal entry links back to the entry it
     * mirrors (reversal_of_id), and a reversed entry is stamped (reversed_at).
     * See JournalService::reverseEntry().
     */
    public function up(): void
    {
        Schema::table('journal_entries', function (Blueprint $table) {
            if (! Schema::hasColumn('journal_entries', 'reversal_of_id')) {
                $table->foreignId('reversal_of_id')->nullable()->after('reference_id')
                    ->constrained('journal_entries')->nullOnDelete();
            }
            if (! Schema::hasColumn('journal_entries', 'reversed_at')) {
                $table->timestamp('reversed_at')->nullable()->after('reversal_of_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('journal_entries', function (Blueprint $table) {
            if (Schema::hasColumn('journal_entries', 'reversal_of_id')) {
                $table->dropConstrainedForeignId('reversal_of_id');
            }
            if (Schema::hasColumn('journal_entries', 'reversed_at')) {
                $table->dropColumn('reversed_at');
            }
        });
    }
};

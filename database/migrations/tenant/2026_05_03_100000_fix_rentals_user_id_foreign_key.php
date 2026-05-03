<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('rentals') || ! Schema::hasColumn('rentals', 'user_id')) {
            return;
        }

        $driver = DB::getDriverName();

        // Drop any FK on rentals.user_id (legacy: rentals_customer_id_foreign pointing to customers,
        // or a partially-fixed rentals_user_id_foreign pointing somewhere wrong).
        $this->dropForeignKeysOnUserId($driver);

        // Recreate the correct FK: rentals.user_id -> users.id
        Schema::table('rentals', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('rentals') || ! Schema::hasColumn('rentals', 'user_id')) {
            return;
        }

        Schema::table('rentals', function (Blueprint $table) {
            try {
                $table->dropForeign(['user_id']);
            } catch (\Throwable $e) {
                // ignore
            }
        });
    }

    private function dropForeignKeysOnUserId(string $driver): void
    {
        if ($driver === 'pgsql') {
            $constraints = DB::select("
                SELECT tc.constraint_name
                FROM information_schema.table_constraints tc
                JOIN information_schema.key_column_usage kcu
                    ON tc.constraint_name = kcu.constraint_name
                    AND tc.table_schema = kcu.table_schema
                WHERE tc.table_name = 'rentals'
                    AND tc.constraint_type = 'FOREIGN KEY'
                    AND kcu.column_name = 'user_id'
            ");

            foreach ($constraints as $row) {
                DB::statement('ALTER TABLE "rentals" DROP CONSTRAINT "' . $row->constraint_name . '"');
            }

            return;
        }

        if ($driver === 'mysql') {
            $database = DB::getDatabaseName();
            $constraints = DB::select(
                "SELECT CONSTRAINT_NAME
                FROM information_schema.KEY_COLUMN_USAGE
                WHERE TABLE_SCHEMA = ?
                    AND TABLE_NAME = 'rentals'
                    AND COLUMN_NAME = 'user_id'
                    AND REFERENCED_TABLE_NAME IS NOT NULL",
                [$database]
            );

            foreach ($constraints as $row) {
                DB::statement('ALTER TABLE `rentals` DROP FOREIGN KEY `' . $row->CONSTRAINT_NAME . '`');
            }

            return;
        }

        // SQLite: no-op (FKs handled via table rebuild; not relevant for tenant runtime).
    }
};

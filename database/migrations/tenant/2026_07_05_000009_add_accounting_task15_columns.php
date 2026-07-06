<?php

use App\Models\Account;
use App\Models\Currency;
use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Fase 6 Task 15 — accounting additives (per-tenant):
 *  - Multi-currency foundation (currencies master + doc currency/exchange_rate; GL stays base).
 *  - Bank reconciliation (bank_statement_lines + finance_transactions.reconciled_at).
 *  - PPh 23 withholding (invoices.pph23_* + prepaid-tax account 1-1500).
 *  - Per-unit depreciation persistence (product_units.accumulated_depreciation + depreciation_run_items).
 *
 * All guarded with hasTable/hasColumn so re-running against partially-migrated tenants is safe.
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── Multi-currency ────────────────────────────────────────────────
        if (! Schema::hasTable('currencies')) {
            Schema::create('currencies', function (Blueprint $table) {
                $table->id();
                $table->string('code', 3)->unique();
                $table->string('name');
                $table->string('symbol', 8)->nullable();
                $table->decimal('exchange_rate', 18, 6)->default(1); // base units per 1 unit of this currency
                $table->boolean('is_base')->default(false);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        Schema::table('finance_transactions', function (Blueprint $table) {
            if (! Schema::hasColumn('finance_transactions', 'currency')) {
                $table->string('currency', 3)->nullable();
            }
            if (! Schema::hasColumn('finance_transactions', 'exchange_rate')) {
                $table->decimal('exchange_rate', 18, 6)->default(1);
            }
            if (! Schema::hasColumn('finance_transactions', 'reconciled_at')) {
                $table->timestamp('reconciled_at')->nullable();
            }
        });

        Schema::table('invoices', function (Blueprint $table) {
            if (! Schema::hasColumn('invoices', 'currency')) {
                $table->string('currency', 3)->nullable();
            }
            if (! Schema::hasColumn('invoices', 'exchange_rate')) {
                $table->decimal('exchange_rate', 18, 6)->default(1);
            }
            // ── PPh 23 withholding ────────────────────────────────────────
            if (! Schema::hasColumn('invoices', 'pph23_withheld')) {
                $table->boolean('pph23_withheld')->default(false);
            }
            if (! Schema::hasColumn('invoices', 'pph23_rate')) {
                $table->decimal('pph23_rate', 8, 2)->default(0);
            }
            if (! Schema::hasColumn('invoices', 'pph23_amount')) {
                $table->decimal('pph23_amount', 15, 2)->default(0);
            }
            if (! Schema::hasColumn('invoices', 'pph23_bukti_potong_number')) {
                $table->string('pph23_bukti_potong_number')->nullable();
            }
        });

        // ── Bank reconciliation ───────────────────────────────────────────
        if (! Schema::hasTable('bank_statement_lines')) {
            Schema::create('bank_statement_lines', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('finance_account_id')->index();
                $table->date('date');
                $table->string('description')->nullable();
                $table->decimal('amount', 15, 2)->default(0); // signed: + inflow, − outflow
                $table->string('reference')->nullable();
                $table->unsignedBigInteger('matched_transaction_id')->nullable()->index();
                $table->timestamp('reconciled_at')->nullable();
                $table->string('import_batch')->nullable()->index();
                $table->timestamps();
            });
        }

        // ── Per-unit depreciation persistence ─────────────────────────────
        Schema::table('product_units', function (Blueprint $table) {
            if (! Schema::hasColumn('product_units', 'accumulated_depreciation')) {
                $table->decimal('accumulated_depreciation', 15, 2)->default(0);
            }
        });

        if (! Schema::hasTable('depreciation_run_items')) {
            Schema::create('depreciation_run_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('depreciation_run_id')->index();
                $table->unsignedBigInteger('product_unit_id')->index();
                $table->decimal('amount', 15, 2)->default(0);
                $table->decimal('accumulated_after', 15, 2)->default(0);
                $table->timestamps();
            });
        }

        // ── Seed base currency + ensure prepaid-tax account ───────────────
        if (! Currency::query()->exists()) {
            Currency::create([
                'code' => 'IDR',
                'name' => 'Rupiah Indonesia',
                'symbol' => 'Rp',
                'exchange_rate' => 1,
                'is_base' => true,
                'is_active' => true,
            ]);
            Setting::set('base_currency', 'IDR');
        }

        if (! Account::where('code', '1-1500')->exists()) {
            $parentId = Account::where('code', '1-1000')->value('id');
            Account::create([
                'code' => '1-1500',
                'name' => 'PPh 23 Dibayar Dimuka',
                'type' => 'asset',
                'subtype' => 'current_asset',
                'is_sub_account' => true,
                'parent_id' => $parentId,
                'is_active' => true,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('depreciation_run_items');
        Schema::dropIfExists('bank_statement_lines');

        Schema::table('product_units', function (Blueprint $table) {
            if (Schema::hasColumn('product_units', 'accumulated_depreciation')) {
                $table->dropColumn('accumulated_depreciation');
            }
        });

        Schema::table('invoices', function (Blueprint $table) {
            foreach (['currency', 'exchange_rate', 'pph23_withheld', 'pph23_rate', 'pph23_amount', 'pph23_bukti_potong_number'] as $col) {
                if (Schema::hasColumn('invoices', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        Schema::table('finance_transactions', function (Blueprint $table) {
            foreach (['currency', 'exchange_rate', 'reconciled_at'] as $col) {
                if (Schema::hasColumn('finance_transactions', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        Schema::dropIfExists('currencies');
    }
};

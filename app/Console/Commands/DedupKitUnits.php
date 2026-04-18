<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\ProductUnit;
use App\Models\UnitKit;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DedupKitUnits extends Command
{
    protected $signature = 'kits:dedupe {--dry-run : Only report what would change without persisting}';

    protected $description = 'Merge ghost KIT-XXXX ProductUnits created as duplicates into their user-entered twins and remove orphaned ghost units.';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');

        $category = Category::where('slug', Category::SYSTEM_SLUG_ACCESSORIES_KITS)->first();

        if (! $category) {
            $this->info('No accessories-kits category found in this tenant. Nothing to dedupe.');
            return self::SUCCESS;
        }

        $ghostUnits = ProductUnit::whereHas('product', fn ($q) => $q->where('category_id', $category->id))
            ->where('serial_number', 'like', 'KIT-%')
            ->get();

        if ($ghostUnits->isEmpty()) {
            $this->info('No ghost KIT-* units found. Nothing to dedupe.');
            return self::SUCCESS;
        }

        $this->info("Found {$ghostUnits->count()} ghost KIT-* unit(s). Starting dedupe" . ($dryRun ? ' (dry-run)' : '') . '.');

        $merged = 0;
        $deleted = 0;

        foreach ($ghostUnits as $ghost) {
            $kitsOnGhost = UnitKit::where('linked_unit_id', $ghost->id)->get();

            $realUnitId = null;

            foreach ($kitsOnGhost as $kit) {
                if (blank($kit->serial_number) || str_starts_with($kit->serial_number, 'KIT-')) {
                    continue;
                }

                $realUnit = ProductUnit::where('serial_number', $kit->serial_number)
                    ->where('id', '!=', $ghost->id)
                    ->first();

                if ($realUnit) {
                    $realUnitId = $realUnit->id;
                    break;
                }
            }

            if ($realUnitId === null) {
                $twinKit = UnitKit::where('linked_unit_id', $ghost->id)
                    ->whereNotNull('serial_number')
                    ->where('serial_number', 'not like', 'KIT-%')
                    ->first();

                if ($twinKit) {
                    $this->line("  Ghost {$ghost->serial_number} (#{$ghost->id}): kept (no real twin found for serial {$twinKit->serial_number})");
                    continue;
                }
            }

            if ($realUnitId !== null) {
                if (! $dryRun) {
                    DB::transaction(function () use ($ghost, $realUnitId, &$merged) {
                        UnitKit::where('linked_unit_id', $ghost->id)
                            ->update(['linked_unit_id' => $realUnitId]);
                        $merged++;
                    });
                } else {
                    $merged++;
                }
                $this->line("  Ghost {$ghost->serial_number} (#{$ghost->id}) → merged kits to unit #{$realUnitId}");
            }

            $stillReferenced = UnitKit::where('linked_unit_id', $ghost->id)->exists()
                || \App\Models\RentalItem::where('product_unit_id', $ghost->id)->exists();

            if (! $stillReferenced) {
                if (! $dryRun) {
                    $ghost->delete();
                }
                $deleted++;
                $this->line("  Ghost {$ghost->serial_number} (#{$ghost->id}) → deleted");
            }
        }

        $this->info("Dedupe summary: merged={$merged}, deleted={$deleted}" . ($dryRun ? ' (dry-run, no changes persisted)' : ''));

        return self::SUCCESS;
    }
}

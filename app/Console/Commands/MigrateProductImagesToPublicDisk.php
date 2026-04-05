<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class MigrateProductImagesToPublicDisk extends Command
{
    protected $signature = 'products:migrate-images {--dry-run : Show what would be moved without actually moving}';

    protected $description = 'Move product images from local (private) disk to public disk';

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');

        $products = Product::whereNotNull('image')->get(['id', 'name', 'image']);

        $moved = 0;
        $alreadyPublic = 0;
        $missing = 0;

        foreach ($products as $product) {
            $path = $product->image;

            $existsOnPublic = Storage::disk('public')->exists($path);
            $existsOnLocal = Storage::disk('local')->exists($path);

            if ($existsOnPublic) {
                $alreadyPublic++;
                continue;
            }

            if (!$existsOnLocal) {
                $this->warn("Missing on both disks: [{$product->id}] {$product->name} → {$path}");
                $missing++;
                continue;
            }

            // File is on local (private) disk, needs to move to public
            if ($dryRun) {
                $this->line("Would move: [{$product->id}] {$product->name} → {$path}");
            } else {
                $contents = Storage::disk('local')->get($path);
                Storage::disk('public')->put($path, $contents);
                $this->info("Moved: [{$product->id}] {$product->name} → {$path}");
            }

            $moved++;
        }

        $this->newLine();
        $this->table(
            ['Status', 'Count'],
            [
                [$dryRun ? 'Would move' : 'Moved', $moved],
                ['Already on public disk', $alreadyPublic],
                ['Missing on both disks', $missing],
            ]
        );

        if ($dryRun && $moved > 0) {
            $this->newLine();
            $this->comment('Run without --dry-run to actually move the files.');
        }

        return self::SUCCESS;
    }
}

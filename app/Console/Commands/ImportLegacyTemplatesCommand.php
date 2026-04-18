<?php

namespace App\Console\Commands;

use App\Models\Central\TenantTemplate;
use App\Models\Central\TenantTemplateBrand;
use App\Models\Central\TenantTemplateProduct;
use App\Models\Central\TenantTemplateProductCategory;
use App\Models\Central\TenantTemplateProductUnit;
use App\Models\TenantCategory;
use Database\Seeders\Tenant\TenantTemplateSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use ReflectionClass;

class ImportLegacyTemplatesCommand extends Command
{
    protected $signature = 'templates:import-legacy
                            {--fresh : Delete existing templates before import}';

    protected $description = 'One-off: migrate hardcoded PHP template seeders into central DB tables';

    public function handle(): int
    {
        $map = TenantTemplateSeeder::getLegacySeederMap();

        if ($this->option('fresh')) {
            if (! $this->confirm('Delete ALL existing tenant templates before re-importing?')) {
                $this->info('Aborted.');

                return self::SUCCESS;
            }

            DB::connection('central')->table('tenant_templates')->delete();
            $this->warn('Cleared existing tenant_templates.');
        }

        $imported = 0;
        $skipped = 0;

        foreach ($map as $categorySlug => $seederClass) {
            $category = TenantCategory::on('central')->where('slug', $categorySlug)->first();
            if (! $category) {
                $this->warn("  [skip] no tenant_category for slug '{$categorySlug}'");
                $skipped++;
                continue;
            }

            if ($category->template()->exists()) {
                $this->line("  [skip] template already exists for '{$categorySlug}'");
                $skipped++;
                continue;
            }

            [$categoriesData, $productsData] = $this->extractLegacyData($seederClass);

            DB::connection('central')->transaction(function () use ($category, $seederClass, $categoriesData, $productsData) {
                $template = TenantTemplate::create([
                    'tenant_category_id' => $category->id,
                    'name' => 'Template ' . $category->name,
                    'description' => "Imported from legacy seeder: {$seederClass}",
                    'is_active' => true,
                ]);

                $defaultBrand = TenantTemplateBrand::create([
                    'tenant_template_id' => $template->id,
                    'name' => 'Umum',
                    'slug' => 'umum',
                    'is_default' => true,
                ]);

                $categoryMap = [];
                foreach ($categoriesData as $cat) {
                    $slug = Str::slug($cat['name']);
                    $tmplCat = TenantTemplateProductCategory::create([
                        'tenant_template_id' => $template->id,
                        'name' => $cat['name'],
                        'slug' => $slug,
                        'description' => $cat['description'] ?? null,
                        'sort_order' => 0,
                    ]);
                    $categoryMap[$cat['name']] = $tmplCat->id;
                }

                foreach ($productsData as $prod) {
                    $categoryId = $categoryMap[$prod['category']] ?? null;
                    if (! $categoryId) {
                        continue;
                    }

                    $product = TenantTemplateProduct::create([
                        'tenant_template_id' => $template->id,
                        'tenant_template_product_category_id' => $categoryId,
                        'tenant_template_brand_id' => $defaultBrand->id,
                        'name' => $prod['name'],
                        'slug' => Str::slug($prod['name']),
                        'description' => $prod['description'] ?? null,
                        'daily_rate' => $prod['daily_rate'] ?? 0,
                        'buffer_time' => 0,
                        'is_visible_on_frontend' => true,
                    ]);

                    TenantTemplateProductUnit::create([
                        'tenant_template_product_id' => $product->id,
                        'serial_suffix' => '001',
                        'condition' => 'good',
                        'status' => 'available',
                    ]);
                }
            });

            $this->info("  [ok] imported template for '{$categorySlug}'");
            $imported++;
        }

        $this->newLine();
        $this->info("Done. Imported: {$imported}, skipped: {$skipped}.");

        return self::SUCCESS;
    }

    /**
     * @return array{0: array<int,array>, 1: array<int,array>}
     */
    protected function extractLegacyData(string $seederClass): array
    {
        $instance = new $seederClass;
        $reflection = new ReflectionClass($instance);

        $categoriesMethod = $reflection->getMethod('categories');
        $categoriesMethod->setAccessible(true);

        $productsMethod = $reflection->getMethod('products');
        $productsMethod->setAccessible(true);

        return [
            $categoriesMethod->invoke($instance),
            $productsMethod->invoke($instance),
        ];
    }
}

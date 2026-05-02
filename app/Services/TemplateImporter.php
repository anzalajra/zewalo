<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Central\TenantTemplate;
use App\Models\Central\TenantTemplateProduct;
use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\ProductVariation;
use App\Models\UnitKit;
use App\Services\Storage\TenantStorageService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TemplateImporter
{
    protected const IMAGE_RETRY_DELAYS_MS = [100, 300, 900];

    public function __construct(
        protected TenantStorageService $storage,
    ) {}

    /**
     * Import a central TenantTemplate into the currently-active tenant database.
     * Must be called within an initialized tenant context.
     */
    public function import(TenantTemplate $template, ?string $tenantId = null): void
    {
        $template->loadMissing([
            'brands',
            'productCategories',
            'products.units.kits',
            'products.variations',
        ]);

        DB::transaction(function () use ($template, $tenantId) {
            [$brandMap, $defaultBrandId] = $this->importBrands($template);
            $categoryMap = $this->importCategories($template);
            $productMap = $this->importProducts($template, $brandMap, $categoryMap, $defaultBrandId, $tenantId);
            $this->importUnitsAndVariations($template, $productMap);
        });
    }

    /**
     * @return array{0: array<int,int>, 1: int}  brandMap + defaultBrandId
     */
    protected function importBrands(TenantTemplate $template): array
    {
        $brandMap = [];
        $defaultBrandId = null;

        foreach ($template->brands as $tmplBrand) {
            $brand = Brand::firstOrCreate(
                ['slug' => Str::slug($tmplBrand->slug ?: $tmplBrand->name)],
                [
                    'name' => $tmplBrand->name,
                    'slug' => Str::slug($tmplBrand->slug ?: $tmplBrand->name),
                    'is_active' => true,
                ]
            );
            $brandMap[$tmplBrand->id] = $brand->id;

            if ($tmplBrand->is_default) {
                $defaultBrandId = $brand->id;
            }
        }

        if ($defaultBrandId === null) {
            $fallback = Brand::query()->where('is_active', true)->first()
                ?? Brand::firstOrCreate(
                    ['slug' => 'umum'],
                    ['name' => 'Umum', 'slug' => 'umum', 'is_active' => true]
                );
            $defaultBrandId = $fallback->id;
        }

        return [$brandMap, $defaultBrandId];
    }

    /**
     * @return array<int,int>  templateCategoryId → tenantCategoryId
     */
    protected function importCategories(TenantTemplate $template): array
    {
        $categoryMap = [];

        foreach ($template->productCategories as $tmplCat) {
            $slug = Str::slug($tmplCat->slug ?: $tmplCat->name);
            $category = Category::firstOrCreate(
                ['slug' => $slug],
                [
                    'name' => $tmplCat->name,
                    'slug' => $slug,
                    'description' => $tmplCat->description,
                    'is_active' => true,
                    'sort_order' => $tmplCat->sort_order ?? 0,
                ]
            );
            $categoryMap[$tmplCat->id] = $category->id;
        }

        return $categoryMap;
    }

    /**
     * @return array<int,int>  templateProductId → tenantProductId
     */
    protected function importProducts(
        TenantTemplate $template,
        array $brandMap,
        array $categoryMap,
        int $defaultBrandId,
        ?string $tenantId
    ): array {
        $productMap = [];

        foreach ($template->products as $tmplProduct) {
            $categoryId = $categoryMap[$tmplProduct->tenant_template_product_category_id] ?? null;
            if (! $categoryId) {
                continue;
            }

            $brandId = $tmplProduct->tenant_template_brand_id
                ? ($brandMap[$tmplProduct->tenant_template_brand_id] ?? $defaultBrandId)
                : $defaultBrandId;

            $slug = Str::slug($tmplProduct->slug ?: $tmplProduct->name);

            $imageRelativePath = $this->copyImageIfPresent($tmplProduct, $tenantId, $slug);

            $product = Product::firstOrCreate(
                ['slug' => $slug],
                [
                    'name' => $tmplProduct->name,
                    'slug' => $slug,
                    'description' => $tmplProduct->description,
                    'daily_rate' => $tmplProduct->daily_rate,
                    'buffer_time' => $tmplProduct->buffer_time ?? 0,
                    'category_id' => $categoryId,
                    'brand_id' => $brandId,
                    'image' => $imageRelativePath,
                    'is_active' => true,
                    'is_visible_on_frontend' => (bool) $tmplProduct->is_visible_on_frontend,
                ]
            );

            $productMap[$tmplProduct->id] = $product->id;
        }

        return $productMap;
    }

    protected function importUnitsAndVariations(TenantTemplate $template, array $productMap): void
    {
        foreach ($template->products as $tmplProduct) {
            $tenantProductId = $productMap[$tmplProduct->id] ?? null;
            if (! $tenantProductId) {
                continue;
            }

            $productSlugUpper = strtoupper(Str::slug($tmplProduct->slug ?: $tmplProduct->name));

            foreach ($tmplProduct->variations as $variation) {
                ProductVariation::create([
                    'product_id' => $tenantProductId,
                    'name' => $variation->name,
                    'daily_rate' => $variation->daily_rate,
                ]);
            }

            foreach ($tmplProduct->units as $tmplUnit) {
                $suffix = trim($tmplUnit->serial_suffix) ?: '001';
                $serial = "TMPL-{$productSlugUpper}-{$suffix}";

                $unit = ProductUnit::firstOrCreate(
                    ['serial_number' => $serial],
                    [
                        'product_id' => $tenantProductId,
                        'serial_number' => $serial,
                        'condition' => $tmplUnit->condition,
                        'status' => $tmplUnit->status,
                    ]
                );

                $this->importKitsForUnit($tmplUnit, $unit, $productSlugUpper);
            }
        }
    }

    protected function importKitsForUnit($tmplUnit, ProductUnit $unit, string $productSlugUpper): void
    {
        foreach ($tmplUnit->kits as $tmplKit) {
            $trackBySerial = (bool) $tmplKit->track_by_serial;
            $kitSerial = null;
            $linkedUnitId = null;

            if ($trackBySerial) {
                $kitSuffix = trim((string) $tmplKit->serial_suffix);
                if ($kitSuffix !== '') {
                    $kitSerial = "TMPL-{$productSlugUpper}-{$kitSuffix}";
                    $linked = ProductUnit::where('serial_number', $kitSerial)->first();
                    if ($linked) {
                        $linkedUnitId = $linked->id;
                    }
                }
            }

            UnitKit::create([
                'unit_id' => $unit->id,
                'linked_unit_id' => $linkedUnitId,
                'track_by_serial' => $trackBySerial,
                'name' => $tmplKit->name,
                'serial_number' => $kitSerial,
                'condition' => $tmplKit->condition,
                'notes' => $tmplKit->notes,
            ]);
        }
    }

    /**
     * Copy central template image (absolute R2 path) into tenant R2 prefix.
     * Returns relative path to store in Product.image (e.g. `products/xxx.jpg`)
     * or null if no image / copy failed.
     */
    protected function copyImageIfPresent(TenantTemplateProduct $tmplProduct, ?string $tenantId, string $slug): ?string
    {
        $centralPath = $tmplProduct->image_path;
        if (! $centralPath) {
            return null;
        }

        $extension = pathinfo($centralPath, PATHINFO_EXTENSION) ?: 'jpg';
        $targetRelative = 'products/' . $slug . '-' . Str::random(8) . '.' . $extension;

        foreach (self::IMAGE_RETRY_DELAYS_MS as $i => $delay) {
            try {
                if (! Storage::disk('r2')->exists($centralPath)) {
                    Log::warning("TemplateImporter: central image missing [{$centralPath}] for product [{$tmplProduct->id}]");
                    $this->reportImageFailure($tmplProduct, $centralPath, 'Central image file not found on R2');

                    return null;
                }

                $ok = $this->storage->copyFromCentral($centralPath, $targetRelative, $tenantId);
                if ($ok) {
                    return $targetRelative;
                }
            } catch (\Throwable $e) {
                Log::warning("TemplateImporter: image copy attempt " . ($i + 1) . " failed [{$centralPath}]: {$e->getMessage()}");

                if ($i === array_key_last(self::IMAGE_RETRY_DELAYS_MS)) {
                    $this->reportImageFailure($tmplProduct, $centralPath, $e->getMessage());

                    return null;
                }
            }

            usleep($delay * 1000);
        }

        return null;
    }

    protected function reportImageFailure(TenantTemplateProduct $tmplProduct, string $centralPath, string $reason): void
    {
        try {
            \App\Services\TenantIssueReporter::report(
                code: 'TEMPLATE_IMAGE_COPY_FAILED',
                title: 'Gambar template produk gagal di-copy ke tenant',
                message: "Gagal copy image untuk template product '{$tmplProduct->name}': {$reason}",
                area: 'template_importer',
                severity: 'warning',
                context: [
                    'template_product_id' => $tmplProduct->id,
                    'central_path' => $centralPath,
                    'reason' => $reason,
                ],
            );
        } catch (\Throwable $e) {
            // Reporter failures should not break import.
            Log::error("TemplateImporter: failed to report image copy failure: {$e->getMessage()}");
        }
    }
}

<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\UnitKit;
use Illuminate\Support\Str;

class KitUnitLinker
{
    public const SYSTEM_CATEGORY_SLUG = 'accessories-kits';
    public const SYSTEM_CATEGORY_NAME = 'Accessories & Kits';
    public const SYSTEM_BRAND_SLUG = 'generic';
    public const SYSTEM_BRAND_NAME = 'Generic';

    public function resolveLinkedUnitId(UnitKit $kit): ?int
    {
        if (! $kit->track_by_serial) {
            return null;
        }

        $name = trim((string) $kit->name);
        $serial = trim((string) $kit->serial_number);

        if ($name === '' || $serial === '') {
            return $kit->linked_unit_id;
        }

        $existing = ProductUnit::where('serial_number', $serial)->first();
        if ($existing) {
            $this->backfillGhostLinks($serial, $existing->id);
            return $existing->id;
        }

        $category = Category::firstOrCreate(
            ['slug' => self::SYSTEM_CATEGORY_SLUG],
            [
                'name' => self::SYSTEM_CATEGORY_NAME,
                'is_active' => true,
                'is_visible_on_storefront' => false,
            ]
        );

        $brand = Brand::firstOrCreate(
            ['slug' => self::SYSTEM_BRAND_SLUG],
            ['name' => self::SYSTEM_BRAND_NAME]
        );

        $product = Product::where('name', $name)
            ->where('category_id', $category->id)
            ->first();

        if (! $product) {
            $slug = Str::slug($name);
            if (Product::where('slug', $slug)->exists()) {
                $slug .= '-' . Str::lower(Str::random(4));
            }

            $product = Product::create([
                'name' => $name,
                'slug' => $slug,
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'daily_rate' => 0,
                'is_active' => true,
                'is_visible_on_frontend' => false,
            ]);
        }

        $unit = ProductUnit::create([
            'product_id' => $product->id,
            'serial_number' => $serial,
            'status' => ProductUnit::STATUS_AVAILABLE,
            'condition' => $kit->condition ?? 'good',
        ]);

        $this->backfillGhostLinks($serial, $unit->id);

        return $unit->id;
    }

    protected function backfillGhostLinks(string $serial, int $unitId): void
    {
        UnitKit::where('serial_number', $serial)
            ->whereNull('linked_unit_id')
            ->update(['linked_unit_id' => $unitId]);
    }
}

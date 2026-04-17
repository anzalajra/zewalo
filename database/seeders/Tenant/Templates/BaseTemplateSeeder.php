<?php

namespace Database\Seeders\Tenant\Templates;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductUnit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

abstract class BaseTemplateSeeder
{
    /**
     * Return array of categories to create.
     * Each: ['name' => '...', 'description' => '...']
     */
    abstract protected function categories(): array;

    /**
     * Return array of products to create.
     * Each: ['name' => '...', 'description' => '...', 'daily_rate' => 0, 'category' => 'Category Name']
     */
    abstract protected function products(): array;

    public function run(): void
    {
        DB::transaction(function () {
            // Products require a non-null brand_id (see create_products_table migration).
            // Reuse any existing active brand, otherwise create a neutral "Umum" brand.
            $brand = Brand::query()->where('is_active', true)->first()
                ?? Brand::firstOrCreate(
                    ['slug' => 'umum'],
                    ['name' => 'Umum', 'slug' => 'umum', 'is_active' => true]
                );

            $categoryMap = [];
            foreach ($this->categories() as $cat) {
                $category = Category::firstOrCreate(
                    ['slug' => Str::slug($cat['name'])],
                    [
                        'name' => $cat['name'],
                        'slug' => Str::slug($cat['name']),
                        'description' => $cat['description'] ?? null,
                        'is_active' => true,
                    ]
                );
                $categoryMap[$cat['name']] = $category->id;
            }

            foreach ($this->products() as $prod) {
                $categoryId = $categoryMap[$prod['category']] ?? null;

                $product = Product::firstOrCreate(
                    ['slug' => Str::slug($prod['name'])],
                    [
                        'name' => $prod['name'],
                        'slug' => Str::slug($prod['name']),
                        'description' => $prod['description'],
                        'daily_rate' => $prod['daily_rate'],
                        'category_id' => $categoryId,
                        'brand_id' => $brand->id,
                        'is_active' => true,
                        'is_visible_on_frontend' => true,
                        'buffer_time' => 0,
                    ]
                );

                ProductUnit::firstOrCreate(
                    ['serial_number' => 'TMPL-' . strtoupper(Str::slug($prod['name'])) . '-001'],
                    [
                        'product_id' => $product->id,
                        'serial_number' => 'TMPL-' . strtoupper(Str::slug($prod['name'])) . '-001',
                        'condition' => 'good',
                        'status' => 'available',
                    ]
                );
            }
        });
    }
}

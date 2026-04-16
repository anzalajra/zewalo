<?php

namespace Database\Seeders\Tenant\Templates;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductUnit;
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
        // Create categories
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

        // Create products with units
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
                    'is_active' => true,
                    'is_visible_on_frontend' => true,
                    'buffer_time' => 0,
                ]
            );

            // Create one unit per product
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
    }
}

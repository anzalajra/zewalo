<?php

namespace Database\Seeders;

use App\Models\TenantCategory;
use Illuminate\Database\Seeder;

class TenantCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Fotografi & Videografi', 'slug' => 'photography', 'icon' => 'photo_camera', 'sort_order' => 1],
            ['name' => 'Kendaraan & Otomotif', 'slug' => 'automotive', 'icon' => 'directions_car', 'sort_order' => 2],
            ['name' => 'Peralatan Camping', 'slug' => 'camping', 'icon' => 'hiking', 'sort_order' => 3],
            ['name' => 'Elektronik & Gadget', 'slug' => 'electronics', 'icon' => 'devices', 'sort_order' => 4],
            ['name' => 'Peralatan Pernikahan', 'slug' => 'wedding', 'icon' => 'celebration', 'sort_order' => 5],
            ['name' => 'Peralatan Olahraga', 'slug' => 'sports', 'icon' => 'sports', 'sort_order' => 6],
            ['name' => 'Alat Musik & Sound System', 'slug' => 'music', 'icon' => 'speaker', 'sort_order' => 7],
            ['name' => 'Lainnya', 'slug' => 'other', 'icon' => 'category', 'sort_order' => 99],
        ];

        foreach ($categories as $category) {
            TenantCategory::firstOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }

        $this->command->info('Tenant categories seeded successfully.');
    }
}

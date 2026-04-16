<?php

namespace Database\Seeders\Tenant\Templates;

class PhotographyTemplateSeeder extends BaseTemplateSeeder
{
    protected function categories(): array
    {
        return [
            ['name' => 'Kamera', 'description' => 'Kamera DSLR dan Mirrorless'],
            ['name' => 'Lensa & Aksesoris', 'description' => 'Lensa, tripod, dan aksesoris fotografi'],
        ];
    }

    protected function products(): array
    {
        return [
            [
                'name' => 'Canon EOS R5 Body',
                'description' => 'Kamera mirrorless full-frame 45MP dengan video 8K. Cocok untuk foto dan video profesional.',
                'daily_rate' => 350000,
                'category' => 'Kamera',
            ],
            [
                'name' => 'Sony A7III Kit 28-70mm',
                'description' => 'Kamera mirrorless full-frame dengan lensa kit 28-70mm. Pilihan populer untuk berbagai kebutuhan.',
                'daily_rate' => 250000,
                'category' => 'Kamera',
            ],
            [
                'name' => 'DJI Ronin-S Gimbal Stabilizer',
                'description' => 'Gimbal stabilizer 3-axis untuk kamera DSLR/mirrorless. Maksimal beban 3.6kg.',
                'daily_rate' => 150000,
                'category' => 'Lensa & Aksesoris',
            ],
        ];
    }
}

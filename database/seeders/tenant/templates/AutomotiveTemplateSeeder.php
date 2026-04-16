<?php

namespace Database\Seeders\Tenant\Templates;

class AutomotiveTemplateSeeder extends BaseTemplateSeeder
{
    protected function categories(): array
    {
        return [
            ['name' => 'Mobil', 'description' => 'Rental mobil untuk berbagai kebutuhan'],
            ['name' => 'Motor', 'description' => 'Rental sepeda motor'],
        ];
    }

    protected function products(): array
    {
        return [
            [
                'name' => 'Toyota Avanza 2023',
                'description' => 'MPV 7-seater, transmisi automatic. Cocok untuk perjalanan keluarga dan wisata.',
                'daily_rate' => 400000,
                'category' => 'Mobil',
            ],
            [
                'name' => 'Honda Brio Satya 2023',
                'description' => 'City car irit bahan bakar, transmisi automatic. Ideal untuk mobilitas harian.',
                'daily_rate' => 250000,
                'category' => 'Mobil',
            ],
            [
                'name' => 'Honda PCX 160 2023',
                'description' => 'Skutik premium 160cc, nyaman untuk touring maupun harian.',
                'daily_rate' => 100000,
                'category' => 'Motor',
            ],
        ];
    }
}

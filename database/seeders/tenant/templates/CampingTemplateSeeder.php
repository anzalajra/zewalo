<?php

namespace Database\Seeders\Tenant\Templates;

class CampingTemplateSeeder extends BaseTemplateSeeder
{
    protected function categories(): array
    {
        return [
            ['name' => 'Tenda & Shelter', 'description' => 'Tenda camping dan shelter outdoor'],
            ['name' => 'Peralatan Camping', 'description' => 'Sleeping bag, kompor, dan perlengkapan lainnya'],
        ];
    }

    protected function products(): array
    {
        return [
            [
                'name' => 'Tenda Dome 4 Orang',
                'description' => 'Tenda dome kapasitas 4 orang, waterproof, mudah dipasang. Dilengkapi flysheet.',
                'daily_rate' => 75000,
                'category' => 'Tenda & Shelter',
            ],
            [
                'name' => 'Sleeping Bag Polar',
                'description' => 'Sleeping bag bahan polar, nyaman untuk suhu dingin hingga 5°C.',
                'daily_rate' => 25000,
                'category' => 'Peralatan Camping',
            ],
            [
                'name' => 'Kompor Portable & Set Masak',
                'description' => 'Kompor gas portable dengan set panci dan peralatan masak outdoor.',
                'daily_rate' => 35000,
                'category' => 'Peralatan Camping',
            ],
        ];
    }
}

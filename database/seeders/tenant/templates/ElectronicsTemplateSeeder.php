<?php

namespace Database\Seeders\Tenant\Templates;

class ElectronicsTemplateSeeder extends BaseTemplateSeeder
{
    protected function categories(): array
    {
        return [
            ['name' => 'Laptop & Komputer', 'description' => 'Rental laptop dan komputer'],
            ['name' => 'Proyektor & Display', 'description' => 'Proyektor, monitor, dan perangkat display'],
        ];
    }

    protected function products(): array
    {
        return [
            [
                'name' => 'MacBook Pro 14" M3',
                'description' => 'Laptop Apple MacBook Pro 14 inci, chip M3, RAM 16GB, SSD 512GB.',
                'daily_rate' => 200000,
                'category' => 'Laptop & Komputer',
            ],
            [
                'name' => 'iPad Pro 12.9" M2',
                'description' => 'Tablet Apple iPad Pro 12.9 inci dengan Apple Pencil dan keyboard case.',
                'daily_rate' => 150000,
                'category' => 'Laptop & Komputer',
            ],
            [
                'name' => 'Proyektor Epson EB-X51',
                'description' => 'Proyektor XGA 3800 lumens, cocok untuk presentasi dan meeting.',
                'daily_rate' => 100000,
                'category' => 'Proyektor & Display',
            ],
        ];
    }
}

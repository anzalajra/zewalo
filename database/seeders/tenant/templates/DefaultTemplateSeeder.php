<?php

namespace Database\Seeders\Tenant\Templates;

class DefaultTemplateSeeder extends BaseTemplateSeeder
{
    protected function categories(): array
    {
        return [
            ['name' => 'Umum', 'description' => 'Kategori umum untuk berbagai produk rental'],
        ];
    }

    protected function products(): array
    {
        return [
            [
                'name' => 'Produk Contoh A',
                'description' => 'Ini adalah produk contoh pertama. Silakan edit nama, deskripsi, dan harga sesuai kebutuhan Anda.',
                'daily_rate' => 100000,
                'category' => 'Umum',
            ],
            [
                'name' => 'Produk Contoh B',
                'description' => 'Ini adalah produk contoh kedua. Silakan edit nama, deskripsi, dan harga sesuai kebutuhan Anda.',
                'daily_rate' => 200000,
                'category' => 'Umum',
            ],
            [
                'name' => 'Produk Contoh C',
                'description' => 'Ini adalah produk contoh ketiga. Silakan edit nama, deskripsi, dan harga sesuai kebutuhan Anda.',
                'daily_rate' => 300000,
                'category' => 'Umum',
            ],
        ];
    }
}

<?php

namespace Database\Seeders\Tenant\Templates;

class WeddingTemplateSeeder extends BaseTemplateSeeder
{
    protected function categories(): array
    {
        return [
            ['name' => 'Dekorasi', 'description' => 'Dekorasi pelaminan dan venue'],
            ['name' => 'Perlengkapan Acara', 'description' => 'Tenda, kursi, meja, dan perlengkapan pesta'],
        ];
    }

    protected function products(): array
    {
        return [
            [
                'name' => 'Set Dekorasi Pelaminan Modern',
                'description' => 'Paket dekorasi pelaminan modern lengkap dengan backdrop, bunga artificial, dan lighting.',
                'daily_rate' => 2500000,
                'category' => 'Dekorasi',
            ],
            [
                'name' => 'Tenda Pesta 10x10m',
                'description' => 'Tenda pesta kapasitas 100 orang, termasuk pemasangan dan pembongkaran.',
                'daily_rate' => 3000000,
                'category' => 'Perlengkapan Acara',
            ],
            [
                'name' => 'Sound System Wedding 2000W',
                'description' => 'Paket sound system 2000 watt dengan 2 speaker, mixer, dan 4 wireless mic.',
                'daily_rate' => 1500000,
                'category' => 'Perlengkapan Acara',
            ],
        ];
    }
}

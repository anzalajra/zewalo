<?php

namespace Database\Seeders\Tenant\Templates;

class SportsTemplateSeeder extends BaseTemplateSeeder
{
    protected function categories(): array
    {
        return [
            ['name' => 'Olahraga Raket', 'description' => 'Peralatan badminton, tenis, dan olahraga raket lainnya'],
            ['name' => 'Olahraga Outdoor', 'description' => 'Sepeda, papan surfing, dan peralatan outdoor'],
        ];
    }

    protected function products(): array
    {
        return [
            [
                'name' => 'Set Badminton Lengkap',
                'description' => 'Paket 2 raket Yonex, shuttlecock 1 tube, net portable, dan tas.',
                'daily_rate' => 50000,
                'category' => 'Olahraga Raket',
            ],
            [
                'name' => 'Sepeda Gunung MTB 27.5"',
                'description' => 'Sepeda gunung 27.5 inci, 21-speed, frame alloy. Termasuk helm dan gembok.',
                'daily_rate' => 75000,
                'category' => 'Olahraga Outdoor',
            ],
            [
                'name' => 'Papan Surfing 6\'2"',
                'description' => 'Shortboard surfing 6\'2" untuk level intermediate. Termasuk leash dan wax.',
                'daily_rate' => 100000,
                'category' => 'Olahraga Outdoor',
            ],
        ];
    }
}

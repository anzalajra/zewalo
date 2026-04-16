<?php

namespace Database\Seeders\Tenant\Templates;

class MusicTemplateSeeder extends BaseTemplateSeeder
{
    protected function categories(): array
    {
        return [
            ['name' => 'Alat Musik', 'description' => 'Gitar, drum, keyboard, dan alat musik lainnya'],
            ['name' => 'Sound System', 'description' => 'Speaker, mixer, amplifier, dan peralatan audio'],
        ];
    }

    protected function products(): array
    {
        return [
            [
                'name' => 'Gitar Akustik Yamaha F310',
                'description' => 'Gitar akustik Yamaha F310, cocok untuk latihan dan pertunjukan kecil. Termasuk softcase.',
                'daily_rate' => 50000,
                'category' => 'Alat Musik',
            ],
            [
                'name' => 'Drum Set Pearl Export 5-Piece',
                'description' => 'Drum set 5 piece Pearl Export dengan cymbal set dan hardware lengkap.',
                'daily_rate' => 300000,
                'category' => 'Alat Musik',
            ],
            [
                'name' => 'Mixer Audio Yamaha MG16XU',
                'description' => 'Mixer 16 channel dengan USB audio interface dan efek SPX built-in.',
                'daily_rate' => 200000,
                'category' => 'Sound System',
            ],
        ];
    }
}

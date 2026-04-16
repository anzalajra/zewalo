<?php

namespace Database\Seeders\Tenant;

use Database\Seeders\Tenant\Templates\AutomotiveTemplateSeeder;
use Database\Seeders\Tenant\Templates\CampingTemplateSeeder;
use Database\Seeders\Tenant\Templates\DefaultTemplateSeeder;
use Database\Seeders\Tenant\Templates\ElectronicsTemplateSeeder;
use Database\Seeders\Tenant\Templates\MusicTemplateSeeder;
use Database\Seeders\Tenant\Templates\PhotographyTemplateSeeder;
use Database\Seeders\Tenant\Templates\SportsTemplateSeeder;
use Database\Seeders\Tenant\Templates\WeddingTemplateSeeder;

class TenantTemplateSeeder
{
    protected static array $seederMap = [
        'photography' => PhotographyTemplateSeeder::class,
        'automotive' => AutomotiveTemplateSeeder::class,
        'camping' => CampingTemplateSeeder::class,
        'electronics' => ElectronicsTemplateSeeder::class,
        'wedding' => WeddingTemplateSeeder::class,
        'sports' => SportsTemplateSeeder::class,
        'music' => MusicTemplateSeeder::class,
        'other' => DefaultTemplateSeeder::class,
    ];

    public static function resolveSeeder(string $categorySlug): string
    {
        return static::$seederMap[$categorySlug] ?? DefaultTemplateSeeder::class;
    }

    public function run(string $categorySlug): void
    {
        $seederClass = static::resolveSeeder($categorySlug);
        (new $seederClass)->run();
    }
}

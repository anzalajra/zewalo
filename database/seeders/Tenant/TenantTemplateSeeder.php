<?php

namespace Database\Seeders\Tenant;

use App\Models\TenantCategory;
use App\Services\TemplateImporter;
use Illuminate\Support\Facades\Log;

class TenantTemplateSeeder
{
    /**
     * Legacy file-based seeder map — retained only for the one-off
     * `templates:import-legacy` command that seeds existing template data
     * into the new central database tables. Not used at runtime.
     *
     * @deprecated Template data now lives in the central DB. Edit via
     *             Central Admin → Tenant Management → Tenant Templates.
     */
    protected static array $legacySeederMap = [
        'photography' => \Database\Seeders\Tenant\Templates\PhotographyTemplateSeeder::class,
        'automotive' => \Database\Seeders\Tenant\Templates\AutomotiveTemplateSeeder::class,
        'camping' => \Database\Seeders\Tenant\Templates\CampingTemplateSeeder::class,
        'electronics' => \Database\Seeders\Tenant\Templates\ElectronicsTemplateSeeder::class,
        'wedding' => \Database\Seeders\Tenant\Templates\WeddingTemplateSeeder::class,
        'sports' => \Database\Seeders\Tenant\Templates\SportsTemplateSeeder::class,
        'music' => \Database\Seeders\Tenant\Templates\MusicTemplateSeeder::class,
        'other' => \Database\Seeders\Tenant\Templates\DefaultTemplateSeeder::class,
    ];

    public static function getLegacySeederMap(): array
    {
        return static::$legacySeederMap;
    }

    /**
     * Import the central template for a given tenant category into the
     * currently-active tenant database.
     *
     * Expects tenancy to already be initialized for the target tenant.
     * If no active template exists for the category, this is a no-op.
     */
    public function run(string $categorySlug, ?string $tenantId = null): void
    {
        $category = TenantCategory::on('central')
            ->where('slug', $categorySlug)
            ->first();

        if (! $category) {
            Log::info("TenantTemplateSeeder: no tenant_category found for slug [{$categorySlug}] — skipping import");

            return;
        }

        $template = $category->activeTemplate;

        if (! $template) {
            Log::info("TenantTemplateSeeder: no active template for category [{$categorySlug}] — skipping import");

            return;
        }

        app(TemplateImporter::class)->import($template, $tenantId);
    }
}

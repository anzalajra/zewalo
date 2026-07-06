<?php

namespace App\Filament\Resources\Brands\Schemas;

use App\Models\Brand;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class BrandForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (?string $state, callable $set, $get, ?Brand $record) {
                        if (! empty($get('slug')) && $record?->slug === $get('slug')) {
                            return;
                        }
                        $set('slug', self::generateUniqueSlug($state ?? '', $record?->getKey()));
                    }),

                TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->dehydrateStateUsing(fn (?string $state, $record) => $state
                        ? self::generateUniqueSlug($state, $record?->getKey())
                        : $state),

                // Tenant-scoped R2 upload (private bucket, auto tenant_{id}/ prefix).
                FileUpload::make('logo')
                    ->image()
                    ->tenantDirectory('brands'),

                TextInput::make('website')
                    ->url()
                    ->default(null)
                    ->placeholder('https://www.sony.com'),

                Toggle::make('is_active')
                    ->default(true),
            ]);
    }

    protected static function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        if ($base === '') {
            return $base;
        }

        $slug = $base;
        $i = 2;
        while (Brand::where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}

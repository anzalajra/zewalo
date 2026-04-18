<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (?string $state, callable $set, callable $get) {
                        if (filled($state) && blank($get('slug'))) {
                            $set('slug', Str::slug($state));
                        }
                    }),

                TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->helperText('Auto-generated from name. Edit only if needed.'),

                Textarea::make('description')
                    ->rows(3)
                    ->maxLength(1000),

                FileUpload::make('image')
                    ->image()
                    ->tenantDirectory('categories'),

                Toggle::make('is_active')
                    ->label('Active')
                    ->default(true)
                    ->helperText('Inactive categories are hidden everywhere.'),

                Toggle::make('is_visible_on_storefront')
                    ->label('Visible on Storefront')
                    ->default(true)
                    ->helperText('Uncheck to hide this category from the public catalog while keeping it usable in admin.'),
            ]);
    }
}

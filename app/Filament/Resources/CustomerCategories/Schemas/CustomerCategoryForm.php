<?php

namespace App\Filament\Resources\CustomerCategories\Schemas;

use App\Models\CustomerCategory;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CustomerCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (?string $state, callable $set) {
                        $set('slug', Str::slug($state ?? ''));
                    }),

                TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),

                Select::make('parent_id')
                    ->label('Parent Category')
                    ->options(function (?CustomerCategory $record) {
                        $query = CustomerCategory::query();
                        if ($record) {
                            // Exclude self and own children to prevent cycles
                            $excludeIds = $record->children()->pluck('id')->push($record->id)->toArray();
                            $query->whereNotIn('id', $excludeIds);
                        }

                        return $query->pluck('name', 'id');
                    })
                    ->searchable()
                    ->nullable()
                    ->helperText('Leave empty for a top-level category.'),

                Textarea::make('description')
                    ->label('Description')
                    ->rows(2)
                    ->maxLength(500)
                    ->helperText('Shown on the registration card.'),

                TextInput::make('icon')
                    ->label('Icon (SVG or icon name)')
                    ->maxLength(2000)
                    ->helperText('Paste an SVG string or an icon class name for the registration card.'),

                TextInput::make('sort_order')
                    ->label('Sort Order')
                    ->numeric()
                    ->default(0)
                    ->minValue(0),

                ColorPicker::make('badge_color')
                    ->label('Badge Color')
                    ->nullable(),

                TextInput::make('discount_percentage')
                    ->label('Discount Percentage')
                    ->numeric()
                    ->default(0)
                    ->minValue(0)
                    ->maxValue(100)
                    ->suffix('%')
                    ->helperText('This discount will be applied to all rentals for customers in this category.'),

                TagsInput::make('benefits')
                    ->label('Benefits')
                    ->helperText('List the benefits for this category (press Enter to add).'),

                CheckboxList::make('documentTypes')
                    ->relationship('documentTypes', 'name')
                    ->label('Required Documents')
                    ->columns(2)
                    ->columnSpanFull(),

                Toggle::make('is_active')
                    ->default(true),
            ]);
    }
}

<?php

namespace App\Filament\Central\Resources\TenantTemplateResource\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProductCategoriesRelationManager extends RelationManager
{
    protected static string $relationship = 'productCategories';

    protected static ?string $title = 'Product Categories';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set, $record) => $record === null ? $set('slug', Str::slug($state)) : null),

                TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->alphaDash(),

                Textarea::make('description')
                    ->maxLength(1000)
                    ->rows(3)
                    ->columnSpanFull(),

                TextInput::make('sort_order')
                    ->numeric()
                    ->default(0),
            ])
            ->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('slug')->toggleable(),
                TextColumn::make('products_count')
                    ->counts('products')
                    ->label('Products')
                    ->badge(),
                TextColumn::make('sort_order')->sortable(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

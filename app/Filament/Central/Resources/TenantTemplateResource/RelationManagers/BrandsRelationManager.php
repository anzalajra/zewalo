<?php

namespace App\Filament\Central\Resources\TenantTemplateResource\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class BrandsRelationManager extends RelationManager
{
    protected static string $relationship = 'brands';

    protected static ?string $title = 'Brands';

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

                Toggle::make('is_default')
                    ->label('Default brand (fallback jika produk tidak punya brand)')
                    ->default(false)
                    ->helperText('Satu template idealnya punya satu default brand. Kalau tidak ada, importer akan pakai "Umum".'),
            ])
            ->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('slug')->toggleable(),
                IconColumn::make('is_default')->boolean()->label('Default'),
                TextColumn::make('products_count')
                    ->counts('products')
                    ->label('Products')
                    ->badge(),
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

<?php

namespace App\Filament\Central\Resources;

use App\Filament\Central\Resources\TenantTemplateResource\Pages;
use App\Filament\Central\Resources\TenantTemplateResource\RelationManagers;
use App\Models\Central\TenantTemplate;
use App\Models\TenantCategory;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class TenantTemplateResource extends Resource
{
    protected static ?string $model = TenantTemplate::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string|UnitEnum|null $navigationGroup = null;

    protected static ?int $navigationSort = 6;

    public static function getNavigationGroup(): ?string
    {
        return __('admin.nav.tenant_management');
    }

    public static function getNavigationLabel(): string
    {
        return 'Tenant Templates';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Template Information')
                    ->description('Template data akan di-import otomatis saat tenant baru menyelesaikan Setup Wizard step terakhir.')
                    ->schema([
                        Select::make('tenant_category_id')
                            ->label('Tenant Category')
                            ->options(fn () => TenantCategory::query()
                                ->orderBy('sort_order')
                                ->pluck('name', 'id'))
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->disabledOn('edit')
                            ->helperText('Satu kategori hanya boleh punya satu template. Tidak bisa diubah setelah dibuat.'),

                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Template Fotografi Lengkap'),

                        Textarea::make('description')
                            ->maxLength(1000)
                            ->rows(3)
                            ->columnSpanFull(),

                        Toggle::make('is_active')
                            ->default(true)
                            ->helperText('Kalau dimatikan, Setup Wizard akan skip import untuk kategori ini.'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tenantCategory.name')
                    ->label('Category')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('brands_count')
                    ->counts('brands')
                    ->label('Brands')
                    ->badge(),
                Tables\Columns\TextColumn::make('product_categories_count')
                    ->counts('productCategories')
                    ->label('Product Categories')
                    ->badge(),
                Tables\Columns\TextColumn::make('products_count')
                    ->counts('products')
                    ->label('Products')
                    ->badge()
                    ->color('info'),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\BrandsRelationManager::class,
            RelationManagers\ProductCategoriesRelationManager::class,
            RelationManagers\ProductsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTenantTemplates::route('/'),
            'create' => Pages\CreateTenantTemplate::route('/create'),
            'edit' => Pages\EditTenantTemplate::route('/{record}/edit'),
        ];
    }
}

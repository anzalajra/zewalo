<?php

namespace App\Filament\Resources\Warehouses;

use App\Enums\TenantFeature;
use App\Filament\Concerns\ChecksTenantFeature;
use App\Filament\Resources\Warehouses\Pages\CreateWarehouse;
use App\Filament\Resources\Warehouses\Pages\EditWarehouse;
use App\Filament\Resources\Warehouses\Pages\ListWarehouses;
use App\Filament\Resources\Warehouses\Schemas\WarehouseForm;
use App\Filament\Resources\Warehouses\Tables\WarehousesTable;
use App\Models\Warehouse;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class WarehouseResource extends Resource
{
    use ChecksTenantFeature;

    protected static ?string $model = Warehouse::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-building-office';

    protected static string|UnitEnum|null $navigationGroup = null;

    protected static ?string $navigationLabel = null;

    public static function getNavigationGroup(): ?string
    {
        return __('admin.nav.inventory');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin.warehouse.nav_label');
    }

    protected static ?string $recordTitleAttribute = 'warehouse';

    public static function shouldRegisterNavigation(): bool
    {
        return static::tenantHasFeature(TenantFeature::InventoryWarehouse);
    }

    public static function canAccess(): bool
    {
        return static::tenantHasFeature(TenantFeature::InventoryWarehouse);
    }

    public static function form(Schema $schema): Schema
    {
        return WarehouseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WarehousesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\Warehouses\RelationManagers\ProductUnitsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWarehouses::route('/'),
            'create' => CreateWarehouse::route('/create'),
            'edit' => EditWarehouse::route('/{record}/edit'),
        ];
    }
}

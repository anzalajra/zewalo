<?php

namespace App\Filament\Central\Resources\TenantCategoryResource\Pages;

use App\Filament\Central\Resources\TenantCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTenantCategories extends ListRecords
{
    protected static string $resource = TenantCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

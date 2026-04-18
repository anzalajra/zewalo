<?php

namespace App\Filament\Central\Resources\TenantTemplateResource\Pages;

use App\Filament\Central\Resources\TenantTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTenantTemplates extends ListRecords
{
    protected static string $resource = TenantTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

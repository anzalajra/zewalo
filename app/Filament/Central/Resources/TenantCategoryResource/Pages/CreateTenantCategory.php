<?php

namespace App\Filament\Central\Resources\TenantCategoryResource\Pages;

use App\Filament\Central\Resources\TenantCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTenantCategory extends CreateRecord
{
    protected static string $resource = TenantCategoryResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

<?php

namespace App\Filament\Central\Resources\TenantTemplateResource\Pages;

use App\Filament\Central\Resources\TenantTemplateResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTenantTemplate extends CreateRecord
{
    protected static string $resource = TenantTemplateResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->record]);
    }
}

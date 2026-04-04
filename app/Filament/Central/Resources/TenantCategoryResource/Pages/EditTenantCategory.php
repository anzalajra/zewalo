<?php

namespace App\Filament\Central\Resources\TenantCategoryResource\Pages;

use App\Filament\Central\Resources\TenantCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTenantCategory extends EditRecord
{
    protected static string $resource = TenantCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

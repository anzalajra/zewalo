<?php

declare(strict_types=1);

namespace App\Filament\Central\Resources\TenantIssueResource\Pages;

use App\Filament\Central\Resources\TenantIssueResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTenantIssue extends EditRecord
{
    protected static string $resource = TenantIssueResource::class;

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

<?php

declare(strict_types=1);

namespace App\Filament\Central\Resources\TenantIssueResource\Pages;

use App\Filament\Central\Resources\TenantIssueResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTenantIssue extends ViewRecord
{
    protected static string $resource = TenantIssueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}

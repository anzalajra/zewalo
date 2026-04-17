<?php

declare(strict_types=1);

namespace App\Filament\Central\Resources\TenantIssueResource\Pages;

use App\Filament\Central\Resources\TenantIssueResource;
use Filament\Resources\Pages\ListRecords;

class ListTenantIssues extends ListRecords
{
    protected static string $resource = TenantIssueResource::class;
}

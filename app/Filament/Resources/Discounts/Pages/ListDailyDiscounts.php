<?php

namespace App\Filament\Resources\Discounts\Pages;

use App\Filament\Resources\Discounts\DailyDiscountResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\CreateAction;

class ListDailyDiscounts extends ListRecords
{
    protected static string $resource = DailyDiscountResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}

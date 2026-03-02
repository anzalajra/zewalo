<?php

namespace App\Filament\Resources\Discounts\Pages;

use App\Filament\Resources\Discounts\DailyDiscountResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDailyDiscount extends EditRecord
{
    protected static string $resource = DailyDiscountResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}

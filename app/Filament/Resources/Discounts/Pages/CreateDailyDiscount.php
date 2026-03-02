<?php

namespace App\Filament\Resources\Discounts\Pages;

use App\Filament\Resources\Discounts\DailyDiscountResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDailyDiscount extends CreateRecord
{
    protected static string $resource = DailyDiscountResource::class;
}

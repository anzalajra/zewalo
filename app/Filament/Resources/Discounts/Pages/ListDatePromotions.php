<?php

namespace App\Filament\Resources\Discounts\Pages;

use App\Filament\Resources\Discounts\DatePromotionResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\CreateAction;

class ListDatePromotions extends ListRecords
{
    protected static string $resource = DatePromotionResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}

<?php

namespace App\Filament\Resources\Discounts\Pages;

use App\Filament\Resources\Discounts\DatePromotionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDatePromotion extends EditRecord
{
    protected static string $resource = DatePromotionResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}

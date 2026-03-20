<?php

namespace App\Filament\Central\Resources\TranslationResource\Pages;

use App\Filament\Central\Resources\TranslationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTranslation extends EditRecord
{
    protected static string $resource = TranslationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

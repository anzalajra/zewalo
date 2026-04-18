<?php

namespace App\Filament\Resources\Rentals\Pages;

use App\Filament\Resources\Rentals\RentalResource;
use App\Filament\Resources\Rentals\Schemas\RentalForm;
use App\Models\Rental;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditRental extends EditRecord
{
    protected static string $resource = RentalResource::class;

    protected array $groupedItemsData = [];

    public function mount(int|string $record): void
    {
        parent::mount($record);

        // Check if rental can be edited
        if (!$this->record->canBeEdited()) {
            Notification::make()
                ->title('Cannot edit this rental')
                ->body('This rental is currently active and cannot be edited.')
                ->danger()
                ->send();

            $this->redirect(RentalResource::getUrl('index'));
        }
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Load rental items and group them for the form
        $rental = Rental::with('items.productUnit')->findOrFail($data['id']);
        $data['grouped_items'] = RentalForm::groupItemsForForm($rental->items);
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Extract grouped_items before saving (not a DB column)
        $this->groupedItemsData = $data['grouped_items'] ?? [];
        unset($data['grouped_items']);
        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // Use saveQuietly to prevent Rental::saved → refreshUnitStatuses from firing here.
        // syncRentalItems() runs immediately after in afterSave() and performs a single
        // batch refresh of all unit statuses, so the per-save refresh is redundant and
        // causes a memory-exhausting cascade when combined with RentalItem events.
        $record->fill($data);
        $record->saveQuietly();
        return $record;
    }

    protected function afterSave(): void
    {
        // Sync rental items from grouped data (does its own batch unit-status refresh)
        RentalForm::syncRentalItems($this->record, $this->groupedItemsData);
        $this->record->refresh();
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->visible(fn () => $this->record->canBeDeleted()),
        ];
    }
}
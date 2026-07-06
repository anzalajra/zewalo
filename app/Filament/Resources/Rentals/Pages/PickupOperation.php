<?php

namespace App\Filament\Resources\Rentals\Pages;

use App\Filament\Concerns\CapturesDeliveryHandover;
use App\Filament\Resources\Rentals\RentalResource;
use App\Models\Delivery;
use App\Models\DeliveryItem;
use App\Models\Rental;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;

class PickupOperation extends Page implements HasTable
{
    use CapturesDeliveryHandover;
    use InteractsWithTable;
    use \Livewire\WithFileUploads;

    protected static string $resource = RentalResource::class;

    public ?Rental $rental = null;

    public ?Delivery $delivery = null;

    public function getView(): string
    {
        return 'filament.resources.rentals.pages.pickup-operation';
    }

    public function mount(int|string $record): void
    {
        $this->rental = Rental::with([
            'customer',
            'items.productUnit.product',
            'items.productUnit.kits',
            'items.rentalItemKits',
            'deliveries.items.rentalItem.productUnit.product',
            'deliveries.items.rentalItemKit.unitKit',
        ])->findOrFail($record);

        // Update late status on mount
        $this->rental->checkAndUpdateLateStatus();
        $this->rental->refresh();

        // Always sync deliveries to ensure all kits are present
        $this->rental->createDeliveries();

        // Get delivery out
        $this->delivery = $this->rental->deliveries()
            ->with(['items.rentalItem.productUnit.product', 'items.rentalItemKit.unitKit'])
            ->where('type', Delivery::TYPE_OUT)
            ->first();

        if (! in_array($this->rental->status, [Rental::STATUS_CONFIRMED, Rental::STATUS_LATE_PICKUP])) {
            Notification::make()
                ->title('Cannot pickup this rental')
                ->body('This rental is not in confirmed or late pickup status.')
                ->danger()
                ->send();

            $this->redirect(RentalResource::getUrl('index'));
        }
    }

    public function getTitle(): string|Htmlable
    {
        return 'Pickup Operation - '.$this->rental->rental_code;
    }

    public function getAvailabilityStatus(): array
    {
        $conflicts = $this->rental->checkAvailability();

        $unavailableUnits = [];
        foreach ($this->rental->items as $item) {
            if ($item->productUnit) {
                // Ensure we have the latest status
                $item->productUnit->refresh();

                // 1. Check direct unit status
                if (in_array($item->productUnit->status, [\App\Models\ProductUnit::STATUS_RENTED, \App\Models\ProductUnit::STATUS_MAINTENANCE])) {
                    $unavailableUnits[] = $item->productUnit;
                }

                // 2. Check Components (if this is a Kit)
                $componentIds = $item->productUnit->kits()
                    ->whereNotNull('linked_unit_id')
                    ->pluck('linked_unit_id')
                    ->toArray();

                if (! empty($componentIds)) {
                    $unavailableComponents = \App\Models\ProductUnit::whereIn('id', $componentIds)
                        ->whereIn('status', [\App\Models\ProductUnit::STATUS_RENTED, \App\Models\ProductUnit::STATUS_MAINTENANCE])
                        ->get();

                    foreach ($unavailableComponents as $comp) {
                        // Avoid duplicates
                        if (! collect($unavailableUnits)->contains('id', $comp->id)) {
                            $unavailableUnits[] = $comp;
                        }
                    }
                }

                // 3. Check Parent Kits (if this is a Component)
                $parentIds = \App\Models\UnitKit::where('linked_unit_id', $item->productUnit->id)
                    ->pluck('unit_id')
                    ->toArray();

                if (! empty($parentIds)) {
                    $unavailableParents = \App\Models\ProductUnit::whereIn('id', $parentIds)
                        ->whereIn('status', [\App\Models\ProductUnit::STATUS_RENTED, \App\Models\ProductUnit::STATUS_MAINTENANCE])
                        ->get();

                    foreach ($unavailableParents as $parent) {
                        // Avoid duplicates
                        if (! collect($unavailableUnits)->contains('id', $parent->id)) {
                            $unavailableUnits[] = $parent;
                        }
                    }
                }
            }
        }

        return [
            'available' => empty($conflicts) && empty($unavailableUnits),
            'conflicts' => $conflicts,
            'unavailable_units' => $unavailableUnits,
        ];
    }

    public function getMarkAllCheckedAction(): Action
    {
        return Action::make('markAllChecked')
            ->label('Mark All as Checked')
            ->icon('heroicon-o-check-circle')
            ->color('warning')
            ->disabled(function () {
                $items = $this->delivery->items;
                if ($items->isEmpty()) {
                    return true;
                }

                // Check if ALL items are unavailable
                $allUnavailable = true;
                foreach ($items as $item) {
                    $unit = $item->rentalItem->productUnit;
                    // Check if unit is available
                    if (! in_array($unit->status, [\App\Models\ProductUnit::STATUS_RENTED, \App\Models\ProductUnit::STATUS_MAINTENANCE])) {
                        $allUnavailable = false;
                        break;
                    }
                }

                return $allUnavailable;
            })
            ->steps([
                \Filament\Schemas\Components\Wizard\Step::make('Verification')
                    ->description('Please verify that all tools have been checked properly and carefully.')
                    ->schema([
                        \Filament\Schemas\Components\Text::make('I confirm that I have physically checked all items and they are present.'),
                    ]),
                \Filament\Schemas\Components\Wizard\Step::make('Final Confirmation')
                    ->description('This will mark all items as checked.')
                    ->schema([
                        \Filament\Schemas\Components\Text::make('All items and kits will be marked as checked. You can still change the condition per item. Are you sure?'),
                    ]),
            ])
            ->action(function () {
                $items = $this->delivery->items;
                $updatedCount = 0;
                $skippedCount = 0;

                foreach ($items as $record) {
                    // Check availability before checking
                    $unit = $record->rentalItem->productUnit;
                    $unit->refresh(); // Ensure fresh status

                    if (in_array($unit->status, [\App\Models\ProductUnit::STATUS_RENTED, \App\Models\ProductUnit::STATUS_MAINTENANCE])) {
                        $skippedCount++;

                        continue;
                    }

                    // Use existing condition or default to 'good'
                    $condition = $record->condition ?? 'good';

                    $record->update([
                        'is_checked' => true,
                        'condition' => $condition,
                    ]);

                    // Logic from check_item action
                    $isMaintenance = in_array($condition, ['broken', 'lost']);

                    if ($isMaintenance) {
                        $updates = ['condition' => $condition];
                        // Add note about auto maintenance
                        $updates['notes'] = ($record->rentalItemKit ? $record->rentalItemKit->unitKit->notes : $record->rentalItem->productUnit->notes)."\n[AUTO] Marked as {$condition} during Pickup.";

                        if (! $record->rentalItemKit) {
                            $updates['status'] = \App\Models\ProductUnit::STATUS_MAINTENANCE;
                        }
                        // Update Unit Kit Master or Main Unit Master
                        if ($record->rentalItemKit) {
                            $record->rentalItemKit->unitKit->update($updates);
                        } else {
                            $record->rentalItem->productUnit->update($updates);
                        }
                    }
                    $updatedCount++;
                }

                $this->delivery->refresh();

                if ($updatedCount === 0 && $skippedCount > 0) {
                    Notification::make()
                        ->title('Cannot mark items as checked')
                        ->body('All items are currently unavailable (rented or maintenance).')
                        ->danger()
                        ->send();
                } elseif ($skippedCount > 0) {
                    Notification::make()
                        ->title('Some items were skipped')
                        ->body("Marked {$updatedCount} items as checked. {$skippedCount} items were skipped because they are unavailable.")
                        ->warning()
                        ->send();
                } else {
                    Notification::make()
                        ->title('All items marked as checked')
                        ->success()
                        ->send();
                }
            });
    }

    public function allItemsChecked(): bool
    {
        return $this->delivery->items->where('is_checked', false)->count() === 0;
    }

    // ─────────────────────────────────────────────────────────────
    //  Camera unit scanner (@zxing) — Fase 4. Additive: the Filament
    //  checklist table keeps working; this is an alternative fast input
    //  that decodes a unit/kit code (UnitCodeService) and checks the match.
    // ─────────────────────────────────────────────────────────────

    /** OUT-delivery items with the relations the scanner needs. */
    protected function getDeliveryItems()
    {
        return $this->delivery
            ? $this->delivery->items()
                ->with(['rentalItem.productUnit.product', 'rentalItemKit.unitKit'])
                ->get()
            : collect();
    }

    /** Human label for a delivery item (kit name, or product name). */
    public function itemLabel(DeliveryItem $item): string
    {
        if ($item->rentalItemKit) {
            return $item->rentalItemKit->unitKit->name ?? 'Kit';
        }

        return $item->rentalItem?->productUnit?->product?->name ?? 'Item';
    }

    /** A unit is unavailable when retired/maintenance or physically broken/lost. */
    public function isItemUnavailable(DeliveryItem $item): bool
    {
        $unit = $item->rentalItem?->productUnit;
        if (! $unit) {
            return false;
        }

        return in_array($unit->status, [\App\Models\ProductUnit::STATUS_MAINTENANCE, \App\Models\ProductUnit::STATUS_RETIRED], true)
            || in_array($unit->condition, ['broken', 'lost'], true);
    }

    /** Mark a checklist item checked (Good) without opening the editor. */
    public function quickCheck(int $id): void
    {
        $record = $this->delivery?->items()->find($id);
        if ($record && ! $record->is_checked) {
            $record->update(['is_checked' => true, 'condition' => $record->condition ?: 'good']);
            $this->delivery->refresh();
        }
    }

    /** Scanner checklist payload (skips auto-scan-with-parent kits + not-taken). */
    public function scannableList(): array
    {
        return $this->getDeliveryItems()
            ->reject(fn (DeliveryItem $it) => ($it->rentalItemKit && $it->rentalItemKit->unitKit?->auto_scan_with_parent) || $it->not_taken)
            ->map(function (DeliveryItem $it) {
                $isKit = $it->rentalItemKit !== null;

                return [
                    'id' => $it->id,
                    'name' => $this->itemLabel($it),
                    'serial' => $isKit
                        ? ($it->rentalItemKit->unitKit->serial_number ?? '')
                        : ($it->rentalItem?->productUnit?->serial_number ?? ''),
                    'type' => $isKit ? 'kit' : 'unit',
                    'checked' => (bool) $it->is_checked,
                ];
            })
            ->values()
            ->all();
    }

    /** Decode a scanned/typed code and check the matching item (+ cascade auto-scan kits). */
    public function scanByCode(string $raw, bool $cascade = true, bool $manual = false): array
    {
        $raw = trim($raw);
        if ($raw === '') {
            return ['status' => 'foreign'];
        }

        if ($manual) {
            $serial = $raw;
        } else {
            $serial = app(\App\Services\UnitCodeService::class)->decode($raw);
            if ($serial === null) {
                return ['status' => 'foreign'];
            }
        }

        $items = $this->getDeliveryItems();
        $needle = mb_strtolower($serial);

        $match = $items->first(fn (DeliveryItem $it) => ! $it->rentalItemKit && ! $it->not_taken
            && mb_strtolower((string) $it->rentalItem?->productUnit?->serial_number) === $needle);

        if (! $match) {
            $match = $items->first(fn (DeliveryItem $it) => $it->rentalItemKit && ! $it->not_taken
                && mb_strtolower((string) $it->rentalItemKit->unitKit?->serial_number) === $needle);
        }

        if (! $match && $manual) {
            $match = $items->first(fn (DeliveryItem $it) => ! $it->not_taken
                && str_contains(mb_strtolower($this->itemLabel($it)), $needle));
        }

        if (! $match) {
            return ['status' => 'notfound', 'serial' => $serial];
        }

        $label = $this->itemLabel($match);
        if ($this->isItemUnavailable($match)) {
            return ['status' => 'unavailable', 'label' => $label];
        }
        if ($match->is_checked) {
            return ['status' => 'already', 'label' => $label];
        }

        $this->quickCheck($match->id);
        $checkedLabels = [$label];
        $checkedIds = [$match->id];

        // Cascade: auto-check this unit's auto_scan_with_parent kits.
        if ($cascade && ! $match->rentalItemKit) {
            $kits = $items->filter(fn (DeliveryItem $it) => $it->rentalItemKit
                && $it->rental_item_id === $match->rental_item_id
                && ! $it->is_checked
                && $it->rentalItemKit->unitKit?->auto_scan_with_parent);

            foreach ($kits as $kit) {
                if ($this->isItemUnavailable($kit)) {
                    continue;
                }
                $this->quickCheck($kit->id);
                $checkedLabels[] = $this->itemLabel($kit);
                $checkedIds[] = $kit->id;
            }
        }

        return ['status' => 'ok', 'label' => $label, 'checked' => $checkedLabels, 'checked_ids' => $checkedIds];
    }

    /**
     * Mark a bundled kit as "not taken" by the customer at pickup. It is flipped to
     * checked (so it no longer blocks the pickup gate) and vanishes from the return
     * checklist (Rental::createDeliveries() skips not_taken kits on the IN branch).
     */
    public function markNotTaken(int $id): void
    {
        $record = $this->delivery->items()->with('rentalItem.productUnit', 'rentalItemKit.unitKit')->find($id);
        if (! $record || ! $record->rentalItemKit) {
            return;
        }

        $record->update(['not_taken' => true, 'is_checked' => true, 'condition' => null]);

        $kitName = $record->rentalItemKit->unitKit->name ?? 'Kit';
        $serial = $record->rentalItem?->productUnit?->serial_number ?? '-';
        $this->rental->logActivity(
            "Kit '{$kitName}' pada unit {$serial} tidak diambil customer saat pickup",
            'general'
        );

        $this->delivery->refresh();
    }

    /** Undo a "not taken" kit before pickup is validated — it must be handed over again. */
    public function undoNotTaken(int $id): void
    {
        $record = $this->delivery->items()->with('rentalItem.productUnit', 'rentalItemKit.unitKit')->find($id);
        if (! $record || ! $record->rentalItemKit) {
            return;
        }

        $record->update(['not_taken' => false, 'is_checked' => false]);

        $kitName = $record->rentalItemKit->unitKit->name ?? 'Kit';
        $serial = $record->rentalItem?->productUnit?->serial_number ?? '-';
        $this->rental->logActivity(
            "Kit '{$kitName}' pada unit {$serial} jadi diambil customer (batal not-taken)",
            'general'
        );

        $this->delivery->refresh();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                $this->delivery->items()->getQuery()
                    ->with([
                        'rentalItem.productUnit.product',
                        'rentalItemKit.unitKit',
                        'rentalItem.rental', // Needed for swap action form
                    ])
            )
            ->columns([
                TextColumn::make('item_name')
                    ->label('Item')
                    ->getStateUsing(function (DeliveryItem $record) {
                        if ($record->rentalItemKit) {
                            return '↳ '.$record->rentalItemKit->unitKit->name;
                        }
                        $productName = $record->rentalItem->productUnit->product->name;
                        $variationName = $record->rentalItem->productUnit->variation->name ?? null;

                        return $productName.($variationName ? ' ('.$variationName.')' : '');
                    }),

                TextColumn::make('serial_number')
                    ->label('Serial Number')
                    ->getStateUsing(function (DeliveryItem $record) {
                        if ($record->rentalItemKit) {
                            return $record->rentalItemKit->unitKit->serial_number ?? '-';
                        }

                        return $record->rentalItem->productUnit->serial_number;
                    })
                    ->description(function (DeliveryItem $record) {
                        if ($record->rentalItemKit) {
                            return null;
                        }
                        $unit = $record->rentalItem->productUnit;
                        if (in_array($unit->status, [\App\Models\ProductUnit::STATUS_RENTED, \App\Models\ProductUnit::STATUS_MAINTENANCE])) {
                            return "⚠️ UNAVAILABLE ({$unit->status})";
                        }

                        return null;
                    })
                    ->color(function (DeliveryItem $record) {
                        if (! $record->rentalItemKit) {
                            $unit = $record->rentalItem->productUnit;
                            if (in_array($unit->status, [\App\Models\ProductUnit::STATUS_RENTED, \App\Models\ProductUnit::STATUS_MAINTENANCE])) {
                                return 'danger';
                            }
                        }

                        return null;
                    }),

                TextColumn::make('type')
                    ->label('Type')
                    ->getStateUsing(function (DeliveryItem $record) {
                        return $record->rentalItemKit ? 'Kit' : 'Unit';
                    })
                    ->badge()
                    ->color(fn (string $state) => $state === 'Unit' ? 'primary' : 'gray'),

                TextColumn::make('condition')
                    ->label('Condition')
                    ->badge()
                    ->color(fn (?string $state) => $state ? DeliveryItem::getConditionColor($state) : 'gray')
                    ->formatStateUsing(fn (?string $state) => $state ? ucfirst($state) : '-'),

                IconColumn::make('is_checked')
                    ->label('Checked')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
            ])
            ->recordActions([
                \Filament\Actions\Action::make('mark_not_taken')
                    ->label(fn (DeliveryItem $record) => $record->not_taken ? 'Diambil' : 'Tidak diambil')
                    ->icon(fn (DeliveryItem $record) => $record->not_taken ? 'heroicon-o-arrow-uturn-left' : 'heroicon-o-no-symbol')
                    ->color(fn (DeliveryItem $record) => $record->not_taken ? 'gray' : 'warning')
                    ->visible(fn (DeliveryItem $record) => $record->rentalItemKit !== null
                        && $this->delivery->status !== \App\Models\Delivery::STATUS_COMPLETED)
                    ->action(fn (DeliveryItem $record) => $record->not_taken
                        ? $this->undoNotTaken($record->id)
                        : $this->markNotTaken($record->id)),

                \Filament\Actions\Action::make('swap_unit')
                    ->label('Swap')
                    ->icon('heroicon-o-arrows-right-left')
                    ->color('info')
                    ->hidden(fn (DeliveryItem $record) => $record->rentalItemKit !== null)
                    ->form(function (DeliveryItem $record) {
                        $rental = $record->rentalItem->rental;
                        $productId = $record->rentalItem->productUnit->product_id;
                        $currentUnitId = $record->rentalItem->product_unit_id;

                        return [
                            Select::make('new_product_unit_id')
                                ->label('Select Available Unit')
                                ->options(function () use ($rental, $productId, $currentUnitId) {
                                    return \App\Models\ProductUnit::where('product_id', $productId)
                                        ->whereNotIn('status', [\App\Models\ProductUnit::STATUS_MAINTENANCE, \App\Models\ProductUnit::STATUS_RETIRED])
                                        ->where('id', '!=', $currentUnitId)
                                        ->whereDoesntHave('rentalItems', function ($q) use ($rental) {
                                            $q->whereHas('rental', function ($r) use ($rental) {
                                                $r->whereIn('status', [Rental::STATUS_QUOTATION, Rental::STATUS_CONFIRMED, Rental::STATUS_ACTIVE, Rental::STATUS_LATE_PICKUP, Rental::STATUS_LATE_RETURN])
                                                    ->where('id', '!=', $rental->id) // Exclude current rental
                                                    ->where(function ($d) use ($rental) {
                                                        $d->where('start_date', '<', $rental->end_date)
                                                            ->where('end_date', '>', $rental->start_date);
                                                    });
                                            });
                                        })
                                        ->get()
                                        ->mapWithKeys(fn ($u) => [$u->id => $u->serial_number.' ('.ucfirst($u->status).')']);
                                })
                                ->required()
                                ->searchable()
                                ->preload(),
                        ];
                    })
                    ->action(function (DeliveryItem $record, array $data) {
                        $rentalItem = $record->rentalItem;
                        $oldUnitId = $rentalItem->product_unit_id;
                        $newUnitId = $data['new_product_unit_id'];

                        // 1. Update Main Unit in Rental Item
                        $rentalItem->update([
                            'product_unit_id' => $newUnitId,
                        ]);

                        // 2. Remove old kits from Rental Item Kits (since they belong to old unit)
                        // And delete associated delivery items for those kits
                        $oldKits = $rentalItem->rentalItemKits;
                        foreach ($oldKits as $kit) {
                            // Delete delivery item associated with this kit
                            $this->delivery->items()->where('rental_item_kit_id', $kit->id)->delete();
                            $kit->delete();
                        }

                        // 3. Attach new kits from new unit
                        $rentalItem->refresh(); // Refresh to get new unit relation
                        $rentalItem->attachKitsFromUnit();

                        // 4. Create new delivery items for new kits
                        $rentalItem->refresh(); // Refresh to get new kits
                        foreach ($rentalItem->rentalItemKits as $kit) {
                            $this->delivery->items()->firstOrCreate([
                                'rental_item_id' => $rentalItem->id,
                                'rental_item_kit_id' => $kit->id,
                            ], [
                                'is_checked' => false,
                                'condition' => $kit->condition_out,
                            ]);
                        }

                        Notification::make()
                            ->title('Unit swapped successfully')
                            ->success()
                            ->send();

                        $this->redirect(request()->header('Referer'));
                    }),

                \Filament\Actions\Action::make('check_item')
                    ->label(fn (DeliveryItem $record) => $record->is_checked ? 'Edit' : 'Check')
                    ->icon(fn (DeliveryItem $record) => $record->is_checked ? 'heroicon-o-pencil' : 'heroicon-o-check')
                    ->color(fn (DeliveryItem $record) => $record->is_checked ? 'gray' : 'warning')
                    ->disabled(function (DeliveryItem $record) {
                        // Check parent unit status for both Unit and Kit items
                        $unit = $record->rentalItem->productUnit;

                        return in_array($unit->status, [\App\Models\ProductUnit::STATUS_RENTED, \App\Models\ProductUnit::STATUS_MAINTENANCE]);
                    })
                    ->modalHeading('Check Item')
                    ->modalWidth('md')
                    ->fillForm(function (DeliveryItem $record): array {
                        return [
                            'item_name' => $record->rentalItemKit
                                ? $record->rentalItemKit->unitKit->name
                                : $record->rentalItem->productUnit->product->name,
                            'condition' => $record->condition,
                            'is_checked' => $record->is_checked,
                            'notes' => $record->notes,
                        ];
                    })
                    ->form(function (DeliveryItem $record) {
                        return [
                            TextInput::make('item_name')
                                ->label('Item')
                                ->disabled()
                                ->dehydrated(false),

                            Select::make('condition')
                                ->label('Condition')
                                ->options(DeliveryItem::getConditionOptions())
                                ->required(),

                            Checkbox::make('is_checked')
                                ->label('Mark as Checked'),

                            Textarea::make('notes')
                                ->label('Notes')
                                ->rows(2),
                        ];
                    })
                    ->action(function (DeliveryItem $record, array $data) {
                        $record->update([
                            'condition' => $data['condition'],
                            'is_checked' => $data['is_checked'],
                            'notes' => $data['notes'],
                        ]);

                        // SYNC CONDITION TO MASTER DATA
                        $newCondition = $data['condition'];
                        $isMaintenance = in_array($newCondition, ['broken', 'lost']);
                        $updates = ['condition' => $newCondition];

                        if ($isMaintenance) {
                            // Add note about auto maintenance
                            $updates['notes'] = ($record->rentalItemKit ? $record->rentalItemKit->unitKit->notes : $record->rentalItem->productUnit->notes)."\n[AUTO] Marked as {$newCondition} during Pickup.";

                            // Only update status for Main Unit, as Kit doesn't have status field
                            if (! $record->rentalItemKit) {
                                $updates['status'] = \App\Models\ProductUnit::STATUS_MAINTENANCE;
                            }
                        }

                        if ($record->rentalItemKit) {
                            $record->rentalItemKit->update([
                                'condition_out' => $data['condition'],
                            ]);
                            // Update Unit Kit Master
                            $record->rentalItemKit->unitKit->update($updates);
                        } else {
                            // Update Main Unit Master
                            $record->rentalItem->productUnit->update($updates);
                        }

                        $this->delivery->refresh();

                        Notification::make()
                            ->title('Item updated')
                            ->success()
                            ->send();
                    }),
            ])
            ->headerActions([
                $this->getMarkAllCheckedAction(),
            ])
            ->paginated(false);
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\ActionGroup::make([
                Action::make('send_whatsapp_reminder')
                    ->label('Pickup Reminder (WhatsApp)')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('success')
                    ->visible(fn () => \App\Models\Setting::get('whatsapp_enabled', true))
                    ->disabled(fn () => empty($this->rental->customer->phone))
                    ->tooltip(fn () => empty($this->rental->customer->phone) ? 'Customer phone number is missing' : null)
                    ->url(function () {
                        $rental = $this->rental;
                        $customer = $rental->customer;

                        // Safety check if phone is missing
                        if (empty($customer->phone)) {
                            return '#';
                        }

                        $pdfLink = \Illuminate\Support\Facades\URL::signedRoute('public-documents.rental.checklist', ['rental' => $rental]);

                        $data = [
                            'customer_name' => $customer->name,
                            'rental_ref' => $rental->rental_code,
                            'pickup_date' => \Carbon\Carbon::parse($rental->start_date)->format('d M Y H:i'),
                            'link_pdf' => $pdfLink,
                            'company_name' => \App\Models\Setting::get('site_name', 'Zewalo'),
                        ];

                        $message = \App\Helpers\WhatsAppHelper::parseTemplate('whatsapp_template_rental_pickup', $data);

                        return \App\Helpers\WhatsAppHelper::getLink($customer->phone, $message);
                    })
                    ->openUrlInNewTab(),

                Action::make('send_email_reminder')
                    ->label('Pickup Reminder (Email)')
                    ->icon('heroicon-o-envelope')
                    ->color('gray')
                    ->disabled()
                    ->tooltip('Coming Soon'),
            ])
                ->label('Send')
                ->icon('heroicon-o-paper-airplane')
                ->color('info')
                ->button(),

            \Filament\Actions\ActionGroup::make([
                Action::make('download_checklist')
                    ->label('Download Checklist Form')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->action(function () {
                        $this->rental->load(['customer', 'items.productUnit.product', 'items.rentalItemKits.unitKit']);

                        $pdf = Pdf::loadView('pdf.checklist-form', ['rental' => $this->rental]);

                        return response()->streamDownload(
                            fn () => print ($pdf->output()),
                            'Checklist-'.$this->rental->rental_code.'.pdf'
                        );
                    }),

                Action::make('download_delivery_note')
                    ->label('Download Delivery Note')
                    ->icon('heroicon-o-truck')
                    ->action(function () {
                        $this->delivery->load(['rental.customer', 'items.rentalItem.productUnit.product', 'items.rentalItemKit.unitKit', 'checkedBy']);

                        $pdf = Pdf::loadView('pdf.delivery-note', ['delivery' => $this->delivery]);

                        return response()->streamDownload(
                            fn () => print ($pdf->output()),
                            $this->delivery->delivery_number.'.pdf'
                        );
                    }),
            ])
                ->label('Print')
                ->icon('heroicon-o-printer')
                ->color('info')
                ->button(),

            Action::make('rental_documents')
                ->label('Delivery')
                ->icon('heroicon-o-document-duplicate')
                ->color('gray')
                ->url(fn () => RentalResource::getUrl('documents', ['record' => $this->rental])),

            Action::make('edit_rental')
                ->label('Edit Rental')
                ->icon('heroicon-o-pencil-square')
                ->color('gray')
                ->url(fn () => RentalResource::getUrl('edit', ['record' => $this->rental])),

            $this->getValidatePickupAction(),
        ];
    }

    public function getValidatePickupAction(): Action
    {
        return Action::make('validate_pickup')
            ->label('Validate Pickup')
            ->icon('heroicon-o-check-circle')
            ->color('success')
            ->size('lg')
            ->modalHeading('Confirm Pickup')
            ->modalSubmitActionLabel('Yes, Confirm Pickup')
            ->disabled(fn () => ! $this->allItemsChecked())
            ->form(function () {
                $status = $this->getAvailabilityStatus();
                $conflicts = $status['conflicts'];
                $unavailableUnits = $status['unavailable_units'];

                if (empty($conflicts) && empty($unavailableUnits)) {
                    return [
                        Placeholder::make('confirmation')
                            ->label('')
                            ->content('Are you sure the customer has picked up all items? This will change the rental status to Active.'),
                    ];
                }

                // If conflicts exist
                $conflictMessages = [];
                foreach ($conflicts as $conflict) {
                    $item = $conflict['item'];
                    $unit = $item->productUnit;
                    $unitName = $unit->product->name ?? 'Unknown Product';
                    $serial = $unit->serial_number ?? '-';

                    $conflictingRentals = $conflict['conflicting_rentals'];
                    $rentalInfo = $conflictingRentals->map(function ($r) {
                        $customerName = $r->customer->name ?? 'Unknown';

                        return "{$r->rental_code} ($customerName)";
                    })->implode(', ');

                    $conflictMessages[] = "<li><strong>$unitName ($serial)</strong> vs $rentalInfo</li>";
                }

                // If unavailable units exist
                foreach ($unavailableUnits as $unit) {
                    $unitName = $unit->product->name ?? 'Unknown Product';
                    $serial = $unit->serial_number ?? '-';
                    $conflictMessages[] = "<li><strong>$unitName ($serial)</strong> is currently <strong>".strtoupper($unit->status).'</strong></li>';
                }

                return [
                    Placeholder::make('conflict_warning')
                        ->label('⚠️ Scheduling Conflicts Detected')
                        ->content(new \Illuminate\Support\HtmlString(
                            '<div class="text-danger-600 dark:text-danger-400" style="color: red;">
                                <p>The following units are unavailable or double-booked:</p>
                                <ul class="list-disc pl-5 mt-2 mb-4">'.implode('', $conflictMessages).'</ul>
                                <p class="font-bold">You cannot proceed with this pickup until these conflicts are resolved manually.</p>
                                <p class="text-sm text-gray-600">Please cancel or modify the conflicting rentals first.</p>
                            </div>'
                        )),
                ];
            })
            ->action(function (array $data) {
                // Check conflicts again to be safe
                $status = $this->getAvailabilityStatus();

                if (! $status['available']) {
                    Notification::make()
                        ->title('Cannot Validate Pickup')
                        ->body('There are unresolved scheduling conflicts or unavailable units. Please resolve them manually before validating pickup.')
                        ->danger()
                        ->send();

                    $this->halt();

                    return;
                }

                $this->rental->validatePickup();

                // Also complete the delivery
                $this->delivery->complete();

                Notification::make()
                    ->title('Pickup validated successfully')
                    ->body('Rental status changed to Active.')
                    ->success()
                    ->send();

                $this->redirect(RentalResource::getUrl('index'));
            });
    }

    public function validatePickupAction(): Action
    {
        return $this->getValidatePickupAction();
    }
}

<?php

namespace App\Filament\Resources\ProductUnits\RelationManagers;

use App\Models\ProductUnit;
use App\Models\UnitKit;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KitsRelationManager extends RelationManager
{
    protected static string $relationship = 'kits';

    protected static ?string $title = 'Unit Kits';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('e.g., Battery, Charger, Strap, Lens Cap'),

                Toggle::make('track_by_serial')
                    ->label('Track by serial')
                    ->helperText('Required serial and mirror this kit as a ProductUnit (shared when the serial already exists).')
                    ->default(true)
                    ->live(),

                TextInput::make('serial_number')
                    ->label('Serial Number')
                    ->maxLength(255)
                    ->placeholder('Required when tracking is enabled')
                    ->required(fn (callable $get) => (bool) $get('track_by_serial'))
                    ->visible(fn (callable $get) => (bool) $get('track_by_serial'))
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set) {
                        if (! filled($state)) {
                            return;
                        }

                        $existingUnit = ProductUnit::where('serial_number', $state)->with('product')->first();

                        if ($existingUnit) {
                            $set('name', $existingUnit->product->name);
                            $set('condition', $existingUnit->condition);

                            \Filament\Notifications\Notification::make()
                                ->title('Existing Unit Found')
                                ->body("Unit '{$existingUnit->product->name}' with serial '{$state}' will be linked automatically.")
                                ->success()
                                ->send();
                        }
                    }),

                Select::make('condition')
                    ->options(UnitKit::getConditionOptions())
                    ->default('excellent')
                    ->required(),

                Textarea::make('notes')
                    ->rows(3)
                    ->placeholder('Additional notes about this kit item'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('serial_number')
                    ->searchable()
                    ->placeholder('—'),

                IconColumn::make('track_by_serial')
                    ->label('Tracked')
                    ->boolean(),

                TextColumn::make('condition')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'excellent' => 'success',
                        'good' => 'info',
                        'fair' => 'warning',
                        'poor' => 'danger',
                        'broken' => 'danger',
                        'lost' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('notes')
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->notes)
                    ->placeholder('—'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

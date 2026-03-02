<?php

namespace App\Filament\Resources\EmailLogs;

use App\Filament\Clusters\Settings\SettingsCluster;
use App\Filament\Resources\EmailLogs\Pages\ListEmailLogs;
use App\Filament\Resources\EmailLogs\Pages\ViewEmailLog;
use App\Models\EmailLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use UnitEnum;

class EmailLogResource extends Resource
{
    protected static ?string $model = EmailLog::class;

    protected static ?string $cluster = SettingsCluster::class;

    // Navigation Configuration
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-envelope';
    
    protected static string|UnitEnum|null $navigationGroup = 'System';
    
    protected static ?int $navigationSort = 10;
    
    protected static ?string $navigationLabel = 'Email Logs';

    protected static ?string $modelLabel = 'Email Log';

    protected static ?string $pluralModelLabel = 'Email Logs';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('to')
                    ->label('Recipient')
                    ->disabled(),
                TextInput::make('subject')
                    ->disabled(),
                TextInput::make('mailable_class')
                    ->label('Type')
                    ->disabled(),
                TextInput::make('status')
                    ->disabled(),
                Textarea::make('error_message')
                    ->label('Error Message')
                    ->disabled()
                    ->visible(fn ($record) => $record?->status === 'failed'),
                TextInput::make('sent_at')
                    ->label('Sent At')
                    ->disabled(),
                TextInput::make('created_at')
                    ->label('Created At')
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable(),
                TextColumn::make('to')
                    ->label('Recipient')
                    ->searchable()
                    ->limit(30),
                TextColumn::make('subject')
                    ->searchable()
                    ->limit(40),
                TextColumn::make('mailable_class')
                    ->label('Type')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'sent' => 'success',
                        'failed' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('error_message')
                    ->label('Error')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('user.name')
                    ->label('Triggered By')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('sent_at')
                    ->label('Sent At')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'sent' => 'Sent',
                        'failed' => 'Failed',
                    ]),
            ])
            ->actions([
                ViewAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEmailLogs::route('/'),
            'view' => ViewEmailLog::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }
}

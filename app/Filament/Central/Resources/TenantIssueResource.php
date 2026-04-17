<?php

declare(strict_types=1);

namespace App\Filament\Central\Resources;

use App\Filament\Central\Resources\TenantIssueResource\Pages;
use App\Models\TenantIssue;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class TenantIssueResource extends Resource
{
    protected static ?string $model = TenantIssue::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-exclamation-triangle';

    protected static string|UnitEnum|null $navigationGroup = null;

    protected static ?int $navigationSort = 25;

    public static function getNavigationGroup(): ?string
    {
        return __('admin.nav.system');
    }

    public static function getNavigationLabel(): string
    {
        return 'Tenant Issues';
    }

    public static function getModelLabel(): string
    {
        return 'Tenant Issue';
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::query()->whereNull('resolved_at')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::query()->whereNull('resolved_at')->where('severity', 'critical')->exists()
            ? 'danger'
            : 'warning';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Overview')
                ->schema([
                    TextInput::make('reference')
                        ->label('Reference')
                        ->formatStateUsing(fn ($record) => $record ? 'ZWL-ERR-' . str_pad((string) $record->id, 6, '0', STR_PAD_LEFT) : null)
                        ->disabled(),
                    TextInput::make('code')->disabled(),
                    TextInput::make('tenant_name')->disabled(),
                    TextInput::make('tenant_id')->disabled(),
                    TextInput::make('area')->disabled(),
                    TextInput::make('severity')->disabled(),
                    TextInput::make('title')->disabled()->columnSpanFull(),
                ])
                ->columns(2),

            Section::make('Error Detail')
                ->schema([
                    Textarea::make('message')->disabled()->rows(3)->columnSpanFull(),
                    TextInput::make('exception_class')->disabled(),
                    TextInput::make('line')->disabled(),
                    TextInput::make('file')->disabled()->columnSpanFull(),
                    Textarea::make('stack_trace')->disabled()->rows(12)->columnSpanFull(),
                ])
                ->columns(2)
                ->collapsible(),

            Section::make('Context')
                ->schema([
                    TextInput::make('url')->disabled()->columnSpanFull(),
                    TextInput::make('user_email')->disabled(),
                    Textarea::make('context_json')
                        ->label('Context')
                        ->formatStateUsing(fn ($record) => $record?->context ? json_encode($record->context, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : null)
                        ->disabled()
                        ->rows(6)
                        ->columnSpanFull(),
                ])
                ->columns(2)
                ->collapsible()
                ->collapsed(),

            Section::make('Resolution')
                ->schema([
                    TextInput::make('resolved_by')->disabled(),
                    TextInput::make('resolved_at')->disabled(),
                    Textarea::make('resolution_note')->rows(3)->columnSpanFull(),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Ref')
                    ->formatStateUsing(fn ($state) => 'ZWL-ERR-' . str_pad((string) $state, 6, '0', STR_PAD_LEFT))
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('severity')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'critical' => 'danger',
                        'error' => 'danger',
                        'warning' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('code')
                    ->badge()
                    ->color('gray')
                    ->searchable(),
                Tables\Columns\TextColumn::make('area')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('tenant_name')
                    ->label('Tenant')
                    ->description(fn ($record) => $record->tenant_id ? (string) $record->tenant_id : null)
                    ->searchable(['tenant_name', 'tenant_id']),
                Tables\Columns\TextColumn::make('title')
                    ->limit(60)
                    ->tooltip(fn ($record) => $record->title)
                    ->searchable(),
                Tables\Columns\IconColumn::make('resolved_at')
                    ->label('Resolved')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->since(),
            ])
            ->filters([
                SelectFilter::make('severity')
                    ->options([
                        'critical' => 'Critical',
                        'error' => 'Error',
                        'warning' => 'Warning',
                    ]),
                SelectFilter::make('area')
                    ->options(fn () => TenantIssue::query()->distinct()->pluck('area', 'area')->toArray()),
                TernaryFilter::make('resolved_at')
                    ->label('Status')
                    ->nullable()
                    ->placeholder('All')
                    ->trueLabel('Resolved')
                    ->falseLabel('Unresolved')
                    ->default(false),
            ])
            ->actions([
                \Filament\Actions\ViewAction::make(),
                Action::make('resolve')
                    ->label('Resolve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (TenantIssue $record) => ! $record->isResolved())
                    ->requiresConfirmation()
                    ->form([
                        Textarea::make('note')->label('Resolution note')->rows(3),
                    ])
                    ->action(function (TenantIssue $record, array $data) {
                        $record->markResolved(
                            by: auth()->user()?->email,
                            note: $data['note'] ?? null,
                        );

                        Notification::make()->title('Issue marked as resolved')->success()->send();
                    }),
                Action::make('reopen')
                    ->label('Reopen')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->visible(fn (TenantIssue $record) => $record->isResolved())
                    ->requiresConfirmation()
                    ->action(function (TenantIssue $record) {
                        $record->update([
                            'resolved_at' => null,
                            'resolved_by' => null,
                            'resolution_note' => null,
                        ]);

                        Notification::make()->title('Issue reopened')->warning()->send();
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTenantIssues::route('/'),
            'view' => Pages\ViewTenantIssue::route('/{record}'),
            'edit' => Pages\EditTenantIssue::route('/{record}/edit'),
        ];
    }
}

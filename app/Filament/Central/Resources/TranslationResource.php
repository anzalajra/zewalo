<?php

namespace App\Filament\Central\Resources;

use App\Filament\Central\Resources\TranslationResource\Pages;
use App\Models\LanguageLine;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class TranslationResource extends Resource
{
    protected static ?string $model = LanguageLine::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-language';

    protected static string|UnitEnum|null $navigationGroup = 'Content Management';

    protected static ?string $navigationLabel = 'Translations';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Translation Key')
                    ->schema([
                        TextInput::make('group')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g. landing.header, common, auth')
                            ->helperText('Translation group (e.g. common, auth, landing.header)'),

                        TextInput::make('key')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g. login, hero_title')
                            ->helperText('Translation key within the group'),
                    ])
                    ->columns(2),

                Section::make('Translations')
                    ->schema([
                        Textarea::make('text.id')
                            ->label('Indonesian (ID)')
                            ->rows(3)
                            ->required()
                            ->placeholder('Terjemahan Bahasa Indonesia'),

                        Textarea::make('text.en')
                            ->label('English (EN)')
                            ->rows(3)
                            ->required()
                            ->placeholder('English translation'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('group')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('key')
                    ->searchable()
                    ->sortable()
                    ->limit(40),

                Tables\Columns\TextColumn::make('text.id')
                    ->label('ID')
                    ->limit(50)
                    ->searchable(query: function ($query, string $search) {
                        $query->where('text->id', 'ilike', "%{$search}%");
                    })
                    ->toggleable(),

                Tables\Columns\TextColumn::make('text.en')
                    ->label('EN')
                    ->limit(50)
                    ->searchable(query: function ($query, string $search) {
                        $query->where('text->en', 'ilike', "%{$search}%");
                    })
                    ->toggleable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('group')
            ->filters([
                Tables\Filters\SelectFilter::make('group')
                    ->options(fn () => LanguageLine::query()
                        ->select('group')
                        ->distinct()
                        ->orderBy('group')
                        ->pluck('group', 'group')
                        ->toArray()
                    )
                    ->searchable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
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
            'index' => Pages\ListTranslations::route('/'),
            'create' => Pages\CreateTranslation::route('/create'),
            'edit' => Pages\EditTranslation::route('/{record}/edit'),
        ];
    }
}

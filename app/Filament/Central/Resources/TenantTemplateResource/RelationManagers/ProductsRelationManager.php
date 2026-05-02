<?php

namespace App\Filament\Central\Resources\TenantTemplateResource\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';

    protected static ?string $title = 'Products';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('ProductTabs')
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make('Informasi Produk')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        TextInput::make('name')
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn ($state, callable $set, $record) => $record === null ? $set('slug', Str::slug($state)) : null),

                                        TextInput::make('slug')
                                            ->required()
                                            ->maxLength(255)
                                            ->alphaDash(),

                                        Select::make('tenant_template_product_category_id')
                                            ->label('Product Category')
                                            ->options(fn ($livewire) => $livewire->getOwnerRecord()
                                                ->productCategories()
                                                ->orderBy('sort_order')
                                                ->pluck('name', 'id'))
                                            ->required(),

                                        Select::make('tenant_template_brand_id')
                                            ->label('Brand')
                                            ->options(fn ($livewire) => $livewire->getOwnerRecord()
                                                ->brands()
                                                ->pluck('name', 'id'))
                                            ->placeholder('Default (Umum)')
                                            ->nullable()
                                            ->helperText('Kosongkan untuk memakai default brand "Umum" saat import.'),

                                        TextInput::make('daily_rate')
                                            ->label('Harga per hari')
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->default(0)
                                            ->required(),

                                        TextInput::make('buffer_time')
                                            ->label('Buffer time (jam)')
                                            ->numeric()
                                            ->default(0),

                                        Textarea::make('description')
                                            ->rows(4)
                                            ->maxLength(2000)
                                            ->columnSpanFull(),

                                        Toggle::make('is_visible_on_frontend')
                                            ->label('Tampilkan di katalog storefront')
                                            ->default(true),

                                        TextInput::make('sort_order')
                                            ->numeric()
                                            ->default(0),
                                    ])
                                    ->columns(2),

                                Section::make('Gambar Produk')
                                    ->description('Gambar akan tersimpan di central R2 (central/templates/...). Saat import, file di-copy ke R2 tenant (tenant_<id>/products/...).')
                                    ->schema([
                                        FileUpload::make('image_path')
                                            ->label('Foto Produk')
                                            ->image()
                                            ->disk('r2')
                                            ->directory(fn ($livewire) => 'central/templates/' . $livewire->getOwnerRecord()->id)
                                            ->visibility('private')
                                            ->maxSize(4096)
                                            ->imageResizeMode('contain')
                                            ->helperText('Rekomendasi: JPG/PNG minimal 800x600px. Maks 4MB.')
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        Tab::make('Units & Kit')
                            ->icon('heroicon-o-cube')
                            ->schema([
                                Repeater::make('units')
                                    ->relationship('units')
                                    ->label('Template Units')
                                    ->schema([
                                        Section::make('Unit Information')
                                            ->columns(3)
                                            ->schema([
                                                TextInput::make('serial_suffix')
                                                    ->required()
                                                    ->maxLength(100)
                                                    ->placeholder('001')
                                                    ->helperText('Suffix serial. Saat import: TMPL-{PRODUCT-SLUG}-{suffix}'),

                                                Select::make('condition')
                                                    ->options([
                                                        'excellent' => 'Excellent',
                                                        'good' => 'Good',
                                                        'fair' => 'Fair',
                                                        'poor' => 'Poor',
                                                    ])
                                                    ->default('good')
                                                    ->required(),

                                                Select::make('status')
                                                    ->options([
                                                        'available' => 'Available',
                                                        'rented' => 'Rented',
                                                        'maintenance' => 'Maintenance',
                                                        'retired' => 'Retired',
                                                    ])
                                                    ->default('available')
                                                    ->required(),
                                            ]),

                                        Section::make('Kits / Accessories')
                                            ->description('Kit adalah aksesori yang menempel pada unit ini. Saat import, kit dibuat sebagai UnitKit di tenant DB. Aktifkan "Track by serial" supaya kit di-link ke ProductUnit dengan serial yang sama.')
                                            ->collapsible()
                                            ->collapsed()
                                            ->schema([
                                                Repeater::make('kits')
                                                    ->relationship('kits')
                                                    ->label('')
                                                    ->schema([
                                                        TextInput::make('name')
                                                            ->required()
                                                            ->maxLength(255),

                                                        Toggle::make('track_by_serial')
                                                            ->label('Track by serial')
                                                            ->default(true)
                                                            ->live(),

                                                        TextInput::make('serial_suffix')
                                                            ->label('Serial Suffix')
                                                            ->maxLength(100)
                                                            ->placeholder('001')
                                                            ->helperText('Saat import: TMPL-{PRODUCT-SLUG}-{suffix}. Kalau cocok dengan ProductUnit existing → auto-link.')
                                                            ->required(fn (callable $get) => (bool) $get('track_by_serial'))
                                                            ->visible(fn (callable $get) => (bool) $get('track_by_serial')),

                                                        Select::make('condition')
                                                            ->options([
                                                                'excellent' => 'Excellent',
                                                                'good' => 'Good',
                                                                'fair' => 'Fair',
                                                                'poor' => 'Poor',
                                                            ])
                                                            ->default('excellent')
                                                            ->required(),

                                                        Textarea::make('notes')
                                                            ->rows(2)
                                                            ->columnSpanFull(),
                                                    ])
                                                    ->columns(2)
                                                    ->defaultItems(0)
                                                    ->itemLabel(fn (array $state): ?string => $state['name'] ?? 'Kit baru')
                                                    ->addActionLabel('+ Tambah Kit'),
                                            ]),
                                    ])
                                    ->columns(1)
                                    ->defaultItems(1)
                                    ->itemLabel(fn (array $state): ?string => $state['serial_suffix'] ?? 'Unit baru')
                                    ->addActionLabel('+ Tambah Unit'),
                            ]),

                        Tab::make('Variations')
                            ->icon('heroicon-o-squares-2x2')
                            ->schema([
                                Repeater::make('variations')
                                    ->relationship('variations')
                                    ->label('Template Variations')
                                    ->schema([
                                        TextInput::make('name')
                                            ->required()
                                            ->maxLength(255)
                                            ->placeholder('Kit 18-55mm'),

                                        TextInput::make('daily_rate')
                                            ->label('Override Price (opsional)')
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->nullable(),
                                    ])
                                    ->columns(2)
                                    ->defaultItems(0)
                                    ->itemLabel(fn (array $state): ?string => $state['name'] ?? 'Variasi baru')
                                    ->addActionLabel('+ Tambah Variasi'),
                            ]),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                ImageColumn::make('image_path')
                    ->label('Image')
                    ->disk('r2')
                    ->visibility('private')
                    ->square(),
                TextColumn::make('name')->searchable(),
                TextColumn::make('productCategory.name')
                    ->label('Category')
                    ->badge()
                    ->sortable(),
                TextColumn::make('brand.name')
                    ->label('Brand')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('daily_rate')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('units_count')
                    ->counts('units')
                    ->label('Units')
                    ->badge(),
                TextColumn::make('variations_count')
                    ->counts('variations')
                    ->label('Vars')
                    ->badge()
                    ->toggleable(),
                TextColumn::make('kits_total')
                    ->label('Kits')
                    ->badge()
                    ->color('info')
                    ->state(fn ($record) => $record->units()
                        ->withCount('kits')
                        ->get()
                        ->sum('kits_count'))
                    ->toggleable(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

<?php

namespace App\Filament\Central\Resources\TenantTemplateResource\RelationManagers;

use App\Models\Central\TenantTemplateProduct;
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

                        Tab::make('Units')
                            ->icon('heroicon-o-cube')
                            ->schema([
                                Repeater::make('units')
                                    ->relationship('units')
                                    ->label('Template Units')
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
                                    ])
                                    ->columns(3)
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

                        Tab::make('Components / Kit')
                            ->icon('heroicon-o-squares-plus')
                            ->schema([
                                Repeater::make('components')
                                    ->relationship('components')
                                    ->label('Kit Components (produk ini sebagai bundle)')
                                    ->schema([
                                        Select::make('child_template_product_id')
                                            ->label('Child Product')
                                            ->options(function ($livewire, $record) {
                                                $templateId = $livewire->getOwnerRecord()->id;
                                                $parentId = $record?->parent_template_product_id;

                                                return TenantTemplateProduct::query()
                                                    ->where('tenant_template_id', $templateId)
                                                    ->when($parentId, fn ($q) => $q->where('id', '!=', $parentId))
                                                    ->pluck('name', 'id');
                                            })
                                            ->searchable()
                                            ->required()
                                            ->helperText('Produk yang termasuk dalam kit ini. Harus produk lain dalam template yang sama.'),

                                        TextInput::make('quantity')
                                            ->numeric()
                                            ->default(1)
                                            ->minValue(1)
                                            ->required(),
                                    ])
                                    ->columns(2)
                                    ->defaultItems(0)
                                    ->itemLabel(fn (array $state): ?string => isset($state['child_template_product_id']) ? TenantTemplateProduct::find($state['child_template_product_id'])?->name : 'Component baru')
                                    ->addActionLabel('+ Tambah Component')
                                    ->helperText('Hanya perlu diisi kalau produk ini adalah paket / kit yang terdiri dari beberapa produk lain.'),
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
                TextColumn::make('components_count')
                    ->counts('components')
                    ->label('Kit')
                    ->badge()
                    ->color('info')
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

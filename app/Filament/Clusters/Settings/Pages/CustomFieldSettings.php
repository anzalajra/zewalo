<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Filament\Clusters\Settings\SettingsCluster;
use App\Models\Setting;
use BackedEnum;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

/**
 * Manage the custom-field DEFINITIONS for Products and Rentals. Definitions are stored
 * as JSON in the Setting keys `product_custom_fields` / `rental_custom_fields` and are
 * rendered into the Product form and the RentalEditor via {@see \App\Support\CustomFields}.
 */
class CustomFieldSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $cluster = SettingsCluster::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-adjustments-horizontal';

    protected static ?string $navigationLabel = 'Custom Fields';

    protected static ?int $navigationSort = 4;

    protected string $view = 'filament.clusters.settings.pages.custom-field-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $product = json_decode(Setting::get('product_custom_fields', '[]'), true);
        $rental = json_decode(Setting::get('rental_custom_fields', '[]'), true);

        $this->form->fill([
            'product_custom_fields' => is_array($product) ? $product : [],
            'rental_custom_fields' => is_array($rental) ? $rental : [],
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('Custom Fields Produk')
                    ->description('Kolom tambahan yang muncul di form produk (Informasi Tambahan).')
                    ->schema([
                        Repeater::make('product_custom_fields')
                            ->hiddenLabel()
                            ->schema(self::customFieldSchema())
                            ->addActionLabel('Tambah Field Produk')
                            ->defaultItems(0)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['label'] ?? null),
                    ]),

                Section::make('Custom Fields Rental')
                    ->description('Kolom tambahan yang muncul di editor rental.')
                    ->schema([
                        Repeater::make('rental_custom_fields')
                            ->hiddenLabel()
                            ->schema(self::customFieldSchema())
                            ->addActionLabel('Tambah Field Rental')
                            ->defaultItems(0)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['label'] ?? null),
                    ]),
            ]);
    }

    /**
     * Shared Repeater schema for a custom-field definition (label/name/type/options/required).
     */
    public static function customFieldSchema(): array
    {
        return [
            Grid::make(2)->schema([
                TextInput::make('label')->required(),
                TextInput::make('name')
                    ->required()
                    ->label('Field Key')
                    ->helperText('Kunci unik untuk penyimpanan (contoh: berat_kg)'),
            ]),
            Select::make('type')
                ->options([
                    'text' => 'Text',
                    'number' => 'Number',
                    'select' => 'Select',
                    'radio' => 'Radio',
                    'checkbox' => 'Checkbox',
                    'textarea' => 'Textarea',
                ])
                ->required()
                ->reactive(),
            Textarea::make('options')
                ->label('Options (dipisah koma)')
                ->helperText('Untuk tipe Select dan Radio saja. Contoh: Opsi 1, Opsi 2')
                ->visible(fn ($get) => in_array($get('type'), ['select', 'radio']))
                ->required(fn ($get) => in_array($get('type'), ['select', 'radio'])),
            Checkbox::make('required')->label('Wajib Diisi'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $product = is_array($data['product_custom_fields'] ?? null) ? array_values($data['product_custom_fields']) : [];
        $rental = is_array($data['rental_custom_fields'] ?? null) ? array_values($data['rental_custom_fields']) : [];

        Setting::set('product_custom_fields', json_encode($product));
        Setting::set('rental_custom_fields', json_encode($rental));

        Notification::make()
            ->title('Custom fields saved successfully')
            ->success()
            ->send();
    }
}

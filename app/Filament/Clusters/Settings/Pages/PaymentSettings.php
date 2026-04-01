<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Filament\Clusters\Settings\SettingsCluster;
use App\Models\Setting;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PaymentSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $cluster = SettingsCluster::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationLabel = 'Payment';

    protected static ?int $navigationSort = 3;

    protected string $view = 'filament.clusters.settings.pages.payment-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = Setting::where('group', 'payment')
            ->get()
            ->pluck('value', 'key')
            ->toArray();

        // Cast boolean values
        if (isset($settings['payment_manual_transfer_enabled'])) {
            $settings['payment_manual_transfer_enabled'] = filter_var($settings['payment_manual_transfer_enabled'], FILTER_VALIDATE_BOOLEAN);
        }

        $this->form->fill($settings);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('Transfer Bank Manual')
                    ->description('Aktifkan metode pembayaran transfer bank manual untuk customer Anda.')
                    ->schema([
                        Toggle::make('payment_manual_transfer_enabled')
                            ->label('Aktifkan Transfer Manual')
                            ->live(),

                        TextInput::make('payment_manual_transfer_bank_name')
                            ->label('Nama Bank')
                            ->placeholder('Contoh: BCA, BNI, Mandiri')
                            ->visible(fn ($get) => $get('payment_manual_transfer_enabled'))
                            ->required(fn ($get) => $get('payment_manual_transfer_enabled')),

                        TextInput::make('payment_manual_transfer_account_number')
                            ->label('Nomor Rekening')
                            ->placeholder('Contoh: 1234567890')
                            ->visible(fn ($get) => $get('payment_manual_transfer_enabled'))
                            ->required(fn ($get) => $get('payment_manual_transfer_enabled')),

                        TextInput::make('payment_manual_transfer_account_holder')
                            ->label('Atas Nama')
                            ->placeholder('Nama pemilik rekening')
                            ->visible(fn ($get) => $get('payment_manual_transfer_enabled'))
                            ->required(fn ($get) => $get('payment_manual_transfer_enabled')),
                    ]),
            ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $paymentKeys = [
            'payment_manual_transfer_enabled',
            'payment_manual_transfer_bank_name',
            'payment_manual_transfer_account_number',
            'payment_manual_transfer_account_holder',
        ];

        foreach ($data as $key => $value) {
            if (! in_array($key, $paymentKeys)) {
                continue;
            }

            $type = 'string';
            if (is_bool($value)) {
                $type = 'boolean';
                $value = $value ? '1' : '0';
            }

            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'group' => 'payment',
                    'type' => $type,
                    'label' => ucwords(str_replace(['payment_', '_'], ['', ' '], $key)),
                ]
            );
        }

        Notification::make()
            ->title('Payment settings saved successfully')
            ->success()
            ->send();
    }
}

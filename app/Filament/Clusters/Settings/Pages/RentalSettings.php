<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Filament\Clusters\Settings\SettingsCluster;
use App\Models\Setting;
use BackedEnum;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;

class RentalSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $cluster = SettingsCluster::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationLabel = null;

    public static function getNavigationLabel(): string
    {
        return __('admin.rental_settings.nav_label');
    }

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.clusters.settings.pages.rental-settings';

    public ?array $data = [];

    public array $holidays = [];

    public array $operationalSchedule = [];

    private const DAY_ORDER = ['1', '2', '3', '4', '5', '6', '0'];

    private const DEFAULT_HOURS = ['open' => '08:00', 'close' => '17:00', 'is_24h' => false];

    public function mount(): void
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();

        // Load holidays
        if (isset($settings['holidays'])) {
            $this->holidays = json_decode($settings['holidays'], true) ?? [];
        }

        // Load operational schedule (new format)
        if (isset($settings['operational_schedule'])) {
            $this->operationalSchedule = json_decode($settings['operational_schedule'], true) ?? [];
        } else {
            // Migrate from old operational_days array
            $enabledDays = array_map('strval', json_decode($settings['operational_days'] ?? '[]', true) ?? []);
            foreach (self::DAY_ORDER as $day) {
                $this->operationalSchedule[$day] = array_merge(self::DEFAULT_HOURS, [
                    'enabled' => in_array($day, $enabledDays),
                ]);
            }
        }

        // Late fee tiers are stored as a JSON string but the Repeater needs an array.
        if (isset($settings['late_fee_tiers'])) {
            $decoded = json_decode($settings['late_fee_tiers'], true);
            $settings['late_fee_tiers'] = is_array($decoded) ? $decoded : [];
        }

        // Remove keys managed outside the Filament form
        unset($settings['holidays'], $settings['operational_days'], $settings['operational_schedule']);

        $this->form->fill($settings);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Grid::make(2)
                    ->schema([
                        Section::make('Deposit Settings')
                            ->schema([
                                Checkbox::make('deposit_enabled')
                                    ->label('Enable Deposit')
                                    ->default(true)
                                    ->live(),
                                Grid::make(2)
                                    ->visible(fn ($get) => $get('deposit_enabled'))
                                    ->schema([
                                        Select::make('deposit_type')
                                            ->options([
                                                'percentage' => 'Percentage (%)',
                                                'fixed' => 'Fixed Amount (Rp)',
                                            ])
                                            ->default('percentage')
                                            ->live()
                                            ->required(),
                                        TextInput::make('deposit_amount')
                                            ->label(fn ($get) => $get('deposit_type') === 'percentage' ? 'Percentage' : 'Amount')
                                            ->numeric()
                                            ->suffix(fn ($get) => $get('deposit_type') === 'percentage' ? '%' : null)
                                            ->prefix(fn ($get) => $get('deposit_type') === 'fixed' ? 'Rp' : null)
                                            ->required()
                                            ->default(30)
                                            ->minValue(0)
                                            ->maxValue(fn ($get) => $get('deposit_type') === 'percentage' ? 100 : null),
                                    ]),
                            ])->columnSpanFull(),

                        Section::make('Late Fee Settings')
                            ->schema([
                                Select::make('late_fee_mode')
                                    ->label('Late Fee Mode')
                                    ->options([
                                        'full_daily_rate' => 'Full daily rate per day',
                                        'per_unit_per_day' => 'Fixed amount per unit per day',
                                        'percentage_per_day' => 'Percentage of daily rate per day',
                                        'flat_per_day' => 'Flat amount per day (whole rental)',
                                        'tiered' => 'Tiered (by hours late)',
                                    ])
                                    ->default('full_daily_rate')
                                    ->live()
                                    ->helperText('Controls how calculateOverdueFee() charges overdue rentals.')
                                    ->required(),
                                TextInput::make('late_fee_amount')
                                    ->label(fn ($get) => $get('late_fee_mode') === 'percentage_per_day' ? 'Percentage per Day' : 'Amount per Day')
                                    ->numeric()
                                    ->suffix(fn ($get) => $get('late_fee_mode') === 'percentage_per_day' ? '%' : null)
                                    ->prefix(fn ($get) => in_array($get('late_fee_mode'), ['per_unit_per_day', 'flat_per_day']) ? 'Rp' : null)
                                    ->visible(fn ($get) => $get('late_fee_mode') !== 'tiered')
                                    ->helperText(fn ($get) => $get('late_fee_mode') === 'full_daily_rate'
                                        ? 'Not used in this mode — charges the item daily rate itself.'
                                        : null),
                                Repeater::make('late_fee_tiers')
                                    ->label('Tiers')
                                    ->visible(fn ($get) => $get('late_fee_mode') === 'tiered')
                                    ->schema([
                                        TextInput::make('up_to_hours')
                                            ->label('Up to (hours late)')
                                            ->numeric()
                                            ->required(),
                                        Select::make('charge_type')
                                            ->label('Charge')
                                            ->options([
                                                'percentage' => 'Percentage of daily rate',
                                                'fixed' => 'Fixed amount (Rp)',
                                            ])
                                            ->default('percentage')
                                            ->required(),
                                        TextInput::make('amount')
                                            ->label('Value')
                                            ->numeric()
                                            ->required(),
                                    ])
                                    ->addActionLabel('Add tier')
                                    ->default([])
                                    ->helperText('Beyond the last tier, each additional 24h adds one daily rate.'),
                            ])->columnSpanFull(),
                    ]),
            ]);
    }

    public function updateSchedule(array $schedule): void
    {
        $this->operationalSchedule = $schedule;

        $operationalDays = array_values(array_keys(array_filter($schedule, fn ($d) => $d['enabled'])));

        Setting::set('operational_schedule', json_encode($schedule));
        Setting::set('operational_days', json_encode($operationalDays));
    }

    public function addHoliday(string $name, string $startDate, string $endDate): void
    {
        $this->holidays[] = [
            'name'       => $name,
            'start_date' => $startDate,
            'end_date'   => $endDate,
        ];

        Setting::set('holidays', json_encode(array_values($this->holidays)));
    }

    public function removeHoliday(int $index): void
    {
        array_splice($this->holidays, $index, 1);

        Setting::set('holidays', json_encode(array_values($this->holidays)));
    }

    public function save(): void
    {
        $data = $this->form->getState();

        // Late fee tiers persist as a JSON string (calculateOverdueFee json_decodes it).
        if (array_key_exists('late_fee_tiers', $data)) {
            $data['late_fee_tiers'] = json_encode(array_values($data['late_fee_tiers'] ?? []));
        }

        // Persist schedule and holidays alongside form data
        $operationalDays = array_values(array_keys(array_filter($this->operationalSchedule, fn ($d) => $d['enabled'])));
        $data['operational_schedule'] = json_encode($this->operationalSchedule);
        $data['operational_days']     = json_encode($operationalDays);
        $data['holidays']             = json_encode(array_values($this->holidays));

        foreach ($data as $key => $value) {
            Setting::set($key, $value);
        }

        Notification::make()
            ->title('Settings saved successfully')
            ->success()
            ->send();
    }
}

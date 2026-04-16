<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Models\Setting;
use App\Models\TenantCategory;
use App\Services\SettingsSyncService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\HtmlString;

class SetupWizard extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-rocket-launch';

    protected static ?string $slug = 'setup-wizard';

    protected string $view = 'filament.pages.setup-wizard';

    public ?array $data = [];

    public array $operationalSchedule = [];

    public bool $importTemplateData = false;

    private const DAY_ORDER = ['1', '2', '3', '4', '5', '6', '0'];

    private const DEFAULT_HOURS = ['open' => '08:00', 'close' => '17:00', 'is_24h' => false];

    public static function getNavigationLabel(): string
    {
        return __('admin.setup_wizard.title');
    }

    public function getTitle(): string
    {
        return __('admin.setup_wizard.title');
    }

    public static function shouldRegisterNavigation(): bool
    {
        $tenant = tenant();

        return $tenant && $tenant->needsSetup();
    }

    public function mount(): void
    {
        $tenant = tenant();

        if (! $tenant || ! $tenant->needsSetup()) {
            $this->redirect(filament()->getUrl());

            return;
        }

        $settings = Setting::all()->pluck('value', 'key')->toArray();

        // Load operational schedule
        if (isset($settings['operational_schedule'])) {
            $this->operationalSchedule = json_decode($settings['operational_schedule'], true) ?? [];
        } else {
            $enabledDays = array_map('strval', json_decode($settings['operational_days'] ?? '[]', true) ?? []);
            foreach (self::DAY_ORDER as $day) {
                $this->operationalSchedule[$day] = array_merge(self::DEFAULT_HOURS, [
                    'enabled' => in_array($day, $enabledDays),
                ]);
            }
        }

        // Ensure all days exist
        foreach (self::DAY_ORDER as $day) {
            if (! isset($this->operationalSchedule[$day])) {
                $this->operationalSchedule[$day] = array_merge(self::DEFAULT_HOURS, ['enabled' => false]);
            }
        }

        // Pre-fill from tenant record and existing settings
        $prefill = array_merge($settings, [
            'site_name' => $settings['site_name'] ?? $tenant->name,
            'company_email' => $settings['company_email'] ?? $tenant->email,
            'tenant_category_id' => $tenant->tenant_category_id,
        ]);

        // Cast booleans
        if (isset($prefill['payment_manual_transfer_enabled'])) {
            $prefill['payment_manual_transfer_enabled'] = filter_var($prefill['payment_manual_transfer_enabled'], FILTER_VALIDATE_BOOLEAN);
        }

        $this->form->fill($prefill);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Wizard::make([
                    Step::make('store-info')
                        ->label(__('admin.setup_wizard.step1_title'))
                        ->description(__('admin.setup_wizard.step1_description'))
                        ->icon('heroicon-o-building-storefront')
                        ->schema([
                            FileUpload::make('site_logo')
                                ->label(__('admin.setup_wizard.logo_label'))
                                ->image()
                                ->tenantDirectory('settings')
                                ->columnSpanFull(),
                            TextInput::make('site_name')
                                ->label(__('admin.setup_wizard.store_name'))
                                ->required(),
                            Select::make('tenant_category_id')
                                ->label(__('admin.setup_wizard.store_category'))
                                ->options(TenantCategory::active()->pluck('name', 'id'))
                                ->searchable()
                                ->live(),
                            Textarea::make('company_address')
                                ->label(__('admin.setup_wizard.address'))
                                ->rows(2)
                                ->columnSpanFull(),
                            TextInput::make('company_phone')
                                ->label(__('admin.setup_wizard.phone'))
                                ->tel(),
                            TextInput::make('company_email')
                                ->label(__('admin.setup_wizard.email'))
                                ->email(),
                            ToggleButtons::make('theme_preset')
                                ->label(__('admin.setup_wizard.theme_preset'))
                                ->options([
                                    'default' => new HtmlString('<div class="w-6 h-6 rounded-full bg-gray-900 border border-gray-200" title="Default"></div>'),
                                    'slate' => new HtmlString('<div class="w-6 h-6 rounded-full" style="background-color: #64748b;" title="Slate"></div>'),
                                    'gray' => new HtmlString('<div class="w-6 h-6 rounded-full" style="background-color: #6b7280;" title="Gray"></div>'),
                                    'zinc' => new HtmlString('<div class="w-6 h-6 rounded-full" style="background-color: #71717a;" title="Zinc"></div>'),
                                    'neutral' => new HtmlString('<div class="w-6 h-6 rounded-full" style="background-color: #737373;" title="Neutral"></div>'),
                                    'stone' => new HtmlString('<div class="w-6 h-6 rounded-full" style="background-color: #78716c;" title="Stone"></div>'),
                                    'red' => new HtmlString('<div class="w-6 h-6 rounded-full" style="background-color: #ef4444;" title="Red"></div>'),
                                    'orange' => new HtmlString('<div class="w-6 h-6 rounded-full" style="background-color: #f97316;" title="Orange"></div>'),
                                    'amber' => new HtmlString('<div class="w-6 h-6 rounded-full" style="background-color: #f59e0b;" title="Amber"></div>'),
                                    'yellow' => new HtmlString('<div class="w-6 h-6 rounded-full" style="background-color: #eab308;" title="Yellow"></div>'),
                                    'lime' => new HtmlString('<div class="w-6 h-6 rounded-full" style="background-color: #84cc16;" title="Lime"></div>'),
                                    'green' => new HtmlString('<div class="w-6 h-6 rounded-full" style="background-color: #22c55e;" title="Green"></div>'),
                                    'emerald' => new HtmlString('<div class="w-6 h-6 rounded-full" style="background-color: #10b981;" title="Emerald"></div>'),
                                    'teal' => new HtmlString('<div class="w-6 h-6 rounded-full" style="background-color: #14b8a6;" title="Teal"></div>'),
                                    'cyan' => new HtmlString('<div class="w-6 h-6 rounded-full" style="background-color: #06b6d4;" title="Cyan"></div>'),
                                    'sky' => new HtmlString('<div class="w-6 h-6 rounded-full" style="background-color: #0ea5e9;" title="Sky"></div>'),
                                    'blue' => new HtmlString('<div class="w-6 h-6 rounded-full" style="background-color: #3b82f6;" title="Blue"></div>'),
                                    'indigo' => new HtmlString('<div class="w-6 h-6 rounded-full" style="background-color: #6366f1;" title="Indigo"></div>'),
                                    'violet' => new HtmlString('<div class="w-6 h-6 rounded-full" style="background-color: #8b5cf6;" title="Violet"></div>'),
                                    'purple' => new HtmlString('<div class="w-6 h-6 rounded-full" style="background-color: #a855f7;" title="Purple"></div>'),
                                    'fuchsia' => new HtmlString('<div class="w-6 h-6 rounded-full" style="background-color: #d946ef;" title="Fuchsia"></div>'),
                                    'pink' => new HtmlString('<div class="w-6 h-6 rounded-full" style="background-color: #ec4899;" title="Pink"></div>'),
                                    'rose' => new HtmlString('<div class="w-6 h-6 rounded-full" style="background-color: #f43f5e;" title="Rose"></div>'),
                                    'custom' => new HtmlString('<div class="w-6 h-6 rounded-full" style="background: conic-gradient(red, yellow, lime, aqua, blue, magenta, red);" title="Custom"></div>'),
                                ])
                                ->inline()
                                ->default('default')
                                ->live()
                                ->columnSpanFull(),
                            ColorPicker::make('theme_color')
                                ->label(__('admin.appearance.custom_color'))
                                ->visible(fn ($get) => $get('theme_preset') === 'custom')
                                ->required(fn ($get) => $get('theme_preset') === 'custom'),
                        ])
                        ->columns(2)
                        ->afterValidation(function () {
                            $this->saveStep1();
                        }),

                    Step::make('operational-hours')
                        ->label(__('admin.setup_wizard.step2_title'))
                        ->description(__('admin.setup_wizard.step2_description'))
                        ->icon('heroicon-o-clock')
                        ->schema([
                            Placeholder::make('schedule_placeholder')
                                ->label('')
                                ->content(new HtmlString('<div id="wizard-schedule-container"></div>'))
                                ->columnSpanFull(),
                        ])
                        ->afterValidation(function () {
                            $this->saveStep2();
                        }),

                    Step::make('payment')
                        ->label(__('admin.setup_wizard.step3_title'))
                        ->description(__('admin.setup_wizard.step3_description'))
                        ->icon('heroicon-o-credit-card')
                        ->schema([
                            Section::make('Transfer Bank Manual')
                                ->schema([
                                    Toggle::make('payment_manual_transfer_enabled')
                                        ->label(__('admin.setup_wizard.enable_bank_transfer'))
                                        ->live(),
                                    TextInput::make('payment_manual_transfer_bank_name')
                                        ->label(__('admin.setup_wizard.bank_name'))
                                        ->placeholder('BCA, BNI, Mandiri')
                                        ->visible(fn ($get) => $get('payment_manual_transfer_enabled'))
                                        ->required(fn ($get) => $get('payment_manual_transfer_enabled')),
                                    TextInput::make('payment_manual_transfer_account_number')
                                        ->label(__('admin.setup_wizard.account_number'))
                                        ->placeholder('1234567890')
                                        ->visible(fn ($get) => $get('payment_manual_transfer_enabled'))
                                        ->required(fn ($get) => $get('payment_manual_transfer_enabled')),
                                    TextInput::make('payment_manual_transfer_account_holder')
                                        ->label(__('admin.setup_wizard.account_holder'))
                                        ->visible(fn ($get) => $get('payment_manual_transfer_enabled'))
                                        ->required(fn ($get) => $get('payment_manual_transfer_enabled')),
                                ]),
                        ])
                        ->afterValidation(function () {
                            $this->saveStep3();
                        }),

                    Step::make('seed-data')
                        ->label(__('admin.setup_wizard.step4_title'))
                        ->description(__('admin.setup_wizard.step4_description'))
                        ->icon('heroicon-o-circle-stack')
                        ->schema([
                            Placeholder::make('category_info')
                                ->label(__('admin.setup_wizard.detected_category'))
                                ->content(function ($get) {
                                    $categoryId = $get('tenant_category_id') ?? tenant()->tenant_category_id;
                                    if ($categoryId) {
                                        $category = TenantCategory::find($categoryId);

                                        return $category ? $category->name : __('admin.setup_wizard.no_category');
                                    }

                                    return __('admin.setup_wizard.no_category');
                                }),
                            Toggle::make('import_template_data')
                                ->label(__('admin.setup_wizard.import_label'))
                                ->helperText(__('admin.setup_wizard.import_description'))
                                ->default(false),
                        ]),
                ])
                    ->submitAction(new HtmlString('<button type="submit" class="fi-btn fi-btn-size-md relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 inline-grid rounded-lg fi-btn-color-primary gap-1.5 px-3 py-2 text-sm shadow-sm bg-primary-600 text-white hover:bg-primary-500 focus-visible:ring-primary-500/50 dark:bg-primary-500 dark:hover:bg-primary-400">' . __('admin.setup_wizard.complete_button') . '</button>')),
            ]);
    }

    protected function saveStep1(): void
    {
        $data = $this->form->getState();

        $settingKeys = [
            'site_logo', 'site_name', 'site_description',
            'company_address', 'company_phone', 'company_email',
            'theme_preset', 'theme_color',
        ];

        foreach ($settingKeys as $key) {
            if (array_key_exists($key, $data)) {
                Setting::set($key, $data[$key]);
            }
        }

        // Update tenant category if changed
        $tenant = tenant();
        if (isset($data['tenant_category_id']) && $data['tenant_category_id'] != $tenant->tenant_category_id) {
            $tenant->update(['tenant_category_id' => $data['tenant_category_id']]);
        }

        // Sync to document layout
        SettingsSyncService::syncToDocumentLayout($data);

        // Track progress
        $tenant->setSetupCurrentStep(2);
    }

    protected function saveStep2(): void
    {
        $operationalDays = array_values(array_keys(array_filter($this->operationalSchedule, fn ($d) => $d['enabled'])));

        Setting::set('operational_schedule', json_encode($this->operationalSchedule));
        Setting::set('operational_days', json_encode($operationalDays));

        tenant()->setSetupCurrentStep(3);
    }

    protected function saveStep3(): void
    {
        $data = $this->form->getState();

        $paymentKeys = [
            'payment_manual_transfer_enabled',
            'payment_manual_transfer_bank_name',
            'payment_manual_transfer_account_number',
            'payment_manual_transfer_account_holder',
        ];

        foreach ($paymentKeys as $key) {
            if (! array_key_exists($key, $data)) {
                continue;
            }

            $value = $data[$key];
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

        tenant()->setSetupCurrentStep(4);
    }

    public function updateSchedule(array $schedule): void
    {
        $this->operationalSchedule = $schedule;
    }

    public function submit(): void
    {
        $data = $this->form->getState();
        $tenant = tenant();

        // Run template seeder if requested
        if (! empty($data['import_template_data'])) {
            $categoryId = $data['tenant_category_id'] ?? $tenant->tenant_category_id;

            if ($categoryId) {
                try {
                    $category = TenantCategory::find($categoryId);
                    if ($category) {
                        $seeder = new \Database\Seeders\Tenant\TenantTemplateSeeder;
                        $seeder->run($category->slug);
                    }
                } catch (\Throwable $e) {
                    Log::warning("SetupWizard: Template seeder failed: {$e->getMessage()}");

                    Notification::make()
                        ->title('Data contoh gagal diimport')
                        ->body('Anda bisa menambahkan produk secara manual.')
                        ->warning()
                        ->send();
                }
            }
        }

        // Mark setup as completed
        $tenant->completeSetup();

        Notification::make()
            ->title(__('admin.setup_wizard.completed_title'))
            ->body(__('admin.setup_wizard.completed_body'))
            ->success()
            ->send();

        $this->redirect(filament()->getUrl());
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('skip')
                ->label(__('admin.setup_wizard.skip_button'))
                ->color('gray')
                ->icon('heroicon-o-forward')
                ->requiresConfirmation()
                ->modalHeading(__('admin.setup_wizard.skip_confirm_title'))
                ->modalDescription(__('admin.setup_wizard.skip_confirm_desc'))
                ->action(function () {
                    tenant()->skipSetup();

                    Notification::make()
                        ->title(__('admin.setup_wizard.title'))
                        ->body(__('admin.setup_wizard.skip_confirm_desc'))
                        ->info()
                        ->send();

                    $this->redirect(filament()->getUrl());
                }),
        ];
    }
}

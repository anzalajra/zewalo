<?php

namespace App\Filament\Central\Resources;

use App\Enums\SaasInvoiceStatus;
use App\Filament\Central\Resources\SaasInvoiceResource\Pages;
use App\Models\PaymentGateway;
use App\Models\PaymentMethod;
use App\Models\SaasInvoice;
use App\Models\SubscriptionPlan;
use App\Models\Tenant;
use App\Models\TenantSubscription;
use App\Services\Payment\PaymentService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class SaasInvoiceResource extends Resource
{
    protected static ?string $model = SaasInvoice::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-currency-dollar';

    protected static string|UnitEnum|null $navigationGroup = null;

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = null;

    public static function getNavigationGroup(): ?string
    {
        return __('admin.nav.tenant_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin.saas_invoice.nav_label');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Invoice Details')
                ->schema([
                    Select::make('tenant_id')
                        ->label('Tenant')
                        ->options(Tenant::pluck('name', 'id'))
                        ->searchable()
                        ->required()
                        ->live(),

                    TextInput::make('invoice_number')
                        ->label('Invoice Number')
                        ->placeholder('Auto-generated if left blank')
                        ->unique(ignoreRecord: true),

                    Select::make('subscription_plan_id')
                        ->label('Subscription Plan')
                        ->options(SubscriptionPlan::orderBy('sort_order')->pluck('name', 'id'))
                        ->searchable()
                        ->required()
                        ->live()
                        ->dehydrated(false)
                        ->afterStateUpdated(function ($state, $set) {
                            if (! $state) {
                                return;
                            }
                            $plan = SubscriptionPlan::find($state);
                            if (! $plan) {
                                return;
                            }
                            // Default amount to monthly price; recompute total
                            $set('amount', (float) ($plan->price_monthly ?? 0));
                            $set('total', (float) ($plan->price_monthly ?? 0));
                        })
                        ->helperText('Paket yang ditagihkan. Subscription akan otomatis dibuat.'),

                    Select::make('billing_cycle')
                        ->label('Billing Cycle')
                        ->options([
                            'monthly' => 'Bulanan',
                            'yearly' => 'Tahunan',
                        ])
                        ->default('monthly')
                        ->required()
                        ->live()
                        ->dehydrated(false)
                        ->afterStateUpdated(function ($state, $get, $set) {
                            $planId = $get('subscription_plan_id');
                            if (! $planId) {
                                return;
                            }
                            $plan = SubscriptionPlan::find($planId);
                            if (! $plan) {
                                return;
                            }
                            $price = $state === 'yearly'
                                ? (float) ($plan->price_yearly ?? 0)
                                : (float) ($plan->price_monthly ?? 0);
                            $set('amount', $price);
                            $set('total', $price);
                        }),

                    Select::make('tenant_subscription_id')
                        ->label('Subscription (existing)')
                        ->options(function ($get) {
                            $tenantId = $get('tenant_id');
                            if (! $tenantId) {
                                return [];
                            }

                            return TenantSubscription::query()
                                ->where('tenant_id', $tenantId)
                                ->with('subscriptionPlan')
                                ->latest('started_at')
                                ->get()
                                ->mapWithKeys(function (TenantSubscription $sub) {
                                    $planName = $sub->subscriptionPlan?->name ?? 'Plan #'.$sub->subscription_plan_id;
                                    $cycle = match ($sub->billing_cycle) {
                                        'monthly' => 'Bulanan',
                                        'yearly' => 'Tahunan',
                                        default => $sub->billing_cycle ?? '—',
                                    };
                                    $period = $sub->started_at && $sub->ends_at
                                        ? $sub->started_at->format('d M Y').' – '.$sub->ends_at->format('d M Y')
                                        : 'tanpa periode';
                                    $status = ucfirst($sub->status ?? '—');

                                    return [$sub->id => "{$planName} · {$cycle} · {$period} · [{$status}]"];
                                })
                                ->all();
                        })
                        ->searchable()
                        ->nullable()
                        ->placeholder(fn ($get) => $get('tenant_id')
                            ? 'Auto-create dari plan di atas'
                            : 'Pilih tenant dulu')
                        ->disabled(fn ($get) => ! $get('tenant_id'))
                        ->helperText('Kosongkan untuk auto-create subscription baru dari plan + cycle di atas. Pilih existing untuk renewal/koreksi tagihan tambahan ke subscription yang sudah ada.'),

                    Select::make('status')
                        ->options([
                            'pending' => 'Pending',
                            'paid' => 'Paid',
                            'overdue' => 'Overdue',
                            'cancelled' => 'Cancelled',
                        ])
                        ->default('pending')
                        ->required(),

                    Select::make('currency')
                        ->options(['IDR' => 'IDR', 'USD' => 'USD', 'EUR' => 'EUR'])
                        ->default('IDR')
                        ->required(),
                ])
                ->columns(2),

            Section::make('Amounts')
                ->schema([
                    TextInput::make('amount')
                        ->numeric()
                        ->prefix('Rp')
                        ->required()
                        ->default(0),

                    TextInput::make('tax')
                        ->numeric()
                        ->prefix('Rp')
                        ->default(0),

                    TextInput::make('total')
                        ->numeric()
                        ->prefix('Rp')
                        ->required()
                        ->default(0),
                ])
                ->columns(3),

            Section::make('Dates')
                ->schema([
                    DateTimePicker::make('issued_at')
                        ->required()
                        ->default(now()),

                    DateTimePicker::make('due_at')
                        ->required(),

                    DateTimePicker::make('paid_at')
                        ->nullable(),
                ])
                ->columns(3),

            Section::make('Payment')
                ->schema([
                    TextInput::make('payment_method')
                        ->nullable()
                        ->placeholder('e.g., Bank Transfer, Duitku'),

                    TextInput::make('payment_reference')
                        ->nullable()
                        ->placeholder('Transaction ID or reference'),

                    Select::make('payment_gateway_id')
                        ->label('Payment Gateway')
                        ->options(PaymentGateway::pluck('name', 'id'))
                        ->nullable()
                        ->placeholder('Auto (via gateway)'),

                    Select::make('payment_method_id')
                        ->label('Payment Method')
                        ->options(PaymentMethod::pluck('display_name', 'id'))
                        ->nullable()
                        ->placeholder('Auto (via gateway)'),

                    TextInput::make('gateway_reference_id')
                        ->label('Gateway Reference')
                        ->disabled()
                        ->placeholder('Set by system'),

                    Textarea::make('notes')
                        ->rows(3)
                        ->columnSpanFull(),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')
                    ->searchable()
                    ->copyable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('tenant.name')
                    ->label('Tenant')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('total')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof SaasInvoiceStatus ? $state->getLabel() : $state)
                    ->color(fn ($state) => $state instanceof SaasInvoiceStatus ? $state->getColor() : 'gray'),

                Tables\Columns\TextColumn::make('paymentMethod.display_name')
                    ->label('Via')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('issued_at')
                    ->label('Issued')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('due_at')
                    ->label('Due')
                    ->date('d M Y')
                    ->sortable()
                    ->color(fn ($record) => $record->isDueSoon() ? 'warning' : null),

                Tables\Columns\TextColumn::make('paid_at')
                    ->label('Paid')
                    ->date('d M Y')
                    ->placeholder('—')
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'overdue' => 'Overdue',
                        'cancelled' => 'Cancelled',
                    ]),

                Tables\Filters\SelectFilter::make('tenant_id')
                    ->label('Tenant')
                    ->options(fn () => Tenant::pluck('name', 'id')),
            ])
            ->recordActions([
                Action::make('mark_as_paid')
                    ->label('Tandai Lunas')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Tandai Invoice sebagai Lunas?')
                    ->modalDescription('Tindakan ini akan menandai invoice sebagai lunas dan mengaktifkan subscription tenant.')
                    ->visible(fn (SaasInvoice $record) => ! $record->isPaid())
                    ->action(function (SaasInvoice $record) {
                        app(PaymentService::class)->handlePaymentSuccess($record);
                        Notification::make()
                            ->title('Invoice ditandai lunas')
                            ->success()
                            ->send();
                    }),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->defaultSort('issued_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSaasInvoices::route('/'),
            'create' => Pages\CreateSaasInvoice::route('/create'),
            'edit' => Pages\EditSaasInvoice::route('/{record}/edit'),
        ];
    }
}

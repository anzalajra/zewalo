<?php

namespace App\Filament\Central\Resources;

use App\Enums\TenantFeature;
use App\Filament\Central\Resources\TenantResource\Pages;
use App\Models\Domain;
use App\Models\SubscriptionPlan;
use App\Models\Tenant;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use UnitEnum;

class TenantResource extends Resource
{
    protected static ?string $model = Tenant::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-building-office-2';

    protected static string|UnitEnum|null $navigationGroup = null;

    public static function getNavigationGroup(): ?string
    {
        return __('admin.nav.tenant_management');
    }

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Tenant')
                    ->tabs([
                        Tab::make('Business Information')
                            ->icon('heroicon-o-building-storefront')
                            ->schema([
                                Section::make('Tenant Identity')
                                    ->schema([
                                        TextInput::make('id')
                                            ->label('Tenant ID')
                                            ->required()
                                            ->unique(ignoreRecord: true)
                                            ->maxLength(255)
                                            ->alphaDash()
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn ($state, callable $set) => $set('id', Str::slug($state)))
                                            ->helperText('Unique identifier for the tenant (used for database name)')
                                            ->disabled(fn ($record) => $record !== null),

                                        TextInput::make('name')
                                            ->label('Company Name')
                                            ->required()
                                            ->maxLength(255),

                                        TextInput::make('email')
                                            ->label('Contact Email')
                                            ->email()
                                            ->required()
                                            ->maxLength(255),

                                        Select::make('tenant_category_id')
                                            ->label('Business Category')
                                            ->relationship('category', 'name')
                                            ->preload()
                                            ->searchable()
                                            ->nullable(),
                                    ])
                                    ->columns(2),

                                Section::make('Domains')
                                    ->schema([
                                        Repeater::make('domains')
                                            ->relationship()
                                            ->schema([
                                                TextInput::make('domain')
                                                    ->label('Domain')
                                                    ->required()
                                                    ->unique(table: Domain::class, column: 'domain', ignoreRecord: true)
                                                    ->maxLength(255)
                                                    ->helperText('e.g., tenant1.example.com'),
                                            ])
                                            ->columns(1)
                                            ->addActionLabel('Add Domain')
                                            ->defaultItems(0)
                                            ->reorderable(false),
                                    ]),
                            ]),

                        Tab::make('Owner Profile')
                            ->icon('heroicon-o-user-circle')
                            ->schema([
                                Section::make('System Admin User')
                                    ->description('The primary administrator of this tenant.')
                                    ->schema([
                                        Placeholder::make('owner_info')
                                            ->label('')
                                            ->content(function ($record) {
                                                if (! $record) {
                                                    return 'Save the tenant first to view owner profile.';
                                                }

                                                try {
                                                    $dbName = 'tenant_'.$record->id;

                                                    // Query the tenant database directly
                                                    $tenantDb = config('database.connections.tenant');
                                                    $tenantDb['database'] = $dbName;
                                                    config(['database.connections.tenant_lookup' => $tenantDb]);
                                                    DB::purge('tenant_lookup');

                                                    $user = DB::connection('tenant_lookup')
                                                        ->table('users')
                                                        ->where('is_system_admin', true)
                                                        ->first(['name', 'email', 'created_at']);

                                                    DB::purge('tenant_lookup');

                                                    if (! $user) {
                                                        return new HtmlString('<p class="text-gray-500">No system admin user found.</p>');
                                                    }

                                                    return new HtmlString(
                                                        '<div class="space-y-2">'.
                                                        '<p><strong>Name:</strong> '.e($user->name).'</p>'.
                                                        '<p><strong>Email:</strong> '.e($user->email).'</p>'.
                                                        '<p><strong>Created:</strong> '.e($user->created_at).'</p>'.
                                                        '</div>'
                                                    );
                                                } catch (\Throwable $e) {
                                                    return new HtmlString('<p class="text-danger-500">Unable to read tenant database: '.e($e->getMessage()).'</p>');
                                                }
                                            })
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        Tab::make('Subscription & Status')
                            ->icon('heroicon-o-credit-card')
                            ->schema([
                                Section::make('Subscription')
                                    ->schema([
                                        Select::make('subscription_plan_id')
                                            ->label('Subscription Plan')
                                            ->relationship('subscriptionPlan', 'name')
                                            ->options(SubscriptionPlan::active()->pluck('name', 'id'))
                                            ->searchable()
                                            ->preload()
                                            ->nullable(),

                                        Select::make('status')
                                            ->label('Status')
                                            ->options([
                                                'trial' => 'Trial',
                                                'active' => 'Active',
                                                'inactive' => 'Inactive',
                                                'suspended' => 'Suspended',
                                            ])
                                            ->default('trial')
                                            ->required(),

                                        Select::make('region')
                                            ->label('Region')
                                            ->options([
                                                'id' => 'Indonesia (IDR)',
                                                'intl' => 'International (USD)',
                                            ])
                                            ->nullable()
                                            ->helperText('Auto-detected at registration. Override only if needed.'),

                                        DateTimePicker::make('trial_ends_at')
                                            ->label('Trial Ends At')
                                            ->nullable(),

                                        DateTimePicker::make('subscription_ends_at')
                                            ->label('Subscription Ends At')
                                            ->nullable(),
                                    ])
                                    ->columns(2),

                                Section::make('Feature Overrides')
                                    ->description('Override fitur bawaan dari Subscription Plan. Biarkan kosong untuk mengikuti pengaturan plan.')
                                    ->schema([
                                        Repeater::make('feature_overrides_form')
                                            ->label('')
                                            ->schema([
                                                Select::make('feature')
                                                    ->label('Fitur')
                                                    ->options(TenantFeature::toOptions())
                                                    ->required()
                                                    ->distinct(),

                                                Toggle::make('enabled')
                                                    ->label('Aktif')
                                                    ->default(true),
                                            ])
                                            ->columns(2)
                                            ->addActionLabel('Tambah Override')
                                            ->defaultItems(0)
                                            ->reorderable(false),
                                    ])
                                    ->collapsed(),

                                Section::make('Additional Data')
                                    ->schema([
                                        KeyValue::make('data')
                                            ->label('Custom Data')
                                            ->keyLabel('Key')
                                            ->valueLabel('Value')
                                            ->addActionLabel('Add Data')
                                            ->nullable(),
                                    ])
                                    ->collapsed(),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Company')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->badge()
                    ->color('gray')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('subscriptionPlan.name')
                    ->label('Plan')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'trial' => 'warning',
                        'inactive' => 'gray',
                        'suspended' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('region')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'id' => 'success',
                        'intl' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'id' => 'Indonesia',
                        'intl' => 'International',
                        default => 'Unset',
                    })
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('domains.domain')
                    ->label('Domains')
                    ->listWithLineBreaks()
                    ->limitList(2)
                    ->expandableLimitedList(),

                Tables\Columns\TextColumn::make('subscription_ends_at')
                    ->label('Expires')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'trial' => 'Trial',
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'suspended' => 'Suspended',
                    ]),

                Tables\Filters\SelectFilter::make('subscription_plan_id')
                    ->label('Plan')
                    ->relationship('subscriptionPlan', 'name'),

                Tables\Filters\SelectFilter::make('region')
                    ->options([
                        'id' => 'Indonesia',
                        'intl' => 'International',
                    ]),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    Action::make('impersonate')
                        ->label('Access Tenant')
                        ->icon('heroicon-o-arrow-top-right-on-square')
                        ->color('info')
                        ->visible(fn (Tenant $record): bool => $record->domains->isNotEmpty())
                        ->url(fn (Tenant $record): string => route('central.impersonate', $record))
                        ->openUrlInNewTab(),
                    Action::make('suspend')
                        ->label('Suspend')
                        ->icon('heroicon-o-pause-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(fn (Tenant $record) => $record->update(['status' => 'suspended']))
                        ->visible(fn (Tenant $record): bool => $record->status !== 'suspended'),
                    Action::make('activate')
                        ->label('Activate')
                        ->icon('heroicon-o-play-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(fn (Tenant $record) => $record->update(['status' => 'active']))
                        ->visible(fn (Tenant $record): bool => $record->status === 'suspended'),
                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    BulkAction::make('suspend_selected')
                        ->label('Suspend Selected')
                        ->icon('heroicon-o-pause-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each->update(['status' => 'suspended'])),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    /**
     * Header action (used on the View & Edit tenant pages) to reset a tenant
     * admin's password from the central panel.
     *
     * Two modes:
     *  - email:  send the branded Filament reset link to the admin's inbox
     *            (same flow as the tenant panel's "forgot password"). The signed
     *            URL is built against the tenant's own domain so the link opens
     *            on the tenant admin panel.
     *  - manual: set a new password immediately (for locked-out tenants). The
     *            superadmin sees the value they typed and can hand it over.
     *
     * All DB work runs inside `$tenant->run()` so it hits the tenant database.
     */
    public static function resetAdminPasswordAction(): Action
    {
        return Action::make('resetAdminPassword')
            ->label('Reset Password Admin')
            ->icon('heroicon-o-key')
            ->color('warning')
            ->modalHeading('Reset Password Admin Toko')
            ->modalDescription('Reset password untuk akun admin di toko (tenant) ini.')
            ->modalSubmitActionLabel('Proses')
            ->form(function (Tenant $record): array {
                $admins = static::getTenantAdminOptions($record);

                return [
                    Select::make('email')
                        ->label('Akun Admin')
                        ->options($admins)
                        ->required()
                        ->searchable()
                        ->native(false)
                        ->helperText(empty($admins)
                            ? 'Tidak ada akun admin ditemukan di toko ini.'
                            : 'Pilih akun admin yang akan direset.'),
                    Radio::make('mode')
                        ->label('Metode')
                        ->options([
                            'email' => 'Kirim tautan reset password ke email admin',
                            'manual' => 'Set password baru secara langsung',
                        ])
                        ->default('email')
                        ->required()
                        ->live(),
                    TextInput::make('password')
                        ->label('Password Baru')
                        ->password()
                        ->revealable()
                        ->minLength(8)
                        ->maxLength(72)
                        ->default(fn () => Str::password(12))
                        ->visible(fn (Get $get) => $get('mode') === 'manual')
                        ->required(fn (Get $get) => $get('mode') === 'manual')
                        ->helperText('Simpan password ini — akan diserahkan ke admin toko. Minimal 8 karakter.'),
                ];
            })
            ->action(function (array $data, Tenant $record): void {
                if (empty($data['email'])) {
                    Notification::make()
                        ->title('Tidak ada akun admin yang dipilih')
                        ->danger()
                        ->send();

                    return;
                }

                if (($data['mode'] ?? 'email') === 'manual') {
                    $ok = static::setTenantAdminPassword($record, $data['email'], $data['password']);

                    if ($ok) {
                        Notification::make()
                            ->title('Password admin berhasil diperbarui')
                            ->body("Akun: {$data['email']}")
                            ->success()
                            ->send();
                    } else {
                        Notification::make()
                            ->title('Gagal memperbarui password')
                            ->body('Akun admin tidak ditemukan di toko ini.')
                            ->danger()
                            ->send();
                    }

                    return;
                }

                $status = static::sendTenantAdminResetLink($record, $data['email']);

                if ($status === Password::RESET_LINK_SENT) {
                    Notification::make()
                        ->title('Tautan reset password terkirim')
                        ->body("Email berisi tautan reset dikirim ke {$data['email']}.")
                        ->success()
                        ->send();
                } else {
                    Notification::make()
                        ->title('Gagal mengirim tautan reset')
                        ->body('Periksa konfigurasi email di Central Admin, atau gunakan metode "Set password baru secara langsung".')
                        ->danger()
                        ->send();
                }
            });
    }

    /**
     * Return [email => "Name (email)"] of admin users inside the tenant DB.
     *
     * @return array<string, string>
     */
    protected static function getTenantAdminOptions(Tenant $tenant): array
    {
        try {
            return $tenant->run(function (): array {
                $format = fn ($users) => $users
                    ->mapWithKeys(fn ($u) => [$u->email => "{$u->name} ({$u->email})"])
                    ->all();

                // Prefer users that can actually access the admin panel.
                $admins = \App\Models\User::query()
                    ->where('is_system_admin', false)
                    ->whereHas('roles', fn ($q) => $q->whereIn('name', ['super_admin', 'admin', 'staff']))
                    ->orderBy('name')
                    ->get(['id', 'name', 'email']);

                if ($admins->isNotEmpty()) {
                    return $format($admins);
                }

                // Fallback for tenants whose admin was never assigned a role
                // (e.g. partially provisioned tenants): list all users so the
                // superadmin can still recover access. Capped to stay usable.
                $all = \App\Models\User::query()
                    ->orderBy('id')
                    ->limit(100)
                    ->get(['id', 'name', 'email']);

                return $format($all);
            });
        } catch (\Throwable $e) {
            return [];
        }
    }

    /**
     * Directly set a new password for the given tenant admin.
     */
    protected static function setTenantAdminPassword(Tenant $tenant, string $email, string $password): bool
    {
        return $tenant->run(function () use ($email, $password): bool {
            $user = \App\Models\User::where('email', $email)->first();

            if (! $user) {
                return false;
            }

            // The `password` cast ('hashed') hashes the value on save.
            $user->update(['password' => $password]);

            return true;
        });
    }

    /**
     * Send the branded Filament reset-password email to the tenant admin, with
     * the signed link pointing at the tenant's own admin panel domain.
     */
    protected static function sendTenantAdminResetLink(Tenant $tenant, string $email): string
    {
        $domain = $tenant->domains()->value('domain');

        if (! $domain) {
            return Password::INVALID_USER;
        }

        return $tenant->run(function () use ($email, $domain): string {
            $previousRoot = config('app.url');

            // Signed reset URL must resolve on the tenant domain, otherwise the
            // link would open on the central panel and fail tenant resolution.
            URL::forceRootUrl('https://' . $domain);

            try {
                return Password::broker('users')->sendResetLink(
                    ['email' => $email],
                    function ($user, string $token): void {
                        $notification = app(\Filament\Auth\Notifications\ResetPassword::class, ['token' => $token]);
                        $notification->url = URL::signedRoute(
                            'filament.admin.auth.password-reset.reset',
                            [
                                'email' => $user->getEmailForPasswordReset(),
                                'token' => $token,
                            ],
                        );

                        $user->notify($notification);
                    },
                );
            } finally {
                URL::forceRootUrl($previousRoot);
            }
        });
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTenants::route('/'),
            'create' => Pages\CreateTenant::route('/create'),
            'view' => Pages\ViewTenant::route('/{record}'),
            'edit' => Pages\EditTenant::route('/{record}/edit'),
        ];
    }
}

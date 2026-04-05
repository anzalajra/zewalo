<?php

namespace App\Filament\Central\Pages;

use App\Models\CentralSetting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use UnitEnum;

class EmailSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-envelope';

    protected static string|UnitEnum|null $navigationGroup = 'System';

    protected static ?int $navigationSort = 11;

    protected static ?string $navigationLabel = 'Email Settings';

    protected string $view = 'filament.central.pages.email-settings';

    public ?array $data = [];

    public static function loadSettings(): array
    {
        try {
            $settings = CentralSetting::getGroup('mail');
            if (! empty($settings)) {
                return $settings;
            }
        } catch (\Exception $e) {
            // DB not available yet
        }

        // Legacy JSON file fallback
        $path = storage_path('app/mail-settings.json');
        if (file_exists($path)) {
            return json_decode(file_get_contents($path), true) ?? [];
        }

        return [];
    }

    protected static function saveSettings(array $settings): void
    {
        $encryptedKeys = ['mail_password', 'aws_secret_access_key'];

        foreach ($settings as $key => $value) {
            CentralSetting::set(
                $key,
                $value ?? '',
                encrypted: in_array($key, $encryptedKeys),
                group: 'mail'
            );
        }
    }

    public function mount(): void
    {
        $saved = static::loadSettings();

        $this->form->fill([
            'mail_mailer'           => $saved['mail_mailer'] ?? config('mail.default'),
            'mail_host'             => $saved['mail_host'] ?? config('mail.mailers.smtp.host'),
            'mail_port'             => $saved['mail_port'] ?? config('mail.mailers.smtp.port'),
            'mail_username'         => $saved['mail_username'] ?? config('mail.mailers.smtp.username'),
            'mail_password'         => $saved['mail_password'] ?? '',
            'mail_encryption'       => $saved['mail_encryption'] ?? config('mail.mailers.smtp.encryption'),
            'aws_access_key_id'     => $saved['aws_access_key_id'] ?? '',
            'aws_secret_access_key' => $saved['aws_secret_access_key'] ?? '',
            'aws_default_region'    => $saved['aws_default_region'] ?? 'ap-southeast-1',
            'mail_from_address'     => $saved['mail_from_address'] ?? config('mail.from.address'),
            'mail_from_name'        => $saved['mail_from_name'] ?? config('mail.from.name'),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('Metode Pengiriman Email')
                    ->description('Pilih driver yang digunakan untuk mengirim semua email dari platform ini.')
                    ->schema([
                        Select::make('mail_mailer')
                            ->label('Mailer / Driver')
                            ->options([
                                'smtp'    => 'SMTP',
                                'sesv2'   => 'Amazon SES v2 (Direkomendasikan)',
                                'ses'     => 'Amazon SES v1',
                                'mailgun' => 'Mailgun',
                                'postmark' => 'Postmark',
                                'log'     => 'Log (Testing Only)',
                            ])
                            ->default('smtp')
                            ->live()
                            ->helperText('Driver sesv2 menggunakan Amazon SES API v2 yang lebih baru dan andal.')
                            ->columnSpanFull(),
                    ]),

                Section::make('Konfigurasi SMTP')
                    ->description('Isi dengan detail server SMTP yang digunakan.')
                    ->schema([
                        TextInput::make('mail_host')
                            ->label('Host')
                            ->placeholder('smtp.gmail.com'),
                        TextInput::make('mail_port')
                            ->label('Port')
                            ->placeholder('587')
                            ->numeric(),
                        TextInput::make('mail_username')
                            ->label('Username')
                            ->placeholder('your-email@gmail.com'),
                        TextInput::make('mail_password')
                            ->label('Password')
                            ->password()
                            ->revealable()
                            ->helperText('Untuk Gmail, gunakan App Password.'),
                        Select::make('mail_encryption')
                            ->label('Enkripsi')
                            ->options([
                                'tls' => 'TLS',
                                'ssl' => 'SSL',
                                ''    => 'None',
                            ])
                            ->default('tls'),
                    ])
                    ->columns(2)
                    ->hidden(fn (Get $get) => $get('mail_mailer') !== 'smtp'),

                Section::make('Konfigurasi Amazon SES')
                    ->description('Masukkan kredensial AWS IAM yang memiliki izin ses:SendEmail dan ses:SendRawEmail.')
                    ->schema([
                        TextInput::make('aws_access_key_id')
                            ->label('AWS Access Key ID')
                            ->placeholder('AKIAxxxxxxxxxxxxxxxxx')
                            ->columnSpanFull(),
                        TextInput::make('aws_secret_access_key')
                            ->label('AWS Secret Access Key')
                            ->password()
                            ->revealable()
                            ->helperText('Disimpan terenkripsi di database.')
                            ->columnSpanFull(),
                        Select::make('aws_default_region')
                            ->label('AWS Region')
                            ->options([
                                'ap-southeast-1' => 'Asia Pacific — Singapore (ap-southeast-1)',
                                'ap-southeast-3' => 'Asia Pacific — Jakarta (ap-southeast-3)',
                                'ap-northeast-1' => 'Asia Pacific — Tokyo (ap-northeast-1)',
                                'us-east-1'      => 'US East — N. Virginia (us-east-1)',
                                'us-west-2'      => 'US West — Oregon (us-west-2)',
                                'eu-west-1'      => 'Europe — Ireland (eu-west-1)',
                                'eu-central-1'   => 'Europe — Frankfurt (eu-central-1)',
                            ])
                            ->default('ap-southeast-1')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->hidden(fn (Get $get) => ! in_array($get('mail_mailer'), ['ses', 'sesv2'])),

                Section::make('Identitas Pengirim')
                    ->description('Alamat dan nama pengirim default. Dapat di-override per-tenant via pengaturan toko.')
                    ->schema([
                        TextInput::make('mail_from_address')
                            ->label('From Address')
                            ->placeholder('no-reply@yourdomain.com')
                            ->email()
                            ->helperText('Pastikan domain/email ini sudah diverifikasi di SES jika menggunakan Amazon SES.'),
                        TextInput::make('mail_from_name')
                            ->label('From Name')
                            ->placeholder('Zewalo')
                            ->helperText('Fallback jika tenant belum mengatur nama pengirim sendiri.'),
                    ])->columns(2),

                Section::make('Uji Koneksi')
                    ->description('Kirim email percobaan untuk memverifikasi konfigurasi sudah benar. Pengaturan akan disimpan terlebih dahulu sebelum tes dikirim.')
                    ->schema([
                        Actions::make([
                            Action::make('testEmail')
                                ->label('Kirim Email Test')
                                ->icon('heroicon-o-paper-airplane')
                                ->color('info')
                                ->form([
                                    TextInput::make('test_email_recipient')
                                        ->label('Alamat Email Tujuan')
                                        ->email()
                                        ->required()
                                        ->helperText('Jika menggunakan SES Sandbox, email penerima harus sudah diverifikasi di AWS SES Console.'),
                                ])
                                ->action(function (array $data) {
                                    $this->sendTestEmail($data['test_email_recipient']);
                                }),
                        ])->columnSpanFull(),
                    ]),
            ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        try {
            static::saveSettings($data);
            $this->applyMailConfig($data);

            Notification::make()
                ->title('Pengaturan email berhasil disimpan')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Gagal menyimpan pengaturan email')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function sendTestEmail(string $recipient): void
    {
        try {
            $data = $this->form->getState();
            static::saveSettings($data);
            $this->applyMailConfig($data);

            $subject = '[Test Email] ' . config('app.name');
            $body = "Ini adalah email percobaan dari " . config('app.name') . ".\n\n"
                . "Jika kamu menerima email ini, konfigurasi email sudah berjalan dengan benar.\n\n"
                . 'Dikirim pada: ' . now()->format('Y-m-d H:i:s');

            Mail::raw($body, function ($message) use ($recipient, $subject) {
                $message->to($recipient)->subject($subject);
            });

            Notification::make()
                ->title('Email test berhasil dikirim!')
                ->body("Email dikirim ke {$recipient}")
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Gagal mengirim email test')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    protected function applyMailConfig(array $data): void
    {
        $mailer = $data['mail_mailer'] ?? 'smtp';

        Config::set('mail.default', $mailer);
        Config::set('mail.from.address', $data['mail_from_address'] ?? null);
        Config::set('mail.from.name', $data['mail_from_name'] ?? config('app.name'));

        if ($mailer === 'smtp') {
            $encryption = $data['mail_encryption'] ?? 'tls';
            Config::set('mail.mailers.smtp.host', $data['mail_host'] ?? null);
            Config::set('mail.mailers.smtp.port', $data['mail_port'] ?? 587);
            Config::set('mail.mailers.smtp.username', $data['mail_username'] ?? null);
            Config::set('mail.mailers.smtp.password', $data['mail_password'] ?? null);
            Config::set('mail.mailers.smtp.encryption', $encryption ?: null);
        }

        if (in_array($mailer, ['ses', 'sesv2'])) {
            Config::set('services.ses.key', $data['aws_access_key_id'] ?? null);
            Config::set('services.ses.secret', $data['aws_secret_access_key'] ?? null);
            Config::set('services.ses.region', $data['aws_default_region'] ?? 'ap-southeast-1');
        }
    }
}

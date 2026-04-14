<?php

namespace App\Filament\Central\Pages;

use App\Models\CentralSetting;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;
use UnitEnum;

class BrandingSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-paint-brush';

    protected static string|UnitEnum|null $navigationGroup = 'System';

    protected static ?int $navigationSort = 9;

    protected static ?string $navigationLabel = 'Branding & SEO';

    protected string $view = 'filament.central.pages.branding-settings';

    public ?array $data = [];

    public static function loadSettings(): array
    {
        try {
            $settings = CentralSetting::getGroup('branding');
            if (! empty($settings)) {
                return $settings;
            }
        } catch (\Exception $e) {
            // DB not available yet
        }

        return [];
    }

    protected static function saveSettings(array $settings): void
    {
        foreach ($settings as $key => $value) {
            // Convert arrays (from TagsInput) to comma-separated string
            if (is_array($value)) {
                $value = implode(', ', $value);
            }

            CentralSetting::set(
                $key,
                $value ?? '',
                encrypted: false,
                group: 'branding'
            );
        }
    }

    public function mount(): void
    {
        $saved = static::loadSettings();

        $keywords = $saved['branding_meta_keywords'] ?? '';
        if (is_string($keywords) && $keywords !== '') {
            $keywords = array_map('trim', explode(',', $keywords));
        } else {
            $keywords = [];
        }

        // Validate file paths exist on R2, clear if not found (prevents infinite loading)
        $fileKeys = ['branding_logo', 'branding_favicon', 'branding_og_image'];
        foreach ($fileKeys as $key) {
            $path = $saved[$key] ?? null;
            if ($path) {
                try {
                    if (! Storage::disk('r2')->exists($path)) {
                        $saved[$key] = null;
                        // Clean up stale setting
                        CentralSetting::set($key, '', encrypted: false, group: 'branding');
                    }
                } catch (\Exception $e) {
                    $saved[$key] = null;
                }
            }
        }

        $this->form->fill([
            'branding_site_name'        => $saved['branding_site_name'] ?? 'Zewalo',
            'branding_site_description' => $saved['branding_site_description'] ?? '',
            'branding_logo'             => $saved['branding_logo'] ?? null,
            'branding_favicon'          => $saved['branding_favicon'] ?? null,
            'branding_meta_keywords'    => $keywords,
            'branding_og_image'         => $saved['branding_og_image'] ?? null,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('Identitas Brand')
                    ->description('Logo dan nama platform yang ditampilkan di landing page, halaman registrasi, login, dan email.')
                    ->schema([
                        TextInput::make('branding_site_name')
                            ->label('Nama Website')
                            ->placeholder('Zewalo')
                            ->required()
                            ->helperText('Nama platform yang ditampilkan di header, footer, email, dan judul halaman.')
                            ->columnSpanFull(),

                        FileUpload::make('branding_logo')
                            ->label('Logo')
                            ->image()
                            ->disk('r2')
                            ->directory('central/branding')
                            ->visibility('public')
                            ->imageResizeMode('contain')
                            ->imageCropAspectRatio(null)
                            ->maxSize(2048)
                            ->helperText('Logo utama platform. Rekomendasi: PNG transparan, minimal 200x60px. Maks 2MB.')
                            ->columnSpan(1),

                        FileUpload::make('branding_favicon')
                            ->label('Favicon')
                            ->image()
                            ->disk('r2')
                            ->directory('central/branding')
                            ->visibility('public')
                            ->maxSize(512)
                            ->helperText('Ikon kecil di tab browser. Rekomendasi: PNG/ICO 32x32px atau 64x64px.')
                            ->columnSpan(1),
                    ])
                    ->columns(2),

                Section::make('SEO & Meta Tags')
                    ->description('Pengaturan untuk optimasi mesin pencari (SEO) dan tampilan saat dibagikan di media sosial.')
                    ->schema([
                        Textarea::make('branding_site_description')
                            ->label('Deskripsi Website')
                            ->placeholder('Platform manajemen rental terbaik. Kelola bisnis penyewaan Anda dalam hitungan menit.')
                            ->rows(3)
                            ->helperText('Deskripsi meta untuk SEO. Tampil di hasil pencarian Google. Rekomendasi: 150-160 karakter.')
                            ->columnSpanFull(),

                        TagsInput::make('branding_meta_keywords')
                            ->label('Meta Keywords')
                            ->placeholder('rental, sewa, manajemen rental')
                            ->helperText('Kata kunci untuk SEO. Tekan Enter untuk menambah keyword baru.')
                            ->columnSpanFull(),

                        FileUpload::make('branding_og_image')
                            ->label('Open Graph Image')
                            ->image()
                            ->disk('r2')
                            ->directory('central/branding')
                            ->visibility('public')
                            ->maxSize(2048)
                            ->helperText('Gambar yang muncul saat link dibagikan di media sosial (Facebook, Twitter, WhatsApp). Rekomendasi: 1200x630px.')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        try {
            // Handle old file cleanup for file uploads
            $fileKeys = ['branding_logo', 'branding_favicon', 'branding_og_image'];
            $saved = static::loadSettings();

            foreach ($fileKeys as $key) {
                $oldValue = $saved[$key] ?? null;
                $newValue = $data[$key] ?? null;

                if ($oldValue && $oldValue !== $newValue) {
                    Storage::disk('r2')->delete($oldValue);
                }
            }

            static::saveSettings($data);

            Notification::make()
                ->title('Pengaturan branding berhasil disimpan')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Gagal menyimpan pengaturan branding')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}

<?php

namespace App\Filament\Central\Pages;

use App\Models\CentralSetting;
use BackedEnum;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use UnitEnum;

class InvoicePdfSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-currency-dollar';

    protected static string|UnitEnum|null $navigationGroup = null;

    protected static ?int $navigationSort = 12;

    protected static ?string $navigationLabel = 'Invoice PDF';

    protected static ?string $title = 'Pengaturan Invoice PDF';

    public static function getNavigationGroup(): ?string
    {
        return __('admin.nav.system');
    }

    protected string $view = 'filament.central.pages.invoice-pdf-settings';

    public ?array $data = [];

    /**
     * Static helper used by the PDF view to read settings with sane defaults.
     * Returns an array merged with defaults so the template never breaks on missing keys.
     */
    public static function loadSettings(): array
    {
        $defaults = static::defaults();

        try {
            $saved = CentralSetting::getGroup('invoice_pdf');
        } catch (\Throwable) {
            $saved = [];
        }

        return array_merge($defaults, array_filter($saved, fn ($v) => $v !== null && $v !== ''));
    }

    public static function defaults(): array
    {
        return [
            'invoice_pdf_primary_color' => '#2563eb',
            'invoice_pdf_text_color' => '#111827',
            'invoice_pdf_muted_color' => '#6b7280',
            'invoice_pdf_accent_bg' => '#f9fafb',
            'invoice_pdf_font_size' => 12,
            'invoice_pdf_paper_size' => 'a4',
            'invoice_pdf_show_logo' => true,
            'invoice_pdf_show_plan_features' => true,
            'invoice_pdf_show_plan_description' => true,
            'invoice_pdf_header_note' => '',
            'invoice_pdf_footer_text' => 'Terima kasih atas kepercayaan Anda.',
            'invoice_pdf_terms_text' => '',
        ];
    }

    public function mount(): void
    {
        $this->form->fill(static::loadSettings());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('Tampilan')
                    ->description('Atur warna, font size, dan kertas PDF.')
                    ->schema([
                        ColorPicker::make('invoice_pdf_primary_color')
                            ->label('Warna Primary')
                            ->required(),

                        ColorPicker::make('invoice_pdf_text_color')
                            ->label('Warna Teks')
                            ->required(),

                        ColorPicker::make('invoice_pdf_muted_color')
                            ->label('Warna Muted')
                            ->required(),

                        ColorPicker::make('invoice_pdf_accent_bg')
                            ->label('Background Aksen')
                            ->required(),

                        TextInput::make('invoice_pdf_font_size')
                            ->label('Font Size (px)')
                            ->numeric()
                            ->minValue(9)
                            ->maxValue(16)
                            ->default(12)
                            ->required(),

                        Select::make('invoice_pdf_paper_size')
                            ->label('Ukuran Kertas')
                            ->options([
                                'a4' => 'A4',
                                'letter' => 'Letter',
                            ])
                            ->default('a4')
                            ->required(),
                    ])
                    ->columns(3),

                Section::make('Konten Invoice')
                    ->description('Atur elemen apa saja yang ditampilkan di PDF.')
                    ->schema([
                        Toggle::make('invoice_pdf_show_logo')
                            ->label('Tampilkan logo central di header')
                            ->default(true),

                        Toggle::make('invoice_pdf_show_plan_description')
                            ->label('Tampilkan deskripsi paket')
                            ->default(true),

                        Toggle::make('invoice_pdf_show_plan_features')
                            ->label('Tampilkan daftar fitur paket')
                            ->default(true),
                    ])
                    ->columns(3),

                Section::make('Teks Tambahan')
                    ->schema([
                        Textarea::make('invoice_pdf_header_note')
                            ->label('Catatan di Header')
                            ->placeholder('Misal: alamat perusahaan, NPWP, dll.')
                            ->rows(2),

                        Textarea::make('invoice_pdf_footer_text')
                            ->label('Teks Footer')
                            ->default('Terima kasih atas kepercayaan Anda.')
                            ->rows(2),

                        Textarea::make('invoice_pdf_terms_text')
                            ->label('Syarat & Ketentuan')
                            ->placeholder('Tampil di bagian bawah invoice. Boleh kosong.')
                            ->rows(3),
                    ]),
            ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            CentralSetting::set($key, $value, encrypted: false, group: 'invoice_pdf');
        }

        Notification::make()
            ->title('Pengaturan Invoice PDF disimpan.')
            ->success()
            ->send();
    }
}

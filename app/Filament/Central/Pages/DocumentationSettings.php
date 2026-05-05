<?php

namespace App\Filament\Central\Pages;

use App\Models\CentralSetting;
use BackedEnum;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Cache;
use UnitEnum;

class DocumentationSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-book-open';

    protected static string|UnitEnum|null $navigationGroup = null;

    protected static ?int $navigationSort = 13;

    protected static ?string $navigationLabel = 'Dokumentasi';

    protected static ?string $title = 'Pengaturan Dokumentasi';

    public static function getNavigationGroup(): ?string
    {
        return __('admin.nav.system');
    }

    protected string $view = 'filament.central.pages.documentation-settings';

    public ?array $data = [];

    public static function loadSettings(): array
    {
        $defaults = static::defaults();

        try {
            $saved = CentralSetting::getGroup('documentation');
        } catch (\Throwable) {
            $saved = [];
        }

        return array_merge($defaults, array_filter($saved, fn ($v) => $v !== null && $v !== ''));
    }

    public static function defaults(): array
    {
        return [
            'documentation_page_title' => 'Dokumentasi',
            'documentation_page_subtitle' => 'Panduan lengkap untuk menggunakan platform.',
            'documentation_intro' => '',
            'documentation_sections' => [],
        ];
    }

    public static function getSections(): array
    {
        $raw = CentralSetting::get('documentation_sections', '[]');

        if (is_string($raw)) {
            $decoded = json_decode($raw, true);
            return is_array($decoded) ? $decoded : [];
        }

        return is_array($raw) ? $raw : [];
    }

    public function mount(): void
    {
        $saved = static::loadSettings();

        $sections = $saved['documentation_sections'] ?? [];
        if (is_string($sections)) {
            $decoded = json_decode($sections, true);
            $sections = is_array($decoded) ? $decoded : [];
        }

        $this->form->fill([
            'documentation_page_title' => $saved['documentation_page_title'] ?? 'Dokumentasi',
            'documentation_page_subtitle' => $saved['documentation_page_subtitle'] ?? '',
            'documentation_intro' => $saved['documentation_intro'] ?? '',
            'documentation_sections' => $sections,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('Header Halaman')
                    ->description('Bagian atas halaman /documentation di zewalo.com.')
                    ->schema([
                        TextInput::make('documentation_page_title')
                            ->label('Judul Halaman')
                            ->placeholder('Dokumentasi')
                            ->required()
                            ->maxLength(120),

                        TextInput::make('documentation_page_subtitle')
                            ->label('Subjudul')
                            ->placeholder('Panduan lengkap untuk menggunakan platform.')
                            ->maxLength(240),

                        Textarea::make('documentation_intro')
                            ->label('Intro / Pengantar (Markdown)')
                            ->placeholder("Tulis intro singkat di sini.\n\nMendukung **bold**, *italic*, dan [link](https://...).")
                            ->rows(4)
                            ->helperText('Mendukung sintaks Markdown (heading, list, link, code, table, image).')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Bagian Dokumentasi')
                    ->description('Tambah, urutkan, dan kelola bagian dokumentasi. Setiap bagian punya judul dan konten Markdown.')
                    ->schema([
                        Repeater::make('documentation_sections')
                            ->label('')
                            ->hiddenLabel()
                            ->schema([
                                TextInput::make('title')
                                    ->label('Judul Bagian')
                                    ->placeholder('Memulai')
                                    ->required()
                                    ->maxLength(160),

                                TextInput::make('slug')
                                    ->label('Slug Anchor')
                                    ->placeholder('memulai')
                                    ->helperText('Untuk anchor link di sidebar (misal #memulai). Hanya huruf kecil, angka, dan tanda hubung.')
                                    ->regex('/^[a-z0-9-]*$/')
                                    ->maxLength(80),

                                Textarea::make('content')
                                    ->label('Konten (Markdown)')
                                    ->placeholder("## Sub-judul\n\nParagraf di sini. Mendukung list, code block, dan lainnya.")
                                    ->rows(10)
                                    ->required()
                                    ->columnSpanFull(),

                                Toggle::make('published')
                                    ->label('Tampilkan di publik')
                                    ->default(true)
                                    ->inline(false),
                            ])
                            ->columns(2)
                            ->collapsible()
                            ->collapsed()
                            ->reorderable()
                            ->cloneable()
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                            ->addActionLabel('Tambah Bagian')
                            ->defaultItems(0),
                    ]),
            ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        try {
            $sections = $data['documentation_sections'] ?? [];

            // Auto-generate slug if empty
            $sections = array_values(array_map(function ($section) {
                $title = $section['title'] ?? '';
                $slug = trim($section['slug'] ?? '');
                if ($slug === '' && $title !== '') {
                    $slug = \Illuminate\Support\Str::slug($title);
                }
                return [
                    'title' => $title,
                    'slug' => $slug,
                    'content' => $section['content'] ?? '',
                    'published' => (bool) ($section['published'] ?? true),
                ];
            }, $sections));

            CentralSetting::set(
                'documentation_page_title',
                $data['documentation_page_title'] ?? 'Dokumentasi',
                encrypted: false,
                group: 'documentation'
            );
            CentralSetting::set(
                'documentation_page_subtitle',
                $data['documentation_page_subtitle'] ?? '',
                encrypted: false,
                group: 'documentation'
            );
            CentralSetting::set(
                'documentation_intro',
                $data['documentation_intro'] ?? '',
                encrypted: false,
                group: 'documentation'
            );
            CentralSetting::set(
                'documentation_sections',
                json_encode($sections, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                encrypted: false,
                group: 'documentation'
            );

            // Bust the public-page render cache
            Cache::forget('public_documentation_render');

            Notification::make()
                ->title('Dokumentasi berhasil disimpan')
                ->success()
                ->send();
        } catch (\Throwable $e) {
            Notification::make()
                ->title('Gagal menyimpan dokumentasi')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}

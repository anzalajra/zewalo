<?php

declare(strict_types=1);

namespace App\Filament\Central\Pages;

use App\Services\LogViewerService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Response;
use Livewire\Attributes\Url;
use UnitEnum;

class LogViewer extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static string|UnitEnum|null $navigationGroup = null;

    protected static ?int $navigationSort = 20;

    protected string $view = 'filament.central.pages.log-viewer';

    #[Url(as: 'file', keep: true)]
    public ?string $selectedFile = null;

    #[Url(as: 'level', keep: true)]
    public ?string $levelFilter = null;

    #[Url(as: 'q', keep: true)]
    public ?string $search = null;

    public int $limit = 500;

    public static function getNavigationGroup(): ?string
    {
        return __('admin.nav.system');
    }

    public static function getNavigationLabel(): string
    {
        return 'Log Viewer';
    }

    public function getTitle(): string
    {
        return 'System Logs';
    }

    public function getHeading(): string
    {
        return 'System Logs';
    }

    public function getSubheading(): ?string
    {
        return 'Lihat log aplikasi, queue worker, scheduler, dan PHP errors — untuk debugging dan support.';
    }

    public function getViewData(): array
    {
        $service = app(LogViewerService::class);

        $files = $service->listFiles();
        $summary = $service->summary();
        $entries = $service->parse(
            fileName: $this->selectedFile,
            level: $this->levelFilter,
            search: $this->search,
            limit: $this->limit,
        );

        return [
            'files' => $files,
            'summary' => $summary,
            'entries' => $entries,
            'levels' => LogViewerService::availableLevels(),
            'selectedFile' => $this->selectedFile,
            'levelFilter' => $this->levelFilter,
            'search' => $this->search,
            'limit' => $this->limit,
            'totalFound' => $entries->count(),
        ];
    }

    public function selectFile(?string $name): void
    {
        $this->selectedFile = $name ?: null;
    }

    public function setLevel(?string $level): void
    {
        $this->levelFilter = $level ?: null;
    }

    public function clearFilters(): void
    {
        $this->selectedFile = null;
        $this->levelFilter = null;
        $this->search = null;
    }

    public function loadMore(): void
    {
        $this->limit += 500;
    }

    public function refreshLogs(): void
    {
        Notification::make()
            ->title('Logs refreshed')
            ->success()
            ->send();
    }

    public function downloadFile(string $fileName)
    {
        $service = app(LogViewerService::class);
        $raw = $service->getRaw($fileName);

        if ($raw === null) {
            Notification::make()
                ->title('File tidak ditemukan')
                ->danger()
                ->send();

            return null;
        }

        return Response::streamDownload(function () use ($raw) {
            echo $raw;
        }, basename($fileName), [
            'Content-Type' => 'text/plain; charset=UTF-8',
        ]);
    }

    public function clearFile(string $fileName): void
    {
        $service = app(LogViewerService::class);

        if ($service->clear($fileName)) {
            Notification::make()
                ->title('Log dibersihkan')
                ->body($fileName.' dikosongkan.')
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('Gagal membersihkan log')
                ->body('File tidak ditemukan atau tidak dapat ditulis.')
                ->danger()
                ->send();
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('refresh')
                ->label('Refresh')
                ->icon('heroicon-o-arrow-path')
                ->action('refreshLogs'),

            Action::make('clearFilters')
                ->label('Clear Filters')
                ->icon('heroicon-o-x-mark')
                ->color('gray')
                ->visible(fn () => $this->selectedFile || $this->levelFilter || $this->search)
                ->action('clearFilters'),
        ];
    }
}

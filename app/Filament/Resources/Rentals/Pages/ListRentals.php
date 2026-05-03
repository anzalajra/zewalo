<?php

namespace App\Filament\Resources\Rentals\Pages;

use App\Filament\Resources\Rentals\RentalResource;
use App\Models\Rental;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Livewire\Attributes\Url;

class ListRentals extends ListRecords
{
    protected static string $resource = RentalResource::class;

    protected string $view = 'filament.resources.rentals.pages.list-rentals';

    public string $currentView = 'list';

    #[Url(as: 'w', except: '')]
    public string $activeWidget = '';

    public function mount(): void
    {
        $this->updateLateStatuses();

        parent::mount();
    }

    public function setView(string $view): void
    {
        $this->currentView = $view;
    }

    public function setActiveWidget(string $key): void
    {
        $this->activeWidget = $this->activeWidget === $key ? '' : $key;
        $this->resetTable();
    }

    /**
     * Available widget metrics. Keys are stable IDs used by JS (localStorage)
     * and the Livewire filter. Order here is the canonical "all widgets" order.
     */
    public function getAvailableWidgets(): array
    {
        return [
            'today_pickup' => [
                'label' => 'Today Pickup',
                'icon' => 'heroicon-o-truck',
                'color' => 'success',
            ],
            'tomorrow_pickup' => [
                'label' => 'Tomorrow Pickup',
                'icon' => 'heroicon-o-calendar-days',
                'color' => 'info',
            ],
            'confirmed' => [
                'label' => 'Confirmed',
                'icon' => 'heroicon-o-check-circle',
                'color' => 'info',
            ],
            'late_return' => [
                'label' => 'Late Return',
                'icon' => 'heroicon-o-clock',
                'color' => 'danger',
            ],
            'late_pickup' => [
                'label' => 'Late Pickup',
                'icon' => 'heroicon-o-exclamation-triangle',
                'color' => 'danger',
            ],
            'active' => [
                'label' => 'Active',
                'icon' => 'heroicon-o-bolt',
                'color' => 'success',
            ],
            'quotation' => [
                'label' => 'Quotation',
                'icon' => 'heroicon-o-document-text',
                'color' => 'warning',
            ],
            'today_return' => [
                'label' => 'Today Return',
                'icon' => 'heroicon-o-arrow-uturn-left',
                'color' => 'warning',
            ],
            'completed_today' => [
                'label' => 'Completed Today',
                'icon' => 'heroicon-o-check-badge',
                'color' => 'gray',
            ],
        ];
    }

    /**
     * Default widget keys shown when user has no saved preferences.
     */
    public function getDefaultWidgetKeys(): array
    {
        return ['today_pickup', 'tomorrow_pickup', 'confirmed', 'late_return'];
    }

    public function getWidgetCounts(): array
    {
        $today = today();
        $tomorrow = today()->addDay();

        return [
            'today_pickup' => Rental::query()
                ->whereDate('start_date', $today)
                ->whereIn('status', [Rental::STATUS_CONFIRMED, Rental::STATUS_LATE_PICKUP])
                ->count(),
            'tomorrow_pickup' => Rental::query()
                ->whereDate('start_date', $tomorrow)
                ->whereIn('status', [Rental::STATUS_QUOTATION, Rental::STATUS_CONFIRMED])
                ->count(),
            'confirmed' => Rental::query()
                ->where('status', Rental::STATUS_CONFIRMED)
                ->count(),
            'late_return' => Rental::query()
                ->where('status', Rental::STATUS_LATE_RETURN)
                ->count(),
            'late_pickup' => Rental::query()
                ->where('status', Rental::STATUS_LATE_PICKUP)
                ->count(),
            'active' => Rental::query()
                ->whereIn('status', [Rental::STATUS_ACTIVE, Rental::STATUS_PARTIAL_RETURN])
                ->count(),
            'quotation' => Rental::query()
                ->where('status', Rental::STATUS_QUOTATION)
                ->count(),
            'today_return' => Rental::query()
                ->whereDate('end_date', $today)
                ->whereIn('status', [Rental::STATUS_ACTIVE, Rental::STATUS_PARTIAL_RETURN, Rental::STATUS_LATE_RETURN])
                ->count(),
            'completed_today' => Rental::query()
                ->whereDate('updated_at', $today)
                ->where('status', Rental::STATUS_COMPLETED)
                ->count(),
        ];
    }

    /**
     * Apply the active widget filter to the table query.
     * Called from RentalsTable via modifyQueryUsing.
     */
    public function applyWidgetFilter($query)
    {
        $today = today();
        $tomorrow = today()->addDay();

        return match ($this->activeWidget) {
            'today_pickup' => $query
                ->whereDate('start_date', $today)
                ->whereIn('status', [Rental::STATUS_CONFIRMED, Rental::STATUS_LATE_PICKUP])
                ->reorder('start_date', 'asc'),
            'tomorrow_pickup' => $query
                ->whereDate('start_date', $tomorrow)
                ->whereIn('status', [Rental::STATUS_QUOTATION, Rental::STATUS_CONFIRMED])
                ->reorder('start_date', 'asc'),
            'confirmed' => $query
                ->where('status', Rental::STATUS_CONFIRMED)
                ->reorder('start_date', 'asc'),
            'late_return' => $query
                ->where('status', Rental::STATUS_LATE_RETURN)
                ->reorder('end_date', 'asc'),
            'late_pickup' => $query
                ->where('status', Rental::STATUS_LATE_PICKUP)
                ->reorder('start_date', 'asc'),
            'active' => $query
                ->whereIn('status', [Rental::STATUS_ACTIVE, Rental::STATUS_PARTIAL_RETURN])
                ->reorder('end_date', 'asc'),
            'quotation' => $query
                ->where('status', Rental::STATUS_QUOTATION)
                ->reorder('created_at', 'desc'),
            'today_return' => $query
                ->whereDate('end_date', $today)
                ->whereIn('status', [Rental::STATUS_ACTIVE, Rental::STATUS_PARTIAL_RETURN, Rental::STATUS_LATE_RETURN])
                ->reorder('end_date', 'asc'),
            'completed_today' => $query
                ->whereDate('updated_at', $today)
                ->where('status', Rental::STATUS_COMPLETED)
                ->reorder('updated_at', 'desc'),
            default => $query,
        };
    }

    public function getStatuses(): array
    {
        return [
            Rental::STATUS_QUOTATION => 'Quotation',
            Rental::STATUS_LATE_PICKUP => 'Late Pickup',
            Rental::STATUS_ACTIVE => 'Active',
            Rental::STATUS_LATE_RETURN => 'Late Return',
            Rental::STATUS_COMPLETED => 'Completed',
            Rental::STATUS_CANCELLED => 'Cancelled',
        ];
    }

    public function getKanbanRecords(): Collection
    {
        return Rental::query()
            ->with(['customer', 'items'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('status');
    }

    protected function updateLateStatuses(): void
    {
        $now = now();

        // Update late pickups - gunakan DB::table untuk bypass model events
        DB::table('rentals')
            ->whereIn('status', ['quotation', 'confirmed'])
            ->where('start_date', '<', $now)
            ->update(['status' => 'late_pickup', 'updated_at' => $now]);

        // Update late returns
        DB::table('rentals')
            ->where('status', 'active')
            ->where('end_date', '<', $now)
            ->update(['status' => 'late_return', 'updated_at' => $now]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('customizeWidget')
                ->label('Customize Widget')
                ->icon('heroicon-o-adjustments-horizontal')
                ->color('gray')
                ->outlined()
                ->extraAttributes([
                    'x-on:click.prevent' => '$dispatch(\'open-widget-customizer\')',
                ]),
            CreateAction::make()->label('New Rental'),
        ];
    }
}

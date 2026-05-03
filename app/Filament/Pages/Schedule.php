<?php

namespace App\Filament\Pages;

use App\Models\Product;
use App\Models\Rental;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Url;
use UnitEnum;
use Livewire\WithPagination;
use Illuminate\Contracts\Pagination\Paginator;

class Schedule extends Page implements HasActions
{
    use InteractsWithActions;
    use WithPagination;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';
    protected static string|UnitEnum|null $navigationGroup = 'Rentals';
    protected static ?string $navigationLabel = 'Schedule';
    protected static ?string $title = 'Schedule';
    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.pages.schedule';

    #[Url(as: 'tab', except: 'order')]
    public string $activeTab = 'order';

    #[Url(as: 'view', except: 'month')]
    public string $calendarView = 'month';

    #[Url(as: 'd')]
    public ?string $cursor = null;

    #[Url(as: 'pd')]
    public ?string $productCursor = null;

    #[Url(except: '')]
    public ?string $search = '';

    public int $perPage = 15;

    /** Window size (days) for By Product view */
    public const PRODUCT_WINDOW_DAYS = 15;

    public function mount(): void
    {
        if (! $this->cursor) {
            $this->cursor = now()->startOfDay()->toDateString();
        }
        if (! $this->productCursor) {
            $this->productCursor = now()->startOfDay()->toDateString();
        }
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function setView(string $view): void
    {
        if (in_array($view, ['month', 'week', 'day'])) {
            $this->calendarView = $view;
        }
    }

    public function gotoToday(): void
    {
        $this->cursor = now()->startOfDay()->toDateString();
    }

    public function navigatePrev(): void
    {
        $this->cursor = $this->cursorCarbon()
            ->sub($this->stepUnit(), 1)
            ->toDateString();
    }

    public function navigateNext(): void
    {
        $this->cursor = $this->cursorCarbon()
            ->add($this->stepUnit(), 1)
            ->toDateString();
    }

    public function productGotoToday(): void
    {
        $this->productCursor = now()->startOfDay()->toDateString();
        $this->resetPage();
    }

    public function productPrev(): void
    {
        $this->productCursor = $this->productCursorCarbon()
            ->subDays(self::PRODUCT_WINDOW_DAYS)
            ->toDateString();
        $this->resetPage();
    }

    public function productNext(): void
    {
        $this->productCursor = $this->productCursorCarbon()
            ->addDays(self::PRODUCT_WINDOW_DAYS)
            ->toDateString();
        $this->resetPage();
    }

    public function productCursorCarbon(): Carbon
    {
        try {
            return Carbon::parse($this->productCursor);
        } catch (\Throwable) {
            return now();
        }
    }

    public function getProductRangeStart(): Carbon
    {
        return $this->productCursorCarbon()->copy()->startOfDay();
    }

    public function getProductRangeEnd(): Carbon
    {
        return $this->productCursorCarbon()->copy()
            ->addDays(self::PRODUCT_WINDOW_DAYS - 1)
            ->endOfDay();
    }

    public function getProductRangeTitle(): string
    {
        $s = $this->getProductRangeStart();
        $e = $this->getProductRangeEnd();
        if ($s->format('Y-m') === $e->format('Y-m')) {
            return $s->translatedFormat('d') . ' – ' . $e->translatedFormat('d F Y');
        }
        return $s->translatedFormat('d M') . ' – ' . $e->translatedFormat('d M Y');
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    protected function stepUnit(): string
    {
        return match ($this->calendarView) {
            'month' => 'month',
            'week'  => 'week',
            'day'   => 'day',
        };
    }

    public function cursorCarbon(): Carbon
    {
        try {
            return Carbon::parse($this->cursor);
        } catch (\Throwable) {
            return now();
        }
    }

    public function getRangeStart(): Carbon
    {
        $c = $this->cursorCarbon();
        return match ($this->calendarView) {
            'month' => $c->copy()->startOfMonth()->startOfWeek(Carbon::MONDAY),
            'week'  => $c->copy()->startOfWeek(Carbon::MONDAY),
            'day'   => $c->copy()->startOfDay(),
        };
    }

    public function getRangeEnd(): Carbon
    {
        $c = $this->cursorCarbon();
        return match ($this->calendarView) {
            'month' => $c->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY),
            'week'  => $c->copy()->endOfWeek(Carbon::SUNDAY),
            'day'   => $c->copy()->endOfDay(),
        };
    }

    public function getTitle(): string
    {
        $c = $this->cursorCarbon();
        return match ($this->calendarView) {
            'month' => $c->translatedFormat('F Y'),
            'week'  => $c->copy()->startOfWeek(Carbon::MONDAY)->translatedFormat('d M') . ' – ' .
                       $c->copy()->endOfWeek(Carbon::SUNDAY)->translatedFormat('d M Y'),
            'day'   => $c->translatedFormat('l, d F Y'),
        };
    }

    /** All rentals overlapping current range */
    public function getRentals(): array
    {
        $start = $this->getRangeStart();
        $end   = $this->getRangeEnd();

        return Rental::with('customer:id,name')
            ->where('start_date', '<', $end)
            ->where('end_date', '>', $start)
            ->orderBy('start_date')
            ->limit(500)
            ->get()
            ->map(fn (Rental $r) => [
                'id'         => $r->id,
                'code'       => $r->rental_code,
                'customer'   => $r->customer?->name ?? '—',
                'status'     => $r->status,
                'start'      => $r->start_date,
                'end'        => $r->end_date,
                'start_iso'  => $r->start_date->toIso8601String(),
                'end_iso'    => $r->end_date->toIso8601String(),
                'total'      => 'Rp ' . number_format($r->total ?? 0, 0, ',', '.'),
            ])->toArray();
    }

    /** Build month grid (6 weeks × 7 days) with rentals laid out per day with overflow */
    public function getMonthGrid(int $maxStack = 3): array
    {
        $start = $this->getRangeStart();
        $end   = $this->getRangeEnd();
        $cursorMonth = (int) $this->cursorCarbon()->format('m');

        $rentals = $this->getRentals();
        $weeks = [];

        for ($d = $start->copy(); $d <= $end; $d->addDay()) {
            $iso = $d->toDateString();
            $items = [];
            foreach ($rentals as $r) {
                if ($d->between($r['start']->copy()->startOfDay(), $r['end']->copy()->endOfDay())) {
                    $items[] = $r;
                }
            }

            $week = intdiv((int) $start->diffInDays($d), 7);
            $weeks[$week][] = [
                'date'      => $iso,
                'day'       => (int) $d->format('j'),
                'isToday'   => $d->isToday(),
                'inMonth'   => (int) $d->format('m') === $cursorMonth,
                'visible'   => array_slice($items, 0, $maxStack),
                'overflow'  => max(0, count($items) - $maxStack),
                'all'       => $items,
            ];
        }

        return $weeks;
    }

    /** Week grid: 7 columns × hour rows; rentals as Gantt bars */
    public function getWeekGrid(): array
    {
        $start = $this->getRangeStart();
        $rentals = $this->getRentals();
        $days = [];

        for ($i = 0; $i < 7; $i++) {
            $d = $start->copy()->addDays($i);
            $days[] = [
                'date'    => $d->toDateString(),
                'short'   => $d->translatedFormat('D'),
                'day'     => (int) $d->format('j'),
                'isToday' => $d->isToday(),
            ];
        }

        // Lane assignment per day: avoid overlaps within same column
        $bars = [];
        foreach ($rentals as $r) {
            $startCol = max(0, (int) $start->diffInDays($r['start']->copy()->startOfDay()));
            $endCol   = min(6, (int) $start->diffInDays($r['end']->copy()->startOfDay()));
            $bars[] = [
                'rental'   => $r,
                'startCol' => (int) $startCol,
                'endCol'   => (int) $endCol,
                'span'     => (int) ($endCol - $startCol + 1),
            ];
        }

        return [
            'days' => $days,
            'bars' => $bars,
        ];
    }

    /** Day timeline: events laid out by hour with overlap-lane assignment */
    public function getDayLayout(): array
    {
        $day = $this->cursorCarbon()->copy()->startOfDay();
        $rentals = $this->getRentals();

        $events = [];
        foreach ($rentals as $r) {
            if (! $day->isSameDay($r['start']) && ! $day->isSameDay($r['end']) && !
                ($day->between($r['start']->copy()->startOfDay(), $r['end']->copy()->endOfDay()))) {
                continue;
            }
            $startMinutes = $day->isSameDay($r['start']) ? ($r['start']->hour * 60 + $r['start']->minute) : 0;
            $endMinutes   = $day->isSameDay($r['end'])   ? ($r['end']->hour * 60 + $r['end']->minute)     : 24 * 60;

            $events[] = [
                'rental'  => $r,
                'startMin'=> $startMinutes,
                'endMin'  => max($startMinutes + 30, $endMinutes),
            ];
        }

        // Lane assignment for overlapping events
        usort($events, fn ($a, $b) => $a['startMin'] <=> $b['startMin']);
        $lanes = [];
        foreach ($events as &$e) {
            $placed = false;
            foreach ($lanes as $li => $endMin) {
                if ($e['startMin'] >= $endMin) {
                    $lanes[$li] = $e['endMin'];
                    $e['lane'] = $li;
                    $placed = true;
                    break;
                }
            }
            if (! $placed) {
                $e['lane'] = count($lanes);
                $lanes[]   = $e['endMin'];
            }
        }
        $totalLanes = max(1, count($lanes));

        return [
            'events'     => $events,
            'totalLanes' => $totalLanes,
        ];
    }

    public function viewRentalDetailsAction(): Action
    {
        return Action::make('viewRentalDetails')
            ->modalHeading('Detail Rental')
            ->modalWidth('2xl')
            ->form(fn (array $arguments) => [
                Grid::make(2)
                    ->schema([
                        TextInput::make('rental_code')->label('Kode Rental')->disabled(),
                        TextInput::make('status')->label('Status')->disabled(),
                        TextInput::make('customer_name')->label('Customer')->disabled(),
                        TextInput::make('total')->label('Total')->disabled(),
                        TextInput::make('start_date')->label('Mulai')->disabled(),
                        TextInput::make('end_date')->label('Selesai')->disabled(),
                        Textarea::make('items')->label('Unit')->rows(3)->disabled()->columnSpanFull(),
                        Textarea::make('notes')->label('Catatan')->disabled()->columnSpanFull(),
                    ]),
            ])
            ->fillForm(function (array $arguments) {
                $rental = Rental::with(['customer', 'items.productUnit.product'])->find($arguments['rentalId']);
                if (! $rental) return [];

                $items = $rental->items->map(function ($item) {
                    $pu = $item->productUnit;
                    return ($pu?->product?->name ?? '-') . ' (' . ($pu->serial_number ?? '-') . ')';
                })->join("\n");

                return [
                    'rental_code'   => $rental->rental_code,
                    'status'        => ucfirst($rental->status),
                    'customer_name' => $rental->customer->name,
                    'total'         => 'Rp ' . number_format($rental->total, 0, ',', '.'),
                    'start_date'    => $rental->start_date->format('d M Y H:i'),
                    'end_date'      => $rental->end_date->format('d M Y H:i'),
                    'items'         => $items,
                    'notes'         => $rental->notes,
                ];
            })
            ->modalFooterActions(fn (array $arguments) => [
                Action::make('viewRentalPage')
                    ->label('Buka Rental')
                    ->color('primary')
                    ->url(fn () => "/admin/rentals/{$arguments['rentalId']}/view"),
            ]);
    }

    /** By-Product table: per-day cells split into hour-aware mini-bars (15-day window) */
    public function getProductsWithUnitsAndRentals(): Paginator
    {
        $rangeStart = $this->getProductRangeStart();
        $rangeEnd   = $this->getProductRangeEnd();

        $query = Product::with(['units.rentalItems.rental.customer'])
            ->whereHas('units');

        $search = trim($this->search ?? '');
        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhereHas('units', function ($q) use ($search) {
                      $q->where('serial_number', 'like', '%' . $search . '%');
                  });
            });
        }

        $products = $query->paginate($this->perPage);

        $days = [];
        for ($d = $rangeStart->copy(); $d <= $rangeEnd; $d->addDay()) {
            $days[] = $d->copy();
        }

        $products->getCollection()->transform(function ($product) use ($rangeStart, $rangeEnd, $days) {
            $productData = [
                'product' => $product,
                'units'   => [],
            ];

            foreach ($product->units as $unit) {
                $rentals = [];
                foreach ($unit->rentalItems as $item) {
                    $rental = $item->rental;
                    if ($rental && $rental->end_date >= $rangeStart && $rental->start_date <= $rangeEnd) {
                        $rentals[] = [
                            'id'       => $rental->id,
                            'code'     => $rental->rental_code,
                            'customer' => $rental->customer?->name ?? '—',
                            'start'    => $rental->start_date,
                            'end'      => $rental->end_date,
                            'status'   => $rental->status,
                        ];
                    }
                }

                // Build per-day mini-bars (option b: proportional position by hour)
                $cells = [];
                foreach ($days as $day) {
                    $dayStart = $day->copy()->startOfDay();
                    $dayEnd   = $day->copy()->endOfDay();
                    $segments = [];
                    foreach ($rentals as $r) {
                        if ($r['end'] < $dayStart || $r['start'] > $dayEnd) continue;

                        $segStart = $r['start']->greaterThan($dayStart) ? $r['start'] : $dayStart;
                        $segEnd   = $r['end']->lessThan($dayEnd) ? $r['end'] : $dayEnd;

                        $startPct = ($segStart->hour * 60 + $segStart->minute) / (24 * 60) * 100;
                        $endPct   = ($segEnd->hour   * 60 + $segEnd->minute)   / (24 * 60) * 100;
                        if ($endPct <= $startPct) $endPct = min(100, $startPct + 4); // min visible width

                        $segments[] = [
                            'rental' => $r,
                            'left'   => round($startPct, 2),
                            'width'  => round($endPct - $startPct, 2),
                            'continuesLeft'  => $r['start'] < $dayStart,
                            'continuesRight' => $r['end']   > $dayEnd,
                        ];
                    }
                    $cells[] = [
                        'date'     => $day->toDateString(),
                        'isToday'  => $day->isToday(),
                        'segments' => $segments,
                    ];
                }

                $productData['units'][] = [
                    'unit'  => $unit,
                    'cells' => $cells,
                ];
            }

            return $productData;
        });

        return $products;
    }

    public function getDaysHeader(): array
    {
        $rangeStart = $this->getProductRangeStart();
        $rangeEnd   = $this->getProductRangeEnd();
        $months = [];
        $days = [];
        for ($d = $rangeStart->copy(); $d <= $rangeEnd; $d->addDay()) {
            $monthKey = $d->format('Y-m');
            if (! isset($months[$monthKey])) {
                $months[$monthKey] = ['label' => $d->translatedFormat('F Y'), 'count' => 0];
            }
            $months[$monthKey]['count']++;
            $days[] = [
                'date'    => $d->toDateString(),
                'short'   => $d->translatedFormat('D'),
                'day'     => (int) $d->format('j'),
                'isToday' => $d->isToday(),
            ];
        }
        return ['months' => array_values($months), 'days' => $days];
    }
}

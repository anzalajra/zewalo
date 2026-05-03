<?php

namespace App\Filament\Widgets;

use App\Models\Rental;
use App\Models\ProductUnit;
use App\Models\User;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class DashboardHomeWidget extends Widget
{
    protected static ?int $sort = -10;

    protected int|string|array $columnSpan = 'full';

    protected string $view = 'filament.widgets.dashboard-home';

    public function getStats(): array
    {
        return Cache::remember('zw_dashboard_home_stats', 120, function () {
            $today = Carbon::today();

            $active = Rental::whereIn('status', [
                Rental::STATUS_ACTIVE,
                Rental::STATUS_LATE_PICKUP,
                Rental::STATUS_LATE_RETURN,
                Rental::STATUS_PARTIAL_RETURN,
            ])->count();

            $quotations = Rental::where('status', Rental::STATUS_QUOTATION)->count();

            $pickups = Rental::whereDate('start_date', $today)
                ->whereIn('status', [
                    Rental::STATUS_CONFIRMED,
                    Rental::STATUS_LATE_PICKUP,
                ])->count();

            $returns = Rental::whereDate('end_date', $today)
                ->whereIn('status', [
                    Rental::STATUS_ACTIVE,
                    Rental::STATUS_LATE_RETURN,
                    Rental::STATUS_PARTIAL_RETURN,
                ])->count();

            $overdue = Rental::whereIn('status', [
                Rental::STATUS_LATE_PICKUP,
                Rental::STATUS_LATE_RETURN,
            ])->count();

            $revenue = Rental::whereMonth('created_at', $today->month)
                ->whereYear('created_at', $today->year)
                ->sum('total');

            return compact('active', 'quotations', 'pickups', 'returns', 'overdue', 'revenue');
        });
    }

    public function getTodaySchedule(): array
    {
        return Cache::remember('zw_dashboard_today_schedule', 120, function () {
            $today = Carbon::today();

            return Rental::with('customer:id,name')
                ->where(function ($q) use ($today) {
                    $q->whereDate('start_date', $today)
                      ->orWhereDate('end_date', $today);
                })
                ->orderBy('start_date')
                ->limit(8)
                ->get()
                ->map(function (Rental $r) use ($today) {
                    $isPickup = $r->start_date->isSameDay($today);
                    return [
                        'id' => $r->id,
                        'code' => $r->rental_code,
                        'customer' => $r->customer?->name ?? '—',
                        'time' => ($isPickup ? $r->start_date : $r->end_date)->format('H:i'),
                        'kind' => $isPickup ? 'Pickup' : 'Return',
                        'status' => $r->status,
                    ];
                })->toArray();
        });
    }

    public function getRecentBookings(): array
    {
        return Cache::remember('zw_dashboard_recent_bookings', 120, function () {
            return Rental::with('customer:id,name')
                ->latest('created_at')
                ->limit(6)
                ->get()
                ->map(fn (Rental $r) => [
                    'id' => $r->id,
                    'code' => $r->rental_code,
                    'customer' => $r->customer?->name ?? '—',
                    'status' => $r->status,
                    'start' => $r->start_date->format('d M'),
                    'total' => 'Rp ' . number_format($r->total ?? 0, 0, ',', '.'),
                ])->toArray();
        });
    }

    protected function getViewData(): array
    {
        return [
            'stats' => $this->getStats(),
            'today' => $this->getTodaySchedule(),
            'recent' => $this->getRecentBookings(),
            'menu' => $this->getMenu(),
            'greeting' => $this->getGreeting(),
            'todayLabel' => Carbon::today()->translatedFormat('l, d F Y'),
        ];
    }

    private function getGreeting(): string
    {
        $h = (int) now()->format('H');
        if ($h < 11) return 'Selamat pagi';
        if ($h < 15) return 'Selamat siang';
        if ($h < 19) return 'Selamat sore';
        return 'Selamat malam';
    }

    private function getMenu(): array
    {
        return [
            ['id' => 'schedule',     'label' => 'Schedule',      'icon' => 'heroicon-o-calendar-days', 'url' => '/admin/schedule', 'desc' => 'Rental calendar'],
            ['id' => 'bookings',     'label' => 'Bookings',      'icon' => 'heroicon-o-clipboard-document-list', 'url' => '/admin/rentals', 'desc' => 'Active rentals & quotes'],
            ['id' => 'inventory',    'label' => 'Inventory',     'icon' => 'heroicon-o-cube', 'url' => '/admin/products', 'desc' => 'Products & units'],
            ['id' => 'deliveries',   'label' => 'Deliveries',    'icon' => 'heroicon-o-truck', 'url' => '/admin/deliveries', 'desc' => 'Pickup & return'],
            ['id' => 'customers',    'label' => 'Customers',     'icon' => 'heroicon-o-users', 'url' => '/admin/customers', 'desc' => 'Customer directory'],
            ['id' => 'invoices',     'label' => 'Invoices',      'icon' => 'heroicon-o-document-text', 'url' => '/admin/invoices', 'desc' => 'Payments & billing'],
            ['id' => 'finance',      'label' => 'Finance',       'icon' => 'heroicon-o-banknotes', 'url' => '/admin/finance', 'desc' => 'Journal & reports'],
            ['id' => 'settings',     'label' => 'Settings',      'icon' => 'heroicon-o-cog-6-tooth', 'url' => '/admin/settings/general', 'desc' => 'Preferences'],
        ];
    }
}

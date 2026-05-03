<?php

namespace App\Filament\Widgets;

use App\Models\Rental;
use App\Models\Setting;
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
            $tomorrow = Carbon::tomorrow();

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

            $pickupsTomorrow = Rental::whereDate('start_date', $tomorrow)
                ->whereIn('status', [
                    Rental::STATUS_CONFIRMED,
                    Rental::STATUS_QUOTATION,
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

            return compact('active', 'quotations', 'pickups', 'pickupsTomorrow', 'returns', 'overdue', 'revenue');
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
            return Rental::with(['customer:id,name', 'items:id,rental_id,product_id', 'items.product:id,name'])
                ->latest('created_at')
                ->limit(6)
                ->get()
                ->map(function (Rental $r) {
                    $first = $r->items->first();
                    $itemName = $first?->product?->name ?? '—';
                    if ($r->items->count() > 1) {
                        $itemName .= ' +' . ($r->items->count() - 1);
                    }

                    return [
                        'id' => $r->id,
                        'code' => $r->rental_code,
                        'customer' => $r->customer?->name ?? '—',
                        'item' => $itemName,
                        'status' => $r->status,
                        'start' => $r->start_date->format('d M'),
                        'total' => 'Rp ' . number_format($r->total ?? 0, 0, ',', '.'),
                        'totalRaw' => (float) ($r->total ?? 0),
                    ];
                })->toArray();
        });
    }

    /**
     * Last 12 months of bookings + revenue. Returned as array of
     * ['month' => 'May', 'bookings' => N, 'revenue' => N] in chronological order.
     */
    public function getMonthlyStats(): array
    {
        return Cache::remember('zw_dashboard_monthly_stats', 600, function () {
            $now = Carbon::now()->startOfMonth();
            $start = (clone $now)->subMonths(11);

            $rows = Rental::query()
                ->selectRaw("date_trunc('month', created_at) as bucket, COUNT(*) as bookings, COALESCE(SUM(total),0) as revenue")
                ->where('created_at', '>=', $start)
                ->groupByRaw("date_trunc('month', created_at)")
                ->get()
                ->keyBy(fn ($r) => Carbon::parse($r->bucket)->format('Y-m'));

            $months = [];
            for ($i = 0; $i < 12; $i++) {
                $cursor = (clone $start)->addMonths($i);
                $key = $cursor->format('Y-m');
                $row = $rows->get($key);
                $months[] = [
                    'month' => $cursor->translatedFormat('M'),
                    'bookings' => (int) ($row->bookings ?? 0),
                    'revenue' => (float) ($row->revenue ?? 0),
                ];
            }
            return $months;
        });
    }

    /**
     * Announcements. For now we read tenant Setting key `announcements_json`,
     * fallback to a single welcome announcement if none set.
     */
    public function getAnnouncements(): array
    {
        $raw = Setting::get('announcements_json');
        if ($raw) {
            $decoded = is_array($raw) ? $raw : json_decode($raw, true);
            if (is_array($decoded) && count($decoded) > 0) {
                return array_values(array_filter(array_map(function ($a) {
                    if (! is_array($a)) return null;
                    return [
                        'label' => $a['label'] ?? 'Pengumuman',
                        'title' => $a['title'] ?? '',
                        'body'  => $a['body']  ?? '',
                    ];
                }, $decoded)));
            }
        }

        return [
            [
                'label' => 'Selamat Datang',
                'title' => '🎉 Dashboard Baru Zewalo',
                'body'  => 'Tampilan home telah diperbarui. Akses menu cepat, lihat statistik, dan kelola booking dari satu halaman.',
            ],
            [
                'label' => 'Tips',
                'title' => '💡 Aktifkan notifikasi WhatsApp',
                'body'  => 'Buka Pengaturan → WhatsApp untuk mengingatkan pelanggan saat masa sewa hampir berakhir.',
            ],
            [
                'label' => 'Update Fitur',
                'title' => '📅 Schedule kalender baru',
                'body'  => 'Kalender Schedule kini punya view By Order & By Product dengan bar per-jam yang lebih akurat.',
            ],
        ];
    }

    protected function getViewData(): array
    {
        return [
            'stats' => $this->getStats(),
            'today' => $this->getTodaySchedule(),
            'recent' => $this->getRecentBookings(),
            'monthly' => $this->getMonthlyStats(),
            'announcements' => $this->getAnnouncements(),
            'menu' => $this->getMenu(),
            'greeting' => $this->getGreeting(),
            'todayLabel' => Carbon::today()->translatedFormat('l, d F Y'),
            'firstName' => explode(' ', auth()->user()?->name ?? 'User')[0],
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

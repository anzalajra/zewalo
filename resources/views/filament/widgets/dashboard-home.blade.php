@php
    $statusColors = [
        'quotation'      => ['solid' => '#f97316', 'bg' => '#fff7ed', 'fg' => '#c2410c', 'label' => 'Quotation'],
        'confirmed'      => ['solid' => '#3b82f6', 'bg' => '#eff6ff', 'fg' => '#1d4ed8', 'label' => 'Confirmed'],
        'active'         => ['solid' => '#22c55e', 'bg' => '#f0fdf4', 'fg' => '#15803d', 'label' => 'Active'],
        'completed'      => ['solid' => '#a855f7', 'bg' => '#faf5ff', 'fg' => '#7e22ce', 'label' => 'Done'],
        'cancelled'      => ['solid' => '#6b7280', 'bg' => '#f9fafb', 'fg' => '#374151', 'label' => 'Cancel'],
        'late_pickup'    => ['solid' => '#ef4444', 'bg' => '#fef2f2', 'fg' => '#b91c1c', 'label' => 'Late'],
        'late_return'    => ['solid' => '#ef4444', 'bg' => '#fef2f2', 'fg' => '#b91c1c', 'label' => 'Late'],
        'partial_return' => ['solid' => '#eab308', 'bg' => '#fefce8', 'fg' => '#854d0e', 'label' => 'Partial'],
    ];
@endphp

<x-filament-widgets::widget>
    <div class="zw-home" wire:ignore.self>

        {{-- Greeting --}}
        <div class="zw-home__greet">
            <div class="zw-home__date">{{ $todayLabel }}</div>
            <div class="zw-home__hello">{{ $greeting }}, {{ explode(' ', auth()->user()?->name ?? 'User')[0] }} 👋</div>
            <div class="zw-home__sub zw-hide-mobile">
                {{ $stats['pickups'] }} pickup &middot; {{ $stats['returns'] }} return hari ini
            </div>
        </div>

        {{-- Overview stats (tablet & desktop only) --}}
        <div class="zw-section zw-hide-mobile">
            <div class="zw-section__title">Overview</div>
            <div class="zw-stats">
                @foreach ([
                    ['label' => 'Active',     'value' => $stats['active'],     'sub' => 'rentals',    'color' => '#22c55e', 'icon' => 'heroicon-o-clipboard-document-list'],
                    ['label' => 'Quotations', 'value' => $stats['quotations'], 'sub' => 'pending',    'color' => '#a855f7', 'icon' => 'heroicon-o-document-text'],
                    ['label' => 'Pickups',    'value' => $stats['pickups'],    'sub' => 'today',      'color' => '#f97316', 'icon' => 'heroicon-o-truck'],
                    ['label' => 'Returns',    'value' => $stats['returns'],    'sub' => 'today',      'color' => '#3b82f6', 'icon' => 'heroicon-o-cube'],
                    ['label' => 'Overdue',    'value' => $stats['overdue'],    'sub' => 'bookings',   'color' => '#ef4444', 'icon' => 'heroicon-o-bell-alert'],
                    ['label' => 'Revenue',    'value' => 'Rp ' . number_format($stats['revenue'] / 1_000_000, 1) . 'jt', 'sub' => 'this month', 'color' => '#0284c7', 'icon' => 'heroicon-o-banknotes'],
                ] as $s)
                    <div class="zw-stat">
                        <div class="zw-stat__icon" style="background:{{ $s['color'] }}1f; color:{{ $s['color'] }}">
                            <x-dynamic-component :component="$s['icon']" class="w-4 h-4"/>
                        </div>
                        <div class="zw-stat__value">{{ $s['value'] }}</div>
                        <div class="zw-stat__meta">
                            <span class="zw-stat__label">{{ $s['label'] }}</span>
                            <span class="zw-stat__sub">{{ $s['sub'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Menu grid (mobile = 2 col, tablet/desktop = 4 col) --}}
        <div class="zw-section">
            <div class="zw-section__title zw-hide-mobile">Menu</div>
            <div class="zw-menu">
                @foreach ($menu as $m)
                    <a href="{{ $m['url'] }}" class="zw-menu__item" wire:navigate>
                        <div class="zw-menu__icon @if($m['id'] === 'schedule') zw-menu__icon--primary @endif">
                            <x-dynamic-component :component="$m['icon']" class="w-6 h-6"/>
                        </div>
                        <div class="zw-menu__text">
                            <div class="zw-menu__label">{{ $m['label'] }}</div>
                            <div class="zw-menu__desc zw-show-mobile-only">{{ $m['desc'] }}</div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Today's Schedule (tablet & desktop only) --}}
        @if (!empty($today))
            <div class="zw-section zw-hide-mobile">
                <div class="zw-section__head">
                    <div class="zw-section__title">Jadwal Hari Ini</div>
                    <a href="/admin/schedule" class="zw-link" wire:navigate>Lihat semua →</a>
                </div>
                <div class="zw-card zw-list">
                    @foreach ($today as $t)
                        @php $c = $statusColors[$t['status']] ?? $statusColors['cancelled']; @endphp
                        <a href="/admin/rentals/{{ $t['id'] }}" class="zw-list__item" wire:navigate>
                            <div class="zw-list__time">{{ $t['time'] }}</div>
                            <div class="zw-list__bar" style="background:{{ $c['solid'] }}"></div>
                            <div class="zw-list__body">
                                <div class="zw-list__name">{{ $t['customer'] }}</div>
                                <div class="zw-list__meta">{{ $t['kind'] }} &middot; #{{ $t['code'] }}</div>
                            </div>
                            <span class="zw-pill" style="background:{{ $c['bg'] }};color:{{ $c['fg'] }}">{{ $c['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Recent Bookings (desktop only) --}}
        @if (!empty($recent))
            <div class="zw-section zw-show-desktop-only">
                <div class="zw-section__title">Booking Terbaru</div>
                <div class="zw-card">
                    <div class="zw-table__head">
                        <div>Booking</div><div>Customer</div><div>Status</div><div>Mulai</div><div>Total</div>
                    </div>
                    @foreach ($recent as $b)
                        @php $c = $statusColors[$b['status']] ?? $statusColors['cancelled']; @endphp
                        <a href="/admin/rentals/{{ $b['id'] }}" class="zw-table__row" wire:navigate>
                            <div class="zw-mono">{{ $b['code'] }}</div>
                            <div class="zw-truncate">{{ $b['customer'] }}</div>
                            <div>
                                <span class="zw-pill" style="background:{{ $c['bg'] }};color:{{ $c['fg'] }}">
                                    <span class="zw-dot" style="background:{{ $c['solid'] }}"></span>{{ $c['label'] }}
                                </span>
                            </div>
                            <div class="zw-muted">{{ $b['start'] }}</div>
                            <div class="zw-bold">{{ $b['total'] }}</div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

    @once
        @push('styles')
        <style>
            .zw-home { display:flex; flex-direction:column; gap:18px; font-family: 'Figtree', ui-sans-serif, system-ui, sans-serif; color:#111827; }
            .zw-home__greet { background:#fff; border:1px solid #f3f4f6; border-radius:14px; padding:16px 18px; }
            .zw-home__date { font-size:12px; color:#6b7280; font-weight:600; }
            .zw-home__hello { font-size:22px; font-weight:800; letter-spacing:-0.4px; margin-top:2px; }
            .zw-home__sub { font-size:13px; color:#6b7280; margin-top:4px; }

            .dark .zw-home__greet { background:#1f2937; border-color:#374151; }
            .dark .zw-home__hello { color:#f9fafb; }
            .dark .zw-home__date, .dark .zw-home__sub { color:#9ca3af; }

            .zw-section { display:flex; flex-direction:column; gap:10px; }
            .zw-section__title { font-size:13px; font-weight:800; color:#374151; letter-spacing:-0.1px; }
            .zw-section__head { display:flex; justify-content:space-between; align-items:center; }
            .zw-link { font-size:12px; font-weight:700; color:#0284c7; text-decoration:none; }
            .dark .zw-section__title { color:#d1d5db; }

            /* Stats */
            .zw-stats { display:grid; grid-template-columns:repeat(3, minmax(0, 1fr)); gap:10px; }
            @media (min-width:1024px) { .zw-stats { grid-template-columns:repeat(6, minmax(0, 1fr)); } }
            .zw-stat { background:#fff; border:1px solid #f3f4f6; border-radius:12px; padding:12px; box-shadow:0 1px 2px rgba(0,0,0,0.03); }
            .dark .zw-stat { background:#1f2937; border-color:#374151; }
            .zw-stat__icon { width:30px; height:30px; border-radius:8px; display:grid; place-items:center; margin-bottom:8px; }
            .zw-stat__value { font-size:20px; font-weight:800; letter-spacing:-0.5px; line-height:1; }
            .dark .zw-stat__value { color:#f9fafb; }
            .zw-stat__meta { font-size:11px; font-weight:600; color:#6b7280; margin-top:4px; display:flex; flex-direction:column; }
            .zw-stat__sub { color:#9ca3af; font-weight:500; }

            /* Menu */
            .zw-menu { display:grid; grid-template-columns:repeat(2, minmax(0, 1fr)); gap:12px; }
            @media (min-width:768px) { .zw-menu { grid-template-columns:repeat(4, minmax(0, 1fr)); gap:10px; } }
            .zw-menu__item {
                background:#fff; border:1px solid #f3f4f6; border-radius:16px;
                padding:18px 14px; display:flex; align-items:center; gap:14px;
                text-decoration:none; color:inherit; box-shadow:0 1px 3px rgba(0,0,0,0.04);
                transition:transform .12s ease, box-shadow .12s ease;
            }
            .zw-menu__item:hover { transform:translateY(-1px); box-shadow:0 4px 12px rgba(0,0,0,0.06); }
            .dark .zw-menu__item { background:#1f2937; border-color:#374151; }
            @media (min-width:768px) { .zw-menu__item { flex-direction:column; text-align:center; gap:8px; padding:16px 12px; } }
            .zw-menu__icon { width:46px; height:46px; flex-shrink:0; border-radius:14px; background:#f0f9ff; color:#0284c7; display:grid; place-items:center; }
            @media (min-width:768px) { .zw-menu__icon { width:40px; height:40px; border-radius:12px; } .zw-menu__icon svg { width:20px; height:20px; } }
            .dark .zw-menu__icon { background:#0c4a6e; color:#7dd3fc; }
            .zw-menu__icon--primary { background:#0284c7; color:#fff; }
            .dark .zw-menu__icon--primary { background:#0284c7; color:#fff; }
            .zw-menu__label { font-size:14px; font-weight:700; line-height:1.3; }
            .dark .zw-menu__label { color:#f9fafb; }
            @media (min-width:768px) { .zw-menu__label { font-size:12px; } }
            .zw-menu__desc { font-size:11px; color:#6b7280; margin-top:2px; }

            /* Lists / cards */
            .zw-card { background:#fff; border:1px solid #f3f4f6; border-radius:12px; overflow:hidden; }
            .dark .zw-card { background:#1f2937; border-color:#374151; }
            .zw-list__item { display:flex; align-items:center; gap:12px; padding:11px 14px; border-top:1px solid #f3f4f6; text-decoration:none; color:inherit; }
            .zw-list__item:first-child { border-top:none; }
            .dark .zw-list__item { border-color:#374151; }
            .zw-list__time { font-size:12px; font-weight:700; color:#6b7280; width:50px; text-align:right; flex-shrink:0; }
            .zw-list__bar { width:3px; height:32px; border-radius:99px; flex-shrink:0; }
            .zw-list__body { flex:1; min-width:0; }
            .zw-list__name { font-size:13px; font-weight:700; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
            .dark .zw-list__name { color:#f9fafb; }
            .zw-list__meta { font-size:11px; color:#6b7280; margin-top:1px; }

            .zw-pill { display:inline-flex; align-items:center; gap:5px; padding:3px 10px; border-radius:99px; font-size:10px; font-weight:700; }
            .zw-dot { width:5px; height:5px; border-radius:99px; }

            /* Recent table (desktop) */
            .zw-table__head { display:grid; grid-template-columns:1fr 1.4fr 1.2fr 0.8fr 0.8fr; padding:10px 16px; background:#f9fafb; gap:10px; font-size:10px; font-weight:800; color:#6b7280; text-transform:uppercase; letter-spacing:0.3px; }
            .dark .zw-table__head { background:#111827; }
            .zw-table__row { display:grid; grid-template-columns:1fr 1.4fr 1.2fr 0.8fr 0.8fr; padding:11px 16px; gap:10px; align-items:center; border-top:1px solid #f3f4f6; text-decoration:none; color:inherit; font-size:12px; }
            .dark .zw-table__row { border-color:#374151; }
            .zw-mono { font-family:ui-monospace, monospace; font-size:11px; color:#0369a1; font-weight:700; }
            .zw-truncate { overflow:hidden; text-overflow:ellipsis; white-space:nowrap; font-weight:600; }
            .zw-muted { color:#4b5563; }
            .zw-bold { font-weight:700; }

            /* Responsive helpers */
            @media (max-width:767px) {
                .zw-hide-mobile { display:none !important; }
            }
            @media (min-width:768px) {
                .zw-show-mobile-only { display:none !important; }
            }
            @media (max-width:1023px) {
                .zw-show-desktop-only { display:none !important; }
            }
        </style>
        @endpush
    @endonce
</x-filament-widgets::widget>

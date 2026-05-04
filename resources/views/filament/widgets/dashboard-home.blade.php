@php
    $statusColors = [
        'quotation'      => ['solid' => '#f97316', 'bg' => '#fff7ed', 'fg' => '#c2410c', 'label' => 'Quotation'],
        'confirmed'      => ['solid' => '#3b82f6', 'bg' => '#eff6ff', 'fg' => '#1d4ed8', 'label' => 'Confirmed'],
        'active'         => ['solid' => '#22c55e', 'bg' => '#dcfce7', 'fg' => '#15803d', 'label' => 'Active'],
        'completed'      => ['solid' => '#a855f7', 'bg' => '#faf5ff', 'fg' => '#7e22ce', 'label' => 'Done'],
        'cancelled'      => ['solid' => '#6b7280', 'bg' => '#f3f4f6', 'fg' => '#374151', 'label' => 'Cancel'],
        'late_pickup'    => ['solid' => '#ef4444', 'bg' => '#fee2e2', 'fg' => '#b91c1c', 'label' => 'Late'],
        'late_return'    => ['solid' => '#ef4444', 'bg' => '#fee2e2', 'fg' => '#b91c1c', 'label' => 'Late'],
        'partial_return' => ['solid' => '#eab308', 'bg' => '#fefce8', 'fg' => '#854d0e', 'label' => 'Partial'],
    ];

    $monthlyJson = json_encode($monthly, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_HEX_TAG | JSON_UNESCAPED_UNICODE);
    $announcementsJson = json_encode($announcements, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_HEX_TAG | JSON_UNESCAPED_UNICODE);
@endphp

<x-filament-widgets::widget>
<div class="zw-home" x-data='zwDashboard({!! $monthlyJson !!})' wire:ignore.self>

    {{-- ─────────── DESKTOP / TABLET ─────────── --}}
    <div class="zw-desktop">

        {{-- Welcome row --}}
        <div class="zw-welcome">
            <div class="zw-welcome__date">{{ $todayLabel }}</div>
            <div class="zw-welcome__hello">{{ $greeting }}, {{ $firstName }} 👋</div>
            <div class="zw-welcome__sub">
                Ada <strong>{{ $stats['pickups'] }} pickup</strong> dan
                <strong>{{ $stats['returns'] }} return</strong> hari ini.
            </div>
        </div>

        {{-- Row 2: Banner + 2x2 stats --}}
        <div class="zw-row2">
            {{-- Announcement carousel --}}
            <div class="zw-banner" x-data='{ idx: 0, items: {!! $announcementsJson !!} }'
                 x-init="setInterval(() => { idx = (idx + 1) % items.length }, 6000)">
                <template x-for="(a, i) in items" :key="i">
                    <div x-show="idx === i" x-transition.opacity.duration.300ms class="zw-banner__inner">
                        <div class="zw-banner__label" x-text="a.label"></div>
                        <div class="zw-banner__title" x-text="a.title"></div>
                        <div class="zw-banner__body" x-text="a.body"></div>
                    </div>
                </template>
                <div class="zw-banner__footer">
                    <div class="zw-banner__dots">
                        <template x-for="(a, i) in items" :key="i">
                            <button @click="idx = i" class="zw-banner__dot" :class="{ 'is-active': idx === i }"></button>
                        </template>
                    </div>
                    <div class="zw-banner__count">
                        <span x-text="idx + 1"></span> / <span x-text="items.length"></span>
                    </div>
                </div>
            </div>

            {{-- 2x2 stat grid --}}
            <div class="zw-stats4">
                @php
                    $cards = [
                        ['label' => 'Active Rental',   'value' => $stats['active'],          'icon' => 'heroicon-o-clipboard-document-list', 'bg' => '#f0f9ff', 'fg' => '#0284c7', 'url' => '/admin/rentals'],
                        ['label' => 'Pickup Today',    'value' => $stats['pickups'],         'icon' => 'heroicon-o-truck',                   'bg' => '#eff6ff', 'fg' => '#2563eb', 'url' => '/admin/schedule'],
                        ['label' => 'Pickup Tomorrow', 'value' => $stats['pickupsTomorrow'], 'icon' => 'heroicon-o-calendar-days',           'bg' => '#fefce8', 'fg' => '#ca8a04', 'url' => '/admin/schedule'],
                        ['label' => 'Overdue Rental',  'value' => $stats['overdue'],         'icon' => 'heroicon-o-exclamation-triangle',    'bg' => '#fef2f2', 'fg' => '#dc2626', 'url' => '/admin/rentals'],
                    ];
                @endphp
                @foreach ($cards as $s)
                    <a href="{{ $s['url'] }}" class="zw-stat4" wire:navigate>
                        <div class="zw-stat4__top">
                            <div class="zw-stat4__value" style="color: {{ $s['fg'] }}">{{ $s['value'] }}</div>
                            <div class="zw-stat4__icon" style="background: {{ $s['bg'] }}; color: {{ $s['fg'] }}">
                                <x-dynamic-component :component="$s['icon']" class="w-5 h-5"/>
                            </div>
                        </div>
                        <div class="zw-stat4__label">{{ $s['label'] }}</div>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Row 3: Recent table + Chart --}}
        <div class="zw-row3">
            {{-- Recent Bookings --}}
            <div class="zw-card">
                <div class="zw-card__head">
                    <span class="zw-card__title">Recent Bookings</span>
                    <a href="/admin/rentals" class="zw-card__action" wire:navigate>View all →</a>
                </div>
                <div class="zw-tablewrap">
                    <table class="zw-table">
                        <thead>
                            <tr>
                                <th>Booking ID</th>
                                <th>Customer</th>
                                <th>Item</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recent as $b)
                                @php $c = $statusColors[$b['status']] ?? $statusColors['cancelled']; @endphp
                                <tr onclick="window.location='/admin/rentals/{{ $b['id'] }}'" style="cursor:pointer">
                                    <td class="zw-mono">{{ $b['code'] }}</td>
                                    <td class="zw-table__strong">{{ $b['customer'] }}</td>
                                    <td class="zw-table__muted">{{ $b['item'] }}</td>
                                    <td class="zw-table__date">{{ $b['start'] }}</td>
                                    <td class="zw-table__bold">{{ $b['total'] }}</td>
                                    <td><span class="zw-pill" style="background:{{ $c['bg'] }};color:{{ $c['fg'] }}">{{ $c['label'] }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="zw-empty">Belum ada booking</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Chart --}}
            <div class="zw-card zw-chart">
                <div class="zw-card__head zw-chart__head">
                    <span class="zw-card__title">Statistik</span>
                    <div class="zw-chart__controls">
                        <label class="zw-check" @click.prevent="showB = !showB">
                            <span class="zw-check__box" :class="{ 'is-on': showB }" :style="showB ? 'background:#0284c7;border-color:#0284c7' : ''">
                                <svg x-show="showB" width="9" height="9" viewBox="0 0 12 12" fill="none"><path d="M2 6l3 3 5-5" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </span>
                            <span :class="showB ? 'zw-check__on' : 'zw-check__off'">Booking</span>
                        </label>
                        <label class="zw-check" @click.prevent="showR = !showR">
                            <span class="zw-check__box" :class="{ 'is-on': showR }" :style="showR ? 'background:#10b981;border-color:#10b981' : ''">
                                <svg x-show="showR" width="9" height="9" viewBox="0 0 12 12" fill="none"><path d="M2 6l3 3 5-5" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </span>
                            <span :class="showR ? 'zw-check__on' : 'zw-check__off'">Revenue</span>
                        </label>
                        <select x-model.number="period" class="zw-select">
                            <option value="1">1 Bulan</option>
                            <option value="3">3 Bulan</option>
                            <option value="6">6 Bulan</option>
                            <option value="12">12 Bulan</option>
                        </select>
                    </div>
                </div>
                <div class="zw-card__body">
                    <div x-show="showB || showR" class="zw-chart__svgwrap"
                         @mousemove="onChartHover($event)" @mouseleave="hover = null">
                        <svg :viewBox="`0 0 ${W} ${H}`" preserveAspectRatio="none" style="width:100%;height:140px;display:block;overflow:visible">
                            <defs>
                                <linearGradient id="zwGradB" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#0284c7" stop-opacity="0.18"/>
                                    <stop offset="100%" stop-color="#0284c7" stop-opacity="0.02"/>
                                </linearGradient>
                                <linearGradient id="zwGradR" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#10b981" stop-opacity="0.18"/>
                                    <stop offset="100%" stop-color="#10b981" stop-opacity="0.02"/>
                                </linearGradient>
                            </defs>
                            <template x-for="(d, i) in slice" :key="i">
                                <text :x="xAt(i)" :y="H - 4" text-anchor="middle" font-size="9" fill="#9ca3af" x-text="d.month"></text>
                            </template>
                            <line x-show="hover !== null" :x1="hoverX" :x2="hoverX" :y1="PAD.t" :y2="PAD.t + INNER.h" stroke="#e5e7eb" stroke-dasharray="3,3"/>
                            <path x-show="showR" :d="rArea" fill="url(#zwGradR)"/>
                            <path x-show="showR" :d="rPath" fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path x-show="showB" :d="bArea" fill="url(#zwGradB)"/>
                            <path x-show="showB" :d="bPath" fill="none" stroke="#0284c7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <template x-if="hover !== null">
                                <g>
                                    <circle x-show="showB" :cx="bPts[hover].x" :cy="bPts[hover].y" r="4" fill="#fff" stroke="#0284c7" stroke-width="2"/>
                                    <circle x-show="showR" :cx="rPts[hover].x" :cy="rPts[hover].y" r="4" fill="#fff" stroke="#10b981" stroke-width="2"/>
                                </g>
                            </template>
                        </svg>
                        <div x-show="hover !== null" class="zw-chart__tip"
                             :style="`left: ${tipLeft}%`">
                            <div class="zw-chart__tipMonth" x-text="hover !== null ? slice[hover].month : ''"></div>
                            <div x-show="showB" class="zw-chart__tipB">
                                📦 <span x-text="hover !== null ? slice[hover].bookings : 0"></span> bookings
                            </div>
                            <div x-show="showR" class="zw-chart__tipR">
                                💰 Rp <span x-text="hover !== null ? (slice[hover].revenue/1000000).toFixed(1) : 0"></span>jt
                            </div>
                        </div>
                    </div>
                    <div x-show="!showB && !showR" class="zw-chart__empty">Pilih minimal satu metrik</div>

                    <div class="zw-chart__summary">
                        <div class="zw-chart__sumItem">
                            <div class="zw-chart__sumIcon" style="background:#0284c715;color:#0284c7">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9 12h6M9 16h4M8 4h8a2 2 0 0 1 2 2v14l-3-2-3 2-3-2-3 2V6a2 2 0 0 1 2-2Z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="zw-chart__sumValue" style="color:#111827" x-text="totalB"></div>
                                <div class="zw-chart__sumLabel">bookings</div>
                            </div>
                        </div>
                        <div class="zw-chart__sumItem">
                            <div class="zw-chart__sumIcon" style="background:#10b98115;color:#10b981">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 6h18M3 12h12M3 18h18"/>
                                </svg>
                            </div>
                            <div>
                                <div class="zw-chart__sumValue" style="color:#10b981" x-text="totalRFmt"></div>
                                <div class="zw-chart__sumLabel">revenue</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>{{-- /.zw-desktop --}}


    {{-- ─────────── MOBILE ─────────── --}}
    <div class="zw-mobile">
        {{-- Hero gradient card --}}
        <div class="zw-hero">
            <div class="zw-hero__deco zw-hero__deco--1"></div>
            <div class="zw-hero__deco zw-hero__deco--2"></div>

            <div class="zw-hero__top">
                <div class="zw-hero__greet">Hello, {{ $firstName }}! 👋</div>
                <button type="button" class="zw-hero__bell" aria-label="Open notifications"
                        onclick="document.querySelector('.fi-topbar-database-notifications-btn')?.click()">
                    <x-heroicon-o-bell class="w-5 h-5"/>
                </button>
            </div>
            <div class="zw-hero__date">{{ $todayLabel }}</div>

            <div class="zw-hero__stats">
                @foreach ([
                    ['v' => $stats['pickups'], 'l' => 'Pickup',  's' => 'Today'],
                    ['v' => $stats['returns'], 'l' => 'Return',  's' => 'Today'],
                    ['v' => $stats['overdue'], 'l' => 'Overdue', 's' => 'Rental'],
                ] as $i => $h)
                    <div class="zw-hero__stat" @if($i < 2) style="border-right:1px solid rgba(255,255,255,0.2)" @endif>
                        <div class="zw-hero__num">{{ str_pad((string) $h['v'], 2, '0', STR_PAD_LEFT) }}</div>
                        <div class="zw-hero__label">{{ $h['l'] }}</div>
                        <div class="zw-hero__sub">{{ $h['s'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Quick menu 2x2 --}}
        <div class="zw-mob-section">Menu</div>
        <div class="zw-quick">
            @php
                $quick = [
                    ['label' => 'Schedule',  'sub' => 'Lihat jadwal harian',     'icon' => 'heroicon-o-calendar-days',           'url' => '/admin/schedule', 'badge' => null],
                    ['label' => 'Bookings',  'sub' => $stats['quotations'] . ' quotation pending', 'icon' => 'heroicon-o-clipboard-document-list', 'url' => '/admin/rentals',  'badge' => $stats['quotations'] > 0 ? $stats['quotations'] : null],
                    ['label' => 'Inventory', 'sub' => 'Kelola unit & stok',      'icon' => 'heroicon-o-cube',                    'url' => '/admin/products', 'badge' => null],
                    ['label' => 'Customers', 'sub' => 'Direktori pelanggan',     'icon' => 'heroicon-o-users',                   'url' => '/admin/customers','badge' => null],
                ];
            @endphp
            @foreach ($quick as $q)
                <a href="{{ $q['url'] }}" class="zw-quick__card" wire:navigate>
                    <div class="zw-quick__iconwrap">
                        <div class="zw-quick__icon">
                            <x-dynamic-component :component="$q['icon']" class="w-5 h-5"/>
                        </div>
                        @if ($q['badge'])
                            <div class="zw-quick__badge">{{ $q['badge'] }}</div>
                        @endif
                    </div>
                    <div>
                        <div class="zw-quick__label">{{ $q['label'] }}</div>
                        <div class="zw-quick__sub">{{ $q['sub'] }}</div>
                    </div>
                </a>
            @endforeach
        </div>

        {{-- Recent bookings cards --}}
        <div class="zw-mob-section">Recent Bookings</div>
        <div class="zw-mob-list">
            @forelse ($recent as $b)
                @php
                    $c = $statusColors[$b['status']] ?? $statusColors['cancelled'];
                    $initials = collect(explode(' ', $b['customer']))->take(2)->map(fn($w) => mb_substr($w, 0, 1))->implode('');
                @endphp
                <a href="/admin/rentals/{{ $b['id'] }}" class="zw-mob-list__item" wire:navigate>
                    <div class="zw-mob-list__avatar">{{ strtoupper($initials ?: '?') }}</div>
                    <div class="zw-mob-list__body">
                        <div class="zw-mob-list__name">{{ $b['customer'] }}</div>
                        <div class="zw-mob-list__item-name">{{ $b['item'] }}</div>
                    </div>
                    <div class="zw-mob-list__right">
                        <span class="zw-pill" style="background:{{ $c['bg'] }};color:{{ $c['fg'] }}">{{ $c['label'] }}</span>
                        <div class="zw-mob-list__total">{{ $b['total'] }}</div>
                    </div>
                </a>
            @empty
                <div class="zw-empty zw-mob-empty">Belum ada booking</div>
            @endforelse
        </div>
        <div style="height:90px"></div>{{-- spacer for bottom nav --}}
    </div>{{-- /.zw-mobile --}}

</div>

@once
<script>
    function zwDashboard(monthly) {
        return {
            monthly: monthly || [],
            showB: true,
            showR: false,
            period: 3,
            hover: null,
            W: 320, H: 130,
            PAD: { t: 8, r: 8, b: 22, l: 8 },
            get INNER() { return { w: this.W - this.PAD.l - this.PAD.r, h: this.H - this.PAD.t - this.PAD.b }; },
            get slice() {
                const n = Math.max(1, Math.min(this.period, this.monthly.length));
                return this.monthly.slice(-n);
            },
            get maxB() { return Math.max(1, ...this.slice.map(d => d.bookings || 0)); },
            get maxR() { return Math.max(1, ...this.slice.map(d => d.revenue || 0)); },
            xAt(i) {
                const n = this.slice.length;
                if (n <= 1) return this.PAD.l + this.INNER.w / 2;
                return this.PAD.l + (i / (n - 1)) * this.INNER.w;
            },
            get bPts() {
                return this.slice.map((d, i) => ({
                    x: this.xAt(i),
                    y: this.PAD.t + this.INNER.h - ((d.bookings || 0) / this.maxB) * this.INNER.h,
                }));
            },
            get rPts() {
                return this.slice.map((d, i) => ({
                    x: this.xAt(i),
                    y: this.PAD.t + this.INNER.h - ((d.revenue || 0) / this.maxR) * this.INNER.h,
                }));
            },
            smoothPath(pts) {
                if (!pts.length) return '';
                if (pts.length < 2) return `M ${pts[0].x} ${pts[0].y}`;
                let d = `M ${pts[0].x} ${pts[0].y}`;
                for (let i = 1; i < pts.length; i++) {
                    const cp1x = pts[i-1].x + (pts[i].x - pts[i-1].x) * 0.45;
                    const cp1y = pts[i-1].y;
                    const cp2x = pts[i].x - (pts[i].x - pts[i-1].x) * 0.45;
                    const cp2y = pts[i].y;
                    d += ` C ${cp1x} ${cp1y}, ${cp2x} ${cp2y}, ${pts[i].x} ${pts[i].y}`;
                }
                return d;
            },
            areaPath(pts) {
                if (!pts.length) return '';
                const bottom = this.PAD.t + this.INNER.h;
                return this.smoothPath(pts) + ` L ${pts[pts.length-1].x} ${bottom} L ${pts[0].x} ${bottom} Z`;
            },
            get bPath() { return this.smoothPath(this.bPts); },
            get rPath() { return this.smoothPath(this.rPts); },
            get bArea() { return this.areaPath(this.bPts); },
            get rArea() { return this.areaPath(this.rPts); },
            get hoverX() { return this.hover !== null ? this.bPts[this.hover].x : 0; },
            get tipLeft() {
                if (this.hover === null) return 50;
                const pct = (this.bPts[this.hover].x / this.W) * 100;
                return Math.min(Math.max(pct, 12), 80);
            },
            onChartHover(e) {
                const svg = e.currentTarget.querySelector('svg');
                if (!svg) return;
                const rect = svg.getBoundingClientRect();
                const mx = (e.clientX - rect.left) * (this.W / rect.width);
                let best = 0, bd = Infinity;
                this.slice.forEach((_, i) => {
                    const px = this.xAt(i);
                    const dist = Math.abs(px - mx);
                    if (dist < bd) { bd = dist; best = i; }
                });
                this.hover = best;
            },
            get totalB() { return this.slice.reduce((s, d) => s + (d.bookings || 0), 0); },
            get totalR() { return this.slice.reduce((s, d) => s + (d.revenue || 0), 0); },
            get totalRFmt() { return 'Rp ' + (this.totalR / 1000000).toFixed(1) + 'jt'; },
        };
    }
</script>
@endonce
</x-filament-widgets::widget>

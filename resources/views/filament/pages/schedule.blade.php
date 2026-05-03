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
    $sc = $statusColors;
@endphp

<x-filament-panels::page>
    <div class="zw-sched">

        {{-- Toolbar --}}
        <div class="zw-toolbar">
            <div class="zw-pill-group">
                <button wire:click="setTab('order')" type="button"
                    class="zw-pill {{ $activeTab === 'order' ? 'zw-pill--on' : '' }}">By Order</button>
                <button wire:click="setTab('unit')" type="button"
                    class="zw-pill {{ $activeTab === 'unit' ? 'zw-pill--on' : '' }}">By Product</button>
            </div>

            @if ($activeTab === 'order')
                <div class="zw-view-select" x-data="{ open: false }" @click.outside="open = false">
                    <button @click="open = ! open" type="button" class="zw-view-btn">
                        <span>{{ ucfirst($calendarView) }}</span>
                        <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                    </button>
                    <div x-show="open" x-cloak class="zw-view-menu" x-transition.opacity style="display:none">
                        @foreach (['month' => 'Month', 'week' => 'Week', 'day' => 'Day'] as $k => $l)
                            <button wire:click="setView('{{ $k }}')" @click="open = false" type="button"
                                class="zw-view-item {{ $calendarView === $k ? 'zw-view-item--on' : '' }}">
                                {{ $l }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="zw-spacer"></div>

            <a href="{{ url('/admin/rentals/create') }}" class="zw-new-btn zw-hide-mobile">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M12 5v14M5 12h14"/></svg>
                <span>New Booking</span>
            </a>
        </div>

        {{-- Status Legend --}}
        <div class="zw-legend">
            @foreach (['quotation','confirmed','active','completed','cancelled','late_pickup','partial_return'] as $k)
                @php $c = $sc[$k]; @endphp
                <div class="zw-legend__item">
                    <span class="zw-legend__dot" style="background:{{ $c['solid'] }}"></span>
                    <span>{{ $c['label'] }}</span>
                </div>
            @endforeach
        </div>

        @if ($activeTab === 'order')
            <div class="zw-gc-toolbar">
                <button wire:click="gotoToday" type="button" class="zw-gc-today">Today</button>
                <button wire:click="navigatePrev" type="button" class="zw-gc-nav" aria-label="Prev">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
                </button>
                <button wire:click="navigateNext" type="button" class="zw-gc-nav" aria-label="Next">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
                </button>
                <div class="zw-gc-title">{{ $this->getTitle() }}</div>
            </div>

            <div class="zw-cal-shell">
                @if ($calendarView === 'month')
                    @include('filament.pages.partials.schedule-month', ['weeks' => $this->getMonthGrid(), 'sc' => $sc])
                @elseif ($calendarView === 'week')
                    @include('filament.pages.partials.schedule-week', ['grid' => $this->getWeekGrid(), 'sc' => $sc])
                @else
                    @include('filament.pages.partials.schedule-day', ['layout' => $this->getDayLayout(), 'sc' => $sc])
                @endif
            </div>
        @else
            @php
                $products = $this->getProductsWithUnitsAndRentals();
                $header = $this->getDaysHeader();
            @endphp
            @include('filament.pages.partials.schedule-by-product', [
                'products' => $products,
                'header'   => $header,
                'sc'       => $sc,
                'rangeTitle' => $this->getProductRangeTitle(),
            ])
        @endif
    </div>

    {{-- Overflow popup — isolated Alpine scope, listens for window event --}}
    <div
        x-data="zwOverflowPopup()"
        x-on:zw-open-overflow.window="open($event.detail.title, $event.detail.items)"
        x-show="visible"
        x-cloak
        @keydown.escape.window="visible = false"
        @click.self="visible = false"
        class="zw-overflow"
        style="display:none"
    >
        <div class="zw-overflow__panel" @click.stop>
            <div class="zw-overflow__head">
                <div class="zw-overflow__date" x-text="title"></div>
                <button @click="visible = false" type="button" class="zw-overflow__close" aria-label="Close">×</button>
            </div>
            <div class="zw-overflow__body">
                <template x-for="r in items" :key="r.id">
                    <button type="button" class="zw-overflow__row"
                            @click="openRental(r.id)">
                        <span class="zw-overflow__bar" :style="`background:${r.color}`"></span>
                        <span class="zw-overflow__time" x-text="r.time"></span>
                        <span class="zw-overflow__name" x-text="r.customer"></span>
                        <span class="zw-overflow__pill" :style="`background:${r.bg};color:${r.fg}`" x-text="r.label"></span>
                    </button>
                </template>
            </div>
        </div>
    </div>

    <x-filament-actions::modals/>

    @push('scripts')
    <script>
        function zwOverflowPopup() {
            return {
                visible: false,
                title: '',
                items: [],
                open(title, items) {
                    this.title = title;
                    this.items = items || [];
                    this.visible = true;
                },
                openRental(id) {
                    this.visible = false;
                    if (window.Livewire) {
                        window.Livewire.dispatch('open-rental-modal', { id: id });
                    }
                },
            };
        }
        // Bridge: dispatch Livewire event to mount Filament action
        document.addEventListener('livewire:init', () => {
            window.Livewire.on('open-rental-modal', (event) => {
                const id = event.id ?? (event[0] && event[0].id);
                if (! id) return;
                const el = document.querySelector('[wire\\:id]');
                if (el && window.Livewire.find(el.getAttribute('wire:id'))) {
                    window.Livewire.find(el.getAttribute('wire:id')).call('mountAction', 'viewRentalDetails', { rentalId: id });
                }
            });
        });
    </script>
    @endpush

    @once
        @push('styles')
        <style>
            [x-cloak]{ display:none !important; }
            .zw-sched { font-family:'Figtree', ui-sans-serif, system-ui, sans-serif; color:#111827; display:flex; flex-direction:column; gap:12px; }

            /* Toolbar */
            .zw-toolbar { display:flex; align-items:center; gap:8px; flex-wrap:wrap; }
            .zw-spacer { flex:1; }
            .zw-pill-group { display:inline-flex; background:#f3f4f6; border-radius:10px; padding:3px; flex:1 1 auto; }
            @media (min-width:640px) { .zw-pill-group { flex:0 0 auto; } }
            .zw-pill { flex:1; padding:8px 16px; border-radius:7px; border:none; background:transparent; color:#6b7280; font-size:12px; font-weight:700; cursor:pointer; font-family:inherit; transition:all .15s; }
            .zw-pill--on { background:#fff; color:#111827; box-shadow:0 1px 3px rgba(0,0,0,0.08); }
            .dark .zw-pill-group { background:#111827; }
            .dark .zw-pill { color:#9ca3af; }
            .dark .zw-pill--on { background:#1f2937; color:#f9fafb; }

            .zw-view-select { position:relative; }
            .zw-view-btn { display:inline-flex; align-items:center; gap:6px; padding:8px 12px; border-radius:8px; background:#fff; border:1px solid #e5e7eb; font-size:12px; font-weight:700; color:#374151; cursor:pointer; font-family:inherit; }
            .dark .zw-view-btn { background:#1f2937; border-color:#374151; color:#f9fafb; }
            .zw-view-menu { position:absolute; top:calc(100% + 4px); left:0; z-index:30; background:#fff; border:1px solid #e5e7eb; border-radius:10px; box-shadow:0 8px 24px rgba(0,0,0,0.12); overflow:hidden; min-width:120px; }
            .dark .zw-view-menu { background:#1f2937; border-color:#374151; }
            .zw-view-item { width:100%; text-align:left; padding:10px 14px; border:none; background:#fff; color:#111827; font-size:13px; font-weight:500; cursor:pointer; font-family:inherit; border-bottom:1px solid #f3f4f6; }
            .zw-view-item:last-child { border-bottom:none; }
            .zw-view-item--on { background:#f0f9ff; color:#0369a1; font-weight:700; }
            .dark .zw-view-item { background:#1f2937; color:#f9fafb; border-color:#374151; }
            .dark .zw-view-item--on { background:#0c4a6e; color:#7dd3fc; }

            .zw-new-btn { display:inline-flex; align-items:center; gap:6px; padding:8px 16px; border-radius:8px; background:#0284c7; color:#fff; font-size:12px; font-weight:700; text-decoration:none; cursor:pointer; }
            .zw-new-btn:hover { background:#0369a1; }

            /* Legend */
            .zw-legend { display:flex; flex-wrap:wrap; gap:6px 14px; padding:9px 12px; background:#fff; border:1px solid #f3f4f6; border-radius:10px; }
            .dark .zw-legend { background:#1f2937; border-color:#374151; }
            .zw-legend__item { display:flex; align-items:center; gap:5px; font-size:11px; font-weight:600; color:#374151; }
            .dark .zw-legend__item { color:#d1d5db; }
            .zw-legend__dot { width:10px; height:10px; border-radius:99px; }

            /* Google Calendar toolbar */
            .zw-gc-toolbar { display:flex; align-items:center; gap:8px; padding:8px 12px; background:#fff; border:1px solid #f3f4f6; border-radius:10px; }
            .dark .zw-gc-toolbar { background:#1f2937; border-color:#374151; }
            .zw-gc-today { padding:6px 14px; border-radius:99px; background:transparent; border:1px solid #d1d5db; color:#374151; font-size:12px; font-weight:700; cursor:pointer; font-family:inherit; }
            .zw-gc-today:hover { background:#f3f4f6; }
            .dark .zw-gc-today { border-color:#4b5563; color:#f9fafb; }
            .zw-gc-nav { width:32px; height:32px; border-radius:99px; background:transparent; border:none; color:#374151; cursor:pointer; display:grid; place-items:center; }
            .zw-gc-nav:hover { background:#f3f4f6; }
            .dark .zw-gc-nav { color:#f9fafb; }
            .dark .zw-gc-nav:hover { background:#374151; }
            .zw-gc-title { font-size:18px; font-weight:700; color:#111827; letter-spacing:-0.3px; margin-left:6px; }
            .dark .zw-gc-title { color:#f9fafb; }
            @media (max-width:640px) { .zw-gc-title { font-size:14px; } }

            /* Calendar shell */
            .zw-cal-shell { background:#fff; border:1px solid #f3f4f6; border-radius:12px; overflow:hidden; }
            .dark .zw-cal-shell { background:#1f2937; border-color:#374151; }

            /* MONTH grid */
            .zw-month { display:flex; flex-direction:column; }
            .zw-month__head { display:grid; grid-template-columns:repeat(7, 1fr); border-bottom:1px solid #f3f4f6; }
            .zw-month__head > div { padding:8px 6px; font-size:11px; font-weight:700; color:#6b7280; text-align:center; text-transform:uppercase; letter-spacing:.3px; }
            .dark .zw-month__head, .dark .zw-month__head > div { border-color:#374151; color:#9ca3af; }
            .zw-month__week { display:grid; grid-template-columns:repeat(7, 1fr); border-bottom:1px solid #f3f4f6; min-height:88px; }
            .dark .zw-month__week { border-color:#374151; }
            .zw-month__week:last-child { border-bottom:none; }
            .zw-month__cell { border-right:1px solid #f3f4f6; padding:4px; display:flex; flex-direction:column; gap:2px; min-width:0; min-height:88px; }
            .dark .zw-month__cell { border-color:#374151; }
            .zw-month__cell:last-child { border-right:none; }
            .zw-month__cell--out { background:#f9fafb; }
            .dark .zw-month__cell--out { background:#111827; }
            .zw-month__day { font-size:11px; font-weight:700; color:#374151; padding:2px 4px; align-self:flex-start; }
            .dark .zw-month__day { color:#f9fafb; }
            .zw-month__day--today { background:#0284c7; color:#fff; border-radius:99px; min-width:22px; height:22px; display:grid; place-items:center; padding:0; }
            .zw-month__day--out { color:#9ca3af; }
            .zw-month__bar { font-size:10px; font-weight:700; color:#fff; padding:2px 6px; border-radius:5px; cursor:pointer; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; box-shadow:0 1px 2px rgba(0,0,0,0.12); border:none; text-align:left; font-family:inherit; }
            .zw-month__more { font-size:10px; font-weight:700; color:#0284c7; padding:2px 6px; border:none; background:transparent; cursor:pointer; text-align:left; font-family:inherit; }
            .zw-month__more:hover { text-decoration:underline; }

            @media (max-width:640px) {
                .zw-month__week { min-height:64px; }
                .zw-month__cell { min-height:64px; padding:2px; }
                .zw-month__bar { font-size:9px; padding:1px 4px; }
                .zw-month__head > div { padding:6px 2px; font-size:10px; }
            }

            /* WEEK grid */
            .zw-week { display:flex; flex-direction:column; min-width:0; }
            .zw-week__head { display:grid; grid-template-columns:repeat(7, 1fr); border-bottom:1px solid #f3f4f6; }
            .dark .zw-week__head { border-color:#374151; }
            .zw-week__head > div { padding:10px 4px; text-align:center; border-right:1px solid #f3f4f6; }
            .dark .zw-week__head > div { border-color:#374151; }
            .zw-week__head > div:last-child { border-right:none; }
            .zw-week__dayname { font-size:10px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:.4px; }
            .zw-week__daynum { font-size:18px; font-weight:800; color:#111827; margin-top:2px; }
            .dark .zw-week__daynum { color:#f9fafb; }
            .zw-week__daynum--today { color:#fff; background:#0284c7; border-radius:99px; width:28px; height:28px; line-height:28px; margin:2px auto 0; display:block; }

            .zw-week__body { padding:8px; display:grid; gap:6px; }
            .zw-week__bar { display:grid; grid-template-columns:repeat(7, 1fr); align-items:center; gap:2px; }
            .zw-week__bar > .zw-week__rental {
                grid-column-start: var(--start);
                grid-column-end: span var(--span);
                background:var(--bg);
                color:#fff; padding:8px 10px; border-radius:6px; font-size:12px; font-weight:700;
                white-space:nowrap; overflow:hidden; text-overflow:ellipsis; cursor:pointer;
                box-shadow:0 1px 3px rgba(0,0,0,0.12); border:none; text-align:left; font-family:inherit;
            }
            .zw-week__rental__sub { font-size:10px; font-weight:600; opacity:.85; margin-top:2px; }

            @media (max-width:640px) {
                .zw-week__head > div { padding:6px 2px; }
                .zw-week__daynum { font-size:13px; }
                .zw-week__bar > .zw-week__rental { padding:6px 6px; font-size:10px; }
            }

            /* DAY timeline */
            .zw-day { display:flex; flex-direction:column; min-width:0; }
            .zw-day__grid { display:flex; min-height:600px; position:relative; }
            .zw-day__hours { width:50px; flex-shrink:0; border-right:1px solid #f3f4f6; }
            .dark .zw-day__hours { border-color:#374151; }
            .zw-day__hour { height:48px; padding:2px 6px; font-size:10px; font-weight:600; color:#6b7280; text-align:right; border-bottom:1px solid #f9fafb; }
            .dark .zw-day__hour { border-color:#374151; }
            .zw-day__events { flex:1; position:relative; min-height:600px; background-image:linear-gradient(to bottom, #f3f4f6 1px, transparent 1px); background-size:100% 48px; }
            .dark .zw-day__events { background-image:linear-gradient(to bottom, #374151 1px, transparent 1px); }
            .zw-day__event { position:absolute; padding:6px 10px; border-radius:6px; color:#fff; font-size:12px; font-weight:700; cursor:pointer; box-shadow:0 1px 3px rgba(0,0,0,0.15); overflow:hidden; border:none; text-align:left; font-family:inherit; }
            .zw-day__event__time { font-size:10px; font-weight:600; opacity:.85; margin-top:2px; }

            /* By Product */
            .zw-prod { display:flex; flex-direction:column; gap:10px; }
            .zw-prod__topbar { display:flex; align-items:center; gap:8px; padding:8px 12px; background:#fff; border:1px solid #f3f4f6; border-radius:10px; flex-wrap:wrap; }
            .dark .zw-prod__topbar { background:#1f2937; border-color:#374151; }
            .zw-prod__title { font-size:15px; font-weight:700; color:#111827; letter-spacing:-0.2px; margin-left:6px; }
            .dark .zw-prod__title { color:#f9fafb; }
            @media (max-width:640px) { .zw-prod__title { font-size:13px; } }
            .zw-prod__search-wrap { flex:1 1 200px; min-width:160px; max-width:280px; }
            .zw-prod__shell { background:#fff; border:1px solid #f3f4f6; border-radius:12px; overflow:hidden; }
            .dark .zw-prod__shell { background:#1f2937; border-color:#374151; }
            .zw-prod__scroll { overflow:auto; max-height:70vh; }
            .zw-prod__table { border-collapse:collapse; table-layout:fixed; min-width:max-content; width:100%; }
            .zw-prod__table th, .zw-prod__table td { border-bottom:1px solid #f3f4f6; border-right:1px solid #f3f4f6; padding:0; }
            .dark .zw-prod__table th, .dark .zw-prod__table td { border-color:#374151; }
            .zw-prod__th-spacer { background:#fff; min-width:200px; width:200px; position:sticky; left:0; top:0; z-index:30; padding:8px 12px !important; font-size:11px; font-weight:800; text-transform:uppercase; color:#6b7280; }
            .dark .zw-prod__th-spacer { background:#1f2937; }
            .zw-prod__month { padding:8px !important; background:#f9fafb; font-size:12px; font-weight:700; color:#374151; text-align:center; position:sticky; top:0; z-index:20; }
            .dark .zw-prod__month { background:#111827; color:#f9fafb; }
            .zw-prod__day { width:64px; min-width:64px; padding:8px 0 !important; background:#fff; text-align:center; position:sticky; top:38px; z-index:20; }
            .dark .zw-prod__day { background:#1f2937; }
            .zw-prod__day--today { background:#f0f9ff !important; }
            .dark .zw-prod__day--today { background:#0c4a6e !important; }
            .zw-prod__day__short { font-size:10px; color:#9ca3af; font-weight:600; }
            .zw-prod__day__num { font-size:14px; font-weight:700; color:#111827; }
            .dark .zw-prod__day__num { color:#f9fafb; }
            .zw-prod__day--today .zw-prod__day__num { color:#0284c7; }

            .zw-prod__product { padding:8px 12px !important; background:#f0f9ff; font-size:13px; font-weight:700; color:#0369a1; position:sticky; left:0; z-index:15; }
            .dark .zw-prod__product { background:#0c4a6e; color:#7dd3fc; }
            .zw-prod__sku { padding:8px 12px 8px 24px !important; background:#fff; position:sticky; left:0; z-index:10; min-width:200px; width:200px; }
            .dark .zw-prod__sku { background:#1f2937; }
            .zw-prod__sku-tag { font-family:ui-monospace, monospace; font-size:11px; background:#f3f4f6; color:#4b5563; padding:2px 8px; border-radius:6px; }
            .dark .zw-prod__sku-tag { background:#374151; color:#d1d5db; }

            .zw-prod__cell { position:relative; height:40px; min-width:64px; }
            .zw-prod__seg { position:absolute; top:6px; bottom:6px; padding:0 4px; display:flex; align-items:center; cursor:pointer; box-shadow:0 1px 2px rgba(0,0,0,0.15); overflow:hidden; border:none; font-family:inherit; }
            .zw-prod__seg__name { font-size:10px; font-weight:700; color:#fff; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }

            /* Overflow popup */
            .zw-overflow { position:fixed; inset:0; z-index:60; background:rgba(0,0,0,0.45); display:grid; place-items:center; padding:16px; }
            .zw-overflow__panel { background:#fff; border-radius:14px; max-width:420px; width:100%; max-height:80vh; overflow:hidden; display:flex; flex-direction:column; box-shadow:0 24px 48px rgba(0,0,0,0.25); }
            .dark .zw-overflow__panel { background:#1f2937; }
            .zw-overflow__head { display:flex; align-items:center; justify-content:space-between; padding:14px 16px; border-bottom:1px solid #f3f4f6; }
            .dark .zw-overflow__head { border-color:#374151; }
            .zw-overflow__date { font-size:14px; font-weight:800; color:#111827; }
            .dark .zw-overflow__date { color:#f9fafb; }
            .zw-overflow__close { width:30px; height:30px; border-radius:99px; border:none; background:#f3f4f6; color:#374151; font-size:18px; line-height:1; cursor:pointer; }
            .dark .zw-overflow__close { background:#374151; color:#f9fafb; }
            .zw-overflow__body { overflow-y:auto; padding:6px 0; }
            .zw-overflow__row { display:flex; align-items:center; gap:10px; width:100%; padding:10px 16px; background:transparent; border:none; cursor:pointer; font-family:inherit; text-align:left; }
            .zw-overflow__row:hover { background:#f9fafb; }
            .dark .zw-overflow__row:hover { background:#111827; }
            .zw-overflow__bar { width:4px; height:32px; border-radius:99px; flex-shrink:0; }
            .zw-overflow__time { font-size:11px; font-weight:700; color:#6b7280; min-width:50px; }
            .zw-overflow__name { flex:1; font-size:13px; font-weight:700; color:#111827; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
            .dark .zw-overflow__name { color:#f9fafb; }
            .zw-overflow__pill { padding:3px 10px; border-radius:99px; font-size:10px; font-weight:700; }

            /* Responsive helpers */
            @media (max-width:767px) {
                .zw-hide-mobile { display:none !important; }
            }
        </style>
        @endpush
    @endonce
</x-filament-panels::page>

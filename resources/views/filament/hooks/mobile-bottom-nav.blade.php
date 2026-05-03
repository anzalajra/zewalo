@php
    if (! auth()->check()) {
        return;
    }

    $path = '/' . trim(request()->path(), '/');

    $isActive = function (array $patterns) use ($path): bool {
        foreach ($patterns as $p) {
            if ($path === $p || str_starts_with($path, rtrim($p, '/') . '/')) {
                return true;
            }
        }
        return false;
    };

    $homeActive     = $path === '/admin' || $path === '/admin/';
    $scheduleActive = $isActive(['/admin/schedule']);
    $bookingsActive = $isActive(['/admin/rentals', '/admin/quotations']);

    $plusActions = [
        ['label' => 'New Rental',    'sub' => 'Buat booking baru',    'url' => '/admin/rentals/create',   'icon' => 'heroicon-o-clipboard-document-list'],
        ['label' => 'New Quotation', 'sub' => 'Buat penawaran harga', 'url' => '/admin/quotations/create','icon' => 'heroicon-o-document-text'],
        ['label' => 'New Customer',  'sub' => 'Tambah pelanggan',     'url' => '/admin/customers/create', 'icon' => 'heroicon-o-users'],
    ];

    $moreItems = [
        ['label' => 'Inventory',  'icon' => 'heroicon-o-cube',                    'url' => '/admin/products'],
        ['label' => 'Customers',  'icon' => 'heroicon-o-users',                   'url' => '/admin/customers'],
        ['label' => 'Deliveries', 'icon' => 'heroicon-o-truck',                   'url' => '/admin/deliveries'],
        ['label' => 'Invoices',   'icon' => 'heroicon-o-document-text',           'url' => '/admin/invoices'],
        ['label' => 'Quotations', 'icon' => 'heroicon-o-clipboard-document-list', 'url' => '/admin/quotations'],
        ['label' => 'Finance',    'icon' => 'heroicon-o-banknotes',               'url' => '/admin/finance'],
        ['label' => 'Profile',    'icon' => 'heroicon-o-user-circle',             'url' => '/admin/profile'],
        ['label' => 'Settings',   'icon' => 'heroicon-o-cog-6-tooth',             'url' => '/admin/settings/general'],
        ['label' => 'Scanner',    'icon' => 'heroicon-o-qr-code',                 'url' => '#scanner'],
        ['label' => 'Billing',    'icon' => 'heroicon-o-credit-card',             'url' => '/admin/subscription-billing'],
    ];
@endphp

<div x-data="{ plusOpen: false, moreOpen: false }" class="zw-mobnav-root">

    {{-- Plus sheet backdrop --}}
    <div x-show="plusOpen || moreOpen" x-cloak x-transition.opacity
         class="zw-mobnav__backdrop"
         @click="plusOpen = false; moreOpen = false"></div>

    {{-- Plus action sheet --}}
    <div x-show="plusOpen" x-cloak
         x-transition:enter="zw-sheet-enter"
         x-transition:enter-start="zw-sheet-enter-from"
         x-transition:enter-end="zw-sheet-enter-to"
         class="zw-mobnav__sheet">
        <div class="zw-mobnav__sheet-head">New</div>
        @foreach ($plusActions as $i => $a)
            <a href="{{ $a['url'] }}" class="zw-mobnav__sheet-item" wire:navigate
               @click="plusOpen = false">
                <div class="zw-mobnav__sheet-icon">
                    <x-dynamic-component :component="$a['icon']" class="w-5 h-5"/>
                </div>
                <div>
                    <div class="zw-mobnav__sheet-label">{{ $a['label'] }}</div>
                    <div class="zw-mobnav__sheet-sub">{{ $a['sub'] }}</div>
                </div>
            </a>
        @endforeach
    </div>

    {{-- More sheet --}}
    <div x-show="moreOpen" x-cloak
         x-transition:enter="zw-sheet-enter"
         x-transition:enter-start="zw-sheet-enter-from"
         x-transition:enter-end="zw-sheet-enter-to"
         class="zw-mobnav__sheet zw-mobnav__sheet--grid">
        <div class="zw-mobnav__sheet-head">Menu Lainnya</div>
        <div class="zw-mobnav__more-grid">
            @foreach ($moreItems as $m)
                @if ($m['url'] === '#scanner')
                    <button type="button" class="zw-mobnav__more-item"
                            @click="moreOpen = false; window.dispatchEvent(new CustomEvent('zw:scanner-open'))">
                        <div class="zw-mobnav__more-icon">
                            <x-dynamic-component :component="$m['icon']" class="w-5 h-5"/>
                        </div>
                        <span>{{ $m['label'] }}</span>
                    </button>
                @else
                    <a href="{{ $m['url'] }}" class="zw-mobnav__more-item" wire:navigate
                       @click="moreOpen = false">
                        <div class="zw-mobnav__more-icon">
                            <x-dynamic-component :component="$m['icon']" class="w-5 h-5"/>
                        </div>
                        <span>{{ $m['label'] }}</span>
                    </a>
                @endif
            @endforeach
        </div>
    </div>

    {{-- Bottom nav bar --}}
    <nav class="zw-mobnav" aria-label="Mobile bottom navigation">
        <a href="/admin" class="zw-mobnav__item @if($homeActive) is-active @endif" wire:navigate
           @click="plusOpen = false; moreOpen = false">
            <x-heroicon-o-home class="zw-mobnav__icon"/>
            <span>Home</span>
        </a>
        <a href="/admin/schedule" class="zw-mobnav__item @if($scheduleActive) is-active @endif" wire:navigate
           @click="plusOpen = false; moreOpen = false">
            <x-heroicon-o-calendar-days class="zw-mobnav__icon"/>
            <span>Schedule</span>
        </a>

        <div class="zw-mobnav__plus-slot">
            <button type="button" class="zw-mobnav__plus" @click="plusOpen = !plusOpen; moreOpen = false"
                    aria-label="New">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
            </button>
        </div>

        <a href="/admin/rentals" class="zw-mobnav__item @if($bookingsActive) is-active @endif" wire:navigate
           @click="plusOpen = false; moreOpen = false">
            <x-heroicon-o-clipboard-document-list class="zw-mobnav__icon"/>
            <span>Bookings</span>
        </a>
        <button type="button" class="zw-mobnav__item" @click="moreOpen = !moreOpen; plusOpen = false">
            <x-heroicon-o-ellipsis-horizontal class="zw-mobnav__icon"/>
            <span>More</span>
        </button>
    </nav>
</div>

@once
@push('styles')
<style>
    /* Hide on desktop, show on mobile */
    .zw-mobnav-root { display: none; font-family: 'Figtree', ui-sans-serif, system-ui, sans-serif; }
    @media (max-width: 767px) {
        .zw-mobnav-root { display: block; }
        /* Reserve space at the bottom of the page for the fixed bar so content isn't covered */
        body { padding-bottom: calc(72px + env(safe-area-inset-bottom, 0px)); }
    }

    .zw-mobnav {
        position: fixed; bottom: 0; left: 0; right: 0; z-index: 9998;
        background: #fff; border-top: 1px solid #e5e7eb;
        height: 68px;
        display: flex; align-items: center; justify-content: space-around;
        box-shadow: 0 -4px 20px rgba(0,0,0,0.08);
        padding: 0 4px env(safe-area-inset-bottom, 0px);
    }
    .dark .zw-mobnav { background: #1f2937; border-top-color: #374151; }

    .zw-mobnav__item {
        flex: 1; display: flex; flex-direction: column; align-items: center; gap: 3px;
        padding: 8px 4px; font-size: 10px; font-weight: 600;
        color: #9ca3af; text-decoration: none;
        border: none; background: transparent; cursor: pointer;
        transition: color 150ms;
        min-width: 0;
    }
    .zw-mobnav__item:active { opacity: 0.7; }
    .zw-mobnav__item.is-active { color: #0284c7; }
    .dark .zw-mobnav__item { color: #9ca3af; }
    .dark .zw-mobnav__item.is-active { color: #38bdf8; }
    .zw-mobnav__icon { width: 22px; height: 22px; }

    .zw-mobnav__plus-slot {
        flex: 1.2; display: flex; align-items: center; justify-content: center;
    }
    .zw-mobnav__plus {
        width: 56px; height: 56px; border-radius: 999px;
        background: #0284c7; color: #fff; border: none; cursor: pointer;
        display: grid; place-items: center;
        box-shadow: 0 6px 20px rgba(2,132,199,0.45);
        transition: transform 150ms, box-shadow 150ms;
        margin-top: -22px;
    }
    .zw-mobnav__plus:hover { transform: scale(1.06); }
    .zw-mobnav__plus:active { transform: scale(0.95); }

    /* Backdrop */
    .zw-mobnav__backdrop {
        position: fixed; inset: 0; z-index: 9996;
        background: rgba(0,0,0,0.32); backdrop-filter: blur(2px);
    }

    /* Sheet */
    .zw-mobnav__sheet {
        position: fixed; bottom: 88px; left: 16px; right: 16px; z-index: 9997;
        background: #fff; border-radius: 18px;
        box-shadow: 0 8px 40px rgba(0,0,0,0.18);
        border: 1px solid #f3f4f6;
        overflow: hidden;
    }
    .dark .zw-mobnav__sheet { background: #1f2937; border-color: #374151; }
    .zw-mobnav__sheet-head {
        padding: 14px 20px 8px;
        font-size: 11px; font-weight: 700; color: #9ca3af;
        text-transform: uppercase; letter-spacing: 0.06em;
        border-bottom: 1px solid #f3f4f6;
    }
    .dark .zw-mobnav__sheet-head { border-bottom-color: #374151; }

    .zw-mobnav__sheet-item {
        padding: 13px 20px; display: flex; align-items: center; gap: 14px;
        border-bottom: 1px solid #f9fafb;
        text-decoration: none; color: inherit;
    }
    .dark .zw-mobnav__sheet-item { border-bottom-color: #1f2937; }
    .zw-mobnav__sheet-item:last-child { border-bottom: none; }
    .zw-mobnav__sheet-item:active { background: #f9fafb; }
    .dark .zw-mobnav__sheet-item:active { background: #111827; }
    .zw-mobnav__sheet-icon {
        width: 42px; height: 42px; border-radius: 12px;
        background: #0284c715; color: #0284c7;
        display: grid; place-items: center; flex-shrink: 0;
    }
    .zw-mobnav__sheet-label { font-size: 14px; font-weight: 700; color: #111827; }
    .dark .zw-mobnav__sheet-label { color: #f9fafb; }
    .zw-mobnav__sheet-sub { font-size: 11.5px; color: #9ca3af; margin-top: 1px; }

    .zw-mobnav__sheet--grid { padding-bottom: 8px; }
    .zw-mobnav__more-grid {
        display: grid; grid-template-columns: repeat(4, 1fr);
        gap: 6px; padding: 10px;
    }
    .zw-mobnav__more-item {
        background: none; border: none;
        padding: 12px 6px; border-radius: 12px; cursor: pointer;
        display: flex; flex-direction: column; align-items: center; gap: 6px;
        font-size: 11px; font-weight: 600; color: #374151;
        text-decoration: none; font-family: inherit;
        transition: background 150ms;
    }
    .dark .zw-mobnav__more-item { color: #d1d5db; }
    .zw-mobnav__more-item:active { background: #f3f4f6; }
    .dark .zw-mobnav__more-item:active { background: #374151; }
    .zw-mobnav__more-icon {
        width: 42px; height: 42px; border-radius: 12px;
        background: #0284c715; color: #0284c7;
        display: grid; place-items: center;
    }

    /* Sheet animation */
    .zw-sheet-enter { transition: opacity 200ms cubic-bezier(0.34,1.56,0.64,1), transform 200ms cubic-bezier(0.34,1.56,0.64,1); }
    .zw-sheet-enter-from { opacity: 0; transform: translateY(16px) scale(0.97); }
    .zw-sheet-enter-to   { opacity: 1; transform: translateY(0) scale(1); }
</style>
@endpush
@endonce

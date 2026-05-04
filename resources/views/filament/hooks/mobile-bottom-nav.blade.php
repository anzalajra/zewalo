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



@php
    $user = auth()->user();
    if (! $user) {
        return;
    }

    $name = $user->name ?? 'User';
    $first = explode(' ', $name)[0];
    $initials = strtoupper(collect(explode(' ', $name))->take(2)->map(fn ($w) => mb_substr($w, 0, 1))->implode(''));

    // Role label — first role name if present, else "Admin"
    $roleLabel = 'Admin';
    try {
        if (method_exists($user, 'getRoleNames')) {
            $roleLabel = $user->getRoleNames()->first() ?? 'Admin';
            $roleLabel = ucwords(str_replace('_', ' ', $roleLabel));
        }
    } catch (\Throwable $e) {
        // ignore
    }

    $tenantName = \App\Models\Setting::get('site_name') ?? config('app.name');

    // Unread DB notifications
    try {
        $unreadCount = $user->unreadNotifications()->count();
        $latestNotifs = $user->notifications()->latest()->limit(5)->get()->map(function ($n) {
            $data = $n->data ?? [];
            return [
                'id' => $n->id,
                'title' => $data['title'] ?? ($data['subject'] ?? 'Notifikasi'),
                'body' => $data['body'] ?? ($data['message'] ?? ''),
                'unread' => $n->read_at === null,
                'time' => $n->created_at?->diffForHumans() ?? '',
                'url' => $data['url'] ?? null,
            ];
        })->all();
    } catch (\Throwable $e) {
        $unreadCount = 0;
        $latestNotifs = [];
    }

    $logoutUrl = filament()->getLogoutUrl();
    $profileUrl = '/admin/profile';
    $billingUrl = '/admin/subscription-billing';
@endphp

<div
    x-data="{
        open: false,
        notifOpen: false,
        toggleProfile() { this.open = !this.open; this.notifOpen = false; },
        toggleNotif()   { this.notifOpen = !this.notifOpen; this.open = false; },
        closeAll()      { this.open = false; this.notifOpen = false; },
    }"
    @click.outside="closeAll()"
    @keydown.escape.window="closeAll()"
    class="zw-capsule-root"
>
    {{-- Profile popup --}}
    <div
        x-show="open"
        x-transition:enter="zw-pop-enter"
        x-transition:enter-start="zw-pop-enter-from"
        x-transition:enter-end="zw-pop-enter-to"
        x-cloak
        class="zw-capsule__profile"
    >
        <div class="zw-capsule__profile-head">
            <div class="zw-capsule__profile-name">{{ $name }}</div>
            <div class="zw-capsule__profile-tenant">{{ $tenantName }}</div>
        </div>

        <div class="zw-capsule__profile-list">
            <a href="{{ $profileUrl }}" class="zw-capsule__profile-item" wire:navigate>
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/></svg>
                <span>My Profile</span>
            </a>
            <a href="{{ $billingUrl }}" class="zw-capsule__profile-item" wire:navigate>
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z"/></svg>
                <span>Subscription &amp; Billing</span>
            </a>
            <form method="POST" action="{{ $logoutUrl }}" class="zw-capsule__profile-form">
                @csrf
                <button type="submit" class="zw-capsule__profile-item zw-capsule__profile-item--danger">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/></svg>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </div>

    {{-- Notifications popup --}}
    <div
        x-show="notifOpen"
        x-transition:enter="zw-pop-enter"
        x-transition:enter-start="zw-pop-enter-from"
        x-transition:enter-end="zw-pop-enter-to"
        x-cloak
        class="zw-capsule__notif"
    >
        <div class="zw-capsule__notif-head">
            <span class="zw-capsule__notif-title">Notifikasi</span>
            @if ($unreadCount > 0)
                <span class="zw-capsule__notif-action">{{ $unreadCount }} belum dibaca</span>
            @endif
        </div>

        @forelse ($latestNotifs as $n)
            <a href="{{ $n['url'] ?? '/admin' }}"
               class="zw-capsule__notif-item @if ($n['unread']) zw-capsule__notif-item--unread @endif"
               wire:navigate>
                <span class="zw-capsule__notif-dot @if ($n['unread']) zw-capsule__notif-dot--on @endif"></span>
                <div class="zw-capsule__notif-body">
                    <div class="zw-capsule__notif-text">{{ $n['title'] }}</div>
                    @if ($n['body'])
                        <div class="zw-capsule__notif-detail">{{ \Illuminate\Support\Str::limit(strip_tags($n['body']), 60) }}</div>
                    @endif
                    <div class="zw-capsule__notif-time">{{ $n['time'] }}</div>
                </div>
            </a>
        @empty
            <div class="zw-capsule__notif-empty">Belum ada notifikasi</div>
        @endforelse
    </div>

    {{-- Capsule itself --}}
    <div class="zw-capsule" role="region" aria-label="Profile capsule">
        <button type="button" @click="toggleProfile()" class="zw-capsule__avatar-btn" aria-label="Open profile menu">
            <span class="zw-capsule__avatar">{{ $initials ?: '?' }}</span>
        </button>
        <button type="button" @click="toggleProfile()" class="zw-capsule__id">
            <div class="zw-capsule__name">{{ $first }}</div>
            <div class="zw-capsule__role">{{ $roleLabel }}</div>
        </button>
        <button type="button" @click="toggleNotif()" class="zw-capsule__icon-btn" aria-label="Notifications">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0"/>
            </svg>
            @if ($unreadCount > 0)
                <span class="zw-capsule__notif-badge"></span>
            @endif
        </button>
        <button type="button" class="zw-capsule__icon-btn" aria-label="QR Scanner"
                onclick="window.dispatchEvent(new CustomEvent('zw:scanner-open'))">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 3.75 9.375v-4.5ZM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 0 1-1.125-1.125v-4.5ZM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 13.5 9.375v-4.5ZM6.75 6.75h.75v.75h-.75v-.75ZM6.75 16.5h.75v.75h-.75v-.75ZM16.5 6.75h.75v.75h-.75v-.75ZM13.5 13.5h.75v.75h-.75v-.75ZM13.5 19.5h.75v.75h-.75v-.75ZM19.5 13.5h.75v.75h-.75v-.75ZM19.5 19.5h.75v.75h-.75v-.75ZM16.5 16.5h.75v.75h-.75v-.75Z"/>
            </svg>
        </button>
    </div>
</div>



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

@once
@push('styles')
<style>
    [x-cloak] { display:none !important; }

    .zw-capsule-root {
        position: fixed; bottom: 20px; left: 20px; z-index: 9999;
        display: flex; flex-direction: column; align-items: flex-start; gap: 10px;
        font-family: 'Figtree', ui-sans-serif, system-ui, sans-serif;
    }
    /* Hidden on mobile — bottom nav takes over there */
    @media (max-width: 767px) {
        .zw-capsule-root { display: none !important; }
    }

    /* Capsule */
    .zw-capsule {
        background: #1f2937;
        border-radius: 999px;
        padding: 7px 10px 7px 8px;
        display: flex; align-items: center; gap: 10px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.25);
        border: 1px solid rgba(255,255,255,0.08);
        user-select: none;
        min-width: 240px;
    }
    .zw-capsule__avatar-btn { background:none; border:none; padding:0; cursor:pointer; flex-shrink:0; }
    .zw-capsule__avatar {
        display:grid; place-items:center;
        width: 36px; height: 36px; border-radius: 999px;
        background: #0284c7; color: #fff;
        font-size: 13px; font-weight: 800;
        transition: opacity 150ms;
    }
    .zw-capsule__avatar-btn:hover .zw-capsule__avatar { opacity: 0.85; }

    .zw-capsule__id {
        background:none; border:none; padding:0; cursor:pointer;
        flex: 1; min-width: 0; text-align: left; color: inherit;
    }
    .zw-capsule__name { font-size: 13px; font-weight: 700; color: #fff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .zw-capsule__role { font-size: 10.5px; color: rgba(255,255,255,0.5); margin-top: 1px; }

    .zw-capsule__icon-btn {
        width: 34px; height: 34px; border-radius: 999px;
        border: none; cursor: pointer;
        background: rgba(255,255,255,0.08);
        color: rgba(255,255,255,0.7);
        display: grid; place-items: center;
        position: relative; flex-shrink: 0;
        transition: background 150ms;
    }
    .zw-capsule__icon-btn:hover { background: rgba(255,255,255,0.16); color: #fff; }
    .zw-capsule__notif-badge {
        position: absolute; top: 6px; right: 6px;
        width: 8px; height: 8px; border-radius: 999px;
        background: #ef4444; border: 1.5px solid #1f2937;
    }

    /* Profile popup */
    .zw-capsule__profile {
        background: #1f2937; border-radius: 16px;
        box-shadow: 0 8px 40px rgba(0,0,0,0.35);
        padding: 6px 0; width: 240px; overflow: hidden;
        border: 1px solid rgba(255,255,255,0.08);
    }
    .zw-capsule__profile-head { padding: 14px 18px 12px; border-bottom: 1px solid rgba(255,255,255,0.08); }
    .zw-capsule__profile-name { font-size: 14px; font-weight: 700; color: #fff; }
    .zw-capsule__profile-tenant { font-size: 11.5px; color: rgba(255,255,255,0.45); margin-top: 1px; }
    .zw-capsule__profile-list { padding: 6px 0; }
    .zw-capsule__profile-form { margin: 0; padding: 0; }
    .zw-capsule__profile-form button { width: 100%; background: none; border: none; cursor: pointer; font-family: inherit; }
    .zw-capsule__profile-item {
        padding: 11px 18px; display: flex; align-items: center; gap: 12px;
        cursor: pointer; transition: background 100ms;
        color: rgba(255,255,255,0.85);
        font-size: 13.5px; font-weight: 500; text-decoration: none;
    }
    .zw-capsule__profile-item:hover { background: rgba(255,255,255,0.06); color: #fff; }
    .zw-capsule__profile-item--danger { color: #f87171; }
    .zw-capsule__profile-item--danger:hover { background: rgba(248,113,113,0.1); color: #fca5a5; }

    /* Notification popup */
    .zw-capsule__notif {
        background: #fff; border-radius: 16px;
        box-shadow: 0 8px 40px rgba(0,0,0,0.18);
        border: 1px solid #e5e7eb;
        width: 320px; max-height: 70vh; overflow-y: auto;
    }
    .dark .zw-capsule__notif { background: #1f2937; border-color: #374151; }
    .zw-capsule__notif-head {
        padding: 14px 18px 10px; border-bottom: 1px solid #f3f4f6;
        display: flex; justify-content: space-between; align-items: center;
        position: sticky; top: 0; background: inherit; z-index: 1;
    }
    .dark .zw-capsule__notif-head { border-bottom-color: #374151; }
    .zw-capsule__notif-title { font-size: 13px; font-weight: 700; color: #111827; }
    .dark .zw-capsule__notif-title { color: #f9fafb; }
    .zw-capsule__notif-action { font-size: 11px; color: #0284c7; font-weight: 600; cursor: pointer; text-decoration: none; }
    .zw-capsule__notif-item {
        padding: 11px 18px; display: flex; gap: 10px; align-items: flex-start;
        border-bottom: 1px solid #f9fafb; text-decoration: none; color: inherit;
    }
    .dark .zw-capsule__notif-item { border-bottom-color: #1f2937; }
    .zw-capsule__notif-item:last-child { border-bottom: none; }
    .zw-capsule__notif-item:hover { background: #f9fafb; }
    .dark .zw-capsule__notif-item:hover { background: #111827; }
    .zw-capsule__notif-item--unread { background: #f0f9ff; }
    .dark .zw-capsule__notif-item--unread { background: #0c4a6e30; }
    .zw-capsule__notif-dot { width: 7px; height: 7px; border-radius: 999px; flex-shrink: 0; margin-top: 6px; }
    .zw-capsule__notif-dot--on { background: #0284c7; }
    .zw-capsule__notif-body { flex: 1; min-width: 0; }
    .zw-capsule__notif-text { font-size: 12.5px; color: #1f2937; font-weight: 600; line-height: 1.4; }
    .dark .zw-capsule__notif-text { color: #f9fafb; }
    .zw-capsule__notif-detail { font-size: 11.5px; color: #6b7280; margin-top: 2px; line-height: 1.4; }
    .zw-capsule__notif-time { font-size: 10.5px; color: #9ca3af; margin-top: 4px; }
    .zw-capsule__notif-empty { padding: 28px 18px; text-align: center; font-size: 12.5px; color: #9ca3af; }

    /* Pop-in animation */
    .zw-pop-enter { transition: opacity 180ms cubic-bezier(0.34,1.56,0.64,1), transform 180ms cubic-bezier(0.34,1.56,0.64,1); }
    .zw-pop-enter-from { opacity: 0; transform: translateY(10px) scale(0.97); }
    .zw-pop-enter-to   { opacity: 1; transform: translateY(0) scale(1); }
</style>
@endpush
@endonce

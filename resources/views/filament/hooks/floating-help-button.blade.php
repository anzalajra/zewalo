@php
    $docUrl = 'https://' . config('app.domain', 'localhost') . '/documentation';
    $changelogUrl = 'https://' . config('app.domain', 'localhost') . '/changelog';
@endphp

<div
    x-data="{ open: false }"
    @click.outside="open = false"
    @keydown.escape.window="open = false"
    class="zw-help-root"
>
    {{-- Popup menu --}}
    <div
        x-show="open"
        x-cloak
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-1"
        class="zw-help-popup"
        role="menu"
    >
        <div class="zw-help-popup__head">
            <div class="zw-help-popup__title">Butuh bantuan?</div>
            <div class="zw-help-popup__sub">Buka dokumentasi atau lihat changelog terbaru.</div>
        </div>

        <a href="{{ $docUrl }}" target="_blank" rel="noopener" class="zw-help-popup__item" role="menuitem">
            <span class="zw-help-popup__icon zw-help-popup__icon--primary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/></svg>
            </span>
            <div class="zw-help-popup__body">
                <div class="zw-help-popup__label">Documentation</div>
                <div class="zw-help-popup__desc">Panduan & FAQ penggunaan platform</div>
            </div>
            <svg class="zw-help-popup__chev" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 19.5 15-15m0 0H8.25m11.25 0v11.25"/></svg>
        </a>

        <a href="{{ $changelogUrl }}" target="_blank" rel="noopener" class="zw-help-popup__item" role="menuitem">
            <span class="zw-help-popup__icon zw-help-popup__icon--muted">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
            </span>
            <div class="zw-help-popup__body">
                <div class="zw-help-popup__label">Changelog</div>
                <div class="zw-help-popup__desc">Riwayat update & rilis terbaru</div>
            </div>
            <svg class="zw-help-popup__chev" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 19.5 15-15m0 0H8.25m11.25 0v11.25"/></svg>
        </a>
    </div>

    {{-- Trigger button --}}
    <button
        type="button"
        @click="open = !open"
        :aria-expanded="open"
        aria-label="Bantuan & Dokumentasi"
        class="zw-help-fab"
    >
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="zw-help-fab__icon zw-help-fab__icon--default">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z"/>
        </svg>
        <svg x-show="open" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="zw-help-fab__icon zw-help-fab__icon--close">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
        </svg>
    </button>
</div>

@once
    @push('styles')
        <style>
            .zw-help-root {
                position: fixed;
                right: 1.25rem;
                bottom: 1.25rem;
                z-index: 9990;
                font-family: inherit;
            }

            .zw-help-fab {
                width: 3rem;
                height: 3rem;
                border-radius: 9999px;
                background: rgb(var(--primary-600, 79 70 229));
                color: #fff;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 10px 25px -5px rgba(0,0,0,0.15), 0 4px 10px -4px rgba(0,0,0,0.1);
                border: none;
                cursor: pointer;
                transition: transform 0.15s ease, box-shadow 0.15s ease, background 0.15s ease;
                position: relative;
            }
            .zw-help-fab:hover {
                transform: translateY(-1px);
                box-shadow: 0 12px 28px -6px rgba(0,0,0,0.2), 0 6px 12px -4px rgba(0,0,0,0.12);
            }
            .zw-help-fab:focus-visible {
                outline: 2px solid rgb(var(--primary-400, 129 140 248));
                outline-offset: 3px;
            }
            .zw-help-fab__icon {
                width: 1.4rem;
                height: 1.4rem;
                position: absolute;
            }

            .zw-help-popup {
                position: absolute;
                right: 0;
                bottom: calc(100% + 0.625rem);
                width: 19rem;
                max-width: calc(100vw - 2rem);
                background: #fff;
                border: 1px solid rgb(226 232 240);
                border-radius: 0.875rem;
                box-shadow: 0 20px 40px -8px rgba(15, 23, 42, 0.18), 0 8px 16px -8px rgba(15, 23, 42, 0.08);
                overflow: hidden;
            }
            .zw-help-popup__head {
                padding: 0.875rem 1rem 0.75rem;
                border-bottom: 1px solid rgb(241 245 249);
            }
            .zw-help-popup__title {
                font-size: 0.875rem;
                font-weight: 700;
                color: rgb(15 23 42);
            }
            .zw-help-popup__sub {
                font-size: 0.75rem;
                color: rgb(100 116 139);
                margin-top: 0.125rem;
            }
            .zw-help-popup__item {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                padding: 0.75rem 1rem;
                text-decoration: none;
                color: rgb(15 23 42);
                transition: background 0.12s ease;
            }
            .zw-help-popup__item:hover {
                background: rgb(248 250 252);
            }
            .zw-help-popup__item + .zw-help-popup__item {
                border-top: 1px solid rgb(241 245 249);
            }
            .zw-help-popup__icon {
                flex-shrink: 0;
                width: 2.25rem;
                height: 2.25rem;
                border-radius: 0.5rem;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }
            .zw-help-popup__icon svg {
                width: 1.15rem;
                height: 1.15rem;
            }
            .zw-help-popup__icon--primary {
                background: rgba(var(--primary-500, 99 102 241), 0.12);
                color: rgb(var(--primary-600, 79 70 229));
            }
            .zw-help-popup__icon--muted {
                background: rgb(241 245 249);
                color: rgb(71 85 105);
            }
            .zw-help-popup__body {
                flex: 1;
                min-width: 0;
            }
            .zw-help-popup__label {
                font-size: 0.875rem;
                font-weight: 600;
                line-height: 1.2;
            }
            .zw-help-popup__desc {
                font-size: 0.75rem;
                color: rgb(100 116 139);
                margin-top: 0.125rem;
                line-height: 1.35;
            }
            .zw-help-popup__chev {
                width: 0.95rem;
                height: 0.95rem;
                color: rgb(148 163 184);
                flex-shrink: 0;
            }

            /* Dark mode */
            .dark .zw-help-popup {
                background: rgb(15 23 42);
                border-color: rgb(30 41 59);
            }
            .dark .zw-help-popup__head { border-bottom-color: rgb(30 41 59); }
            .dark .zw-help-popup__title { color: rgb(241 245 249); }
            .dark .zw-help-popup__sub { color: rgb(148 163 184); }
            .dark .zw-help-popup__item { color: rgb(226 232 240); }
            .dark .zw-help-popup__item:hover { background: rgb(30 41 59); }
            .dark .zw-help-popup__item + .zw-help-popup__item { border-top-color: rgb(30 41 59); }
            .dark .zw-help-popup__icon--muted {
                background: rgb(30 41 59);
                color: rgb(203 213 225);
            }
            .dark .zw-help-popup__desc { color: rgb(148 163 184); }
            .dark .zw-help-popup__chev { color: rgb(100 116 139); }

            /* Don't collide with mobile bottom nav */
            @media (max-width: 768px) {
                .zw-help-root {
                    bottom: 5rem;
                    right: 1rem;
                }
                .zw-help-fab {
                    width: 2.75rem;
                    height: 2.75rem;
                }
                .zw-help-popup {
                    width: 17rem;
                }
            }
        </style>
    @endpush
@endonce

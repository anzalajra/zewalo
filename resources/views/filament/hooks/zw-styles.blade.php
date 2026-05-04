{{-- Custom CSS for Zewalo dashboard, floating capsule, and mobile bottom nav. --}}
{{-- Injected at panels::styles.after so it lands in <head> after Filament styles. --}}
<style>
    /* ─────────────────────────────────────────────────────── */
    /* DASHBOARD HOME WIDGET                                   */
    /* ─────────────────────────────────────────────────────── */

    .zw-home { font-family: 'Figtree', ui-sans-serif, system-ui, sans-serif; color:#111827; }
    .zw-desktop { display:flex; flex-direction:column; gap:18px; }
    .zw-mobile  { display:none; }
    @media (max-width: 767px) {
        .zw-desktop { display:none; }
        .zw-mobile  { display:block; }
    }

    /* Welcome row */
    .zw-welcome { background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:18px 24px; }
    .zw-welcome__date { font-size:12px; color:#9ca3af; font-weight:500; margin-bottom:4px; }
    .zw-welcome__hello { font-size:22px; font-weight:800; letter-spacing:-0.01em; color:#111827; line-height:1.2; }
    .zw-welcome__sub { font-size:13.5px; color:#6b7280; margin-top:6px; }
    .zw-welcome__sub strong { color:#0284c7; font-weight:700; }
    .dark .zw-welcome { background:#1f2937; border-color:#374151; }
    .dark .zw-welcome__hello { color:#f9fafb; }
    .dark .zw-welcome__sub { color:#9ca3af; }

    /* Row 2: Banner + 2x2 stats */
    .zw-row2 { display:grid; grid-template-columns:1fr 1fr; gap:18px; align-items:stretch; }
    @media (max-width: 1023px) { .zw-row2 { grid-template-columns: 1fr; } }

    .zw-banner {
        background: linear-gradient(135deg, #0284c7 0%, #075985 100%);
        color:#fff; border-radius:12px; overflow:hidden;
        display:flex; flex-direction:column; min-height:180px; position:relative;
    }
    .zw-banner__inner { flex:1; padding:22px 24px; display:flex; flex-direction:column; gap:6px; }
    .zw-banner__label { font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:0.1em; opacity:0.8; }
    .zw-banner__title { font-size:17px; font-weight:700; line-height:1.3; }
    .zw-banner__body  { font-size:13px; opacity:0.9; line-height:1.55; }
    .zw-banner__footer { padding:12px 24px; background:rgba(0,0,0,0.15); display:flex; justify-content:space-between; align-items:center; }
    .zw-banner__dots { display:flex; gap:6px; }
    .zw-banner__dot { width:6px; height:6px; border-radius:99px; background:rgba(255,255,255,0.4); border:none; cursor:pointer; padding:0; transition:all .2s; }
    .zw-banner__dot.is-active { background:#fff; width:18px; }
    .zw-banner__count { font-size:11px; opacity:0.65; }

    .zw-stats4 { display:grid; grid-template-columns:1fr 1fr; grid-template-rows:1fr 1fr; gap:12px; }
    .zw-stat4 {
        background:#fff; border:1px solid #e5e7eb; border-radius:10px;
        padding:16px 18px; box-shadow:0 1px 2px rgba(0,0,0,0.04);
        display:flex; flex-direction:column; gap:10px;
        text-decoration:none; color:inherit; transition:box-shadow .15s, transform .15s;
    }
    .zw-stat4:hover { box-shadow:0 4px 12px rgba(0,0,0,0.07); transform:translateY(-1px); }
    .zw-stat4__top { display:flex; justify-content:space-between; align-items:flex-start; }
    .zw-stat4__value { font-size:28px; font-weight:800; line-height:1; }
    .zw-stat4__icon { width:40px; height:40px; border-radius:9px; display:grid; place-items:center; flex-shrink:0; }
    .zw-stat4__label { font-size:12px; font-weight:500; color:#6b7280; }
    .dark .zw-stat4 { background:#1f2937; border-color:#374151; }
    .dark .zw-stat4__label { color:#9ca3af; }

    /* Row 3: Recent table + Chart */
    .zw-row3 { display:grid; grid-template-columns:3fr 2fr; gap:18px; align-items:start; }
    @media (max-width: 1023px) { .zw-row3 { grid-template-columns: 1fr; } }

    .zw-card { background:#fff; border:1px solid #e5e7eb; border-radius:10px; box-shadow:0 1px 2px rgba(0,0,0,0.04); }
    .dark .zw-card { background:#1f2937; border-color:#374151; }
    .zw-card__head { padding:14px 20px; border-bottom:1px solid #f3f4f6; display:flex; align-items:center; justify-content:space-between; gap:10px; flex-wrap:wrap; }
    .dark .zw-card__head { border-bottom-color:#374151; }
    .zw-card__title { font-size:14px; font-weight:700; color:#111827; }
    .dark .zw-card__title { color:#f9fafb; }
    .zw-card__action { font-size:12px; color:#0284c7; cursor:pointer; font-weight:600; text-decoration:none; }
    .zw-card__body { padding:18px 20px; }

    /* Recent table */
    .zw-tablewrap { overflow:auto; border-radius:0 0 10px 10px; }
    .zw-table { width:100%; border-collapse:collapse; }
    .zw-table th {
        font-size:10.5px; font-weight:700; text-transform:uppercase; letter-spacing:0.06em;
        color:#6b7280; padding:10px 16px; text-align:left;
        background:#f9fafb; border-bottom:1px solid #f3f4f6;
    }
    .dark .zw-table th { background:#111827; color:#9ca3af; border-bottom-color:#374151; }
    .zw-table td { padding:11px 16px; font-size:13px; border-bottom:1px solid #f9fafb; color:#374151; }
    .dark .zw-table td { color:#d1d5db; border-bottom-color:#1f2937; }
    .zw-table tr:last-child td { border-bottom:none; }
    .zw-table tbody tr:hover { background:#f0f9ff; }
    .dark .zw-table tbody tr:hover { background:rgba(12,74,110,0.18); }
    .zw-mono { font-family: ui-monospace, monospace; font-size:11.5px; color:#0369a1; font-weight:700; }
    .zw-table__strong { font-weight:600; color:#111827; }
    .dark .zw-table__strong { color:#f9fafb; }
    .zw-table__muted { color:#6b7280; }
    .zw-table__date  { font-size:12px; color:#9ca3af; }
    .zw-table__bold  { font-weight:600; color:#1f2937; }
    .dark .zw-table__bold { color:#e5e7eb; }
    .zw-empty { padding:24px; text-align:center; color:#9ca3af; font-size:13px; }

    /* Pills */
    .zw-pill { display:inline-flex; align-items:center; padding:3px 10px; border-radius:999px; font-size:11px; font-weight:600; line-height:1.5; }

    /* Chart */
    .zw-chart__head { gap:8px; }
    .zw-chart__controls { display:flex; align-items:center; gap:10px; flex-wrap:wrap; }
    .zw-check { display:flex; align-items:center; gap:5px; font-size:12px; font-weight:600; cursor:pointer; user-select:none; }
    .zw-check__box { width:15px; height:15px; border-radius:4px; border:2px solid #d1d5db; display:flex; align-items:center; justify-content:center; transition:all .15s; flex-shrink:0; }
    .zw-check__on { color:#374151; }
    .zw-check__off { color:#9ca3af; }
    .dark .zw-check__on { color:#e5e7eb; }
    .zw-select {
        appearance:none; -webkit-appearance:none;
        background:#f9fafb; border:1px solid #e5e7eb;
        border-radius:7px; padding:5px 24px 5px 10px;
        font-size:12px; font-weight:600; color:#374151; cursor:pointer; outline:none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
        background-repeat:no-repeat; background-position:right 7px center;
    }
    .dark .zw-select { background:#111827; border-color:#374151; color:#e5e7eb; }
    .zw-chart__svgwrap { position:relative; }
    .zw-chart__tip {
        position:absolute; top:0;
        background:#1f2937; color:#fff; border-radius:8px; padding:6px 10px;
        font-size:11px; font-weight:600; pointer-events:none; white-space:nowrap;
        box-shadow:0 4px 12px rgba(0,0,0,0.2); transform:translateX(-50%);
    }
    .zw-chart__tipMonth { color:#9ca3af; margin-bottom:3px; }
    .zw-chart__tipB { color:#38bdf8; }
    .zw-chart__tipR { color:#34d399; }
    .zw-chart__empty { height:140px; display:flex; align-items:center; justify-content:center; color:#9ca3af; font-size:13px; }
    .zw-chart__summary { margin-top:14px; padding-top:14px; border-top:1px solid #f3f4f6; display:flex; gap:20px; flex-wrap:wrap; }
    .dark .zw-chart__summary { border-top-color:#374151; }
    .zw-chart__sumItem { display:flex; align-items:center; gap:8px; }
    .zw-chart__sumIcon { width:28px; height:28px; border-radius:8px; display:grid; place-items:center; }
    .zw-chart__sumValue { font-size:18px; font-weight:800; line-height:1; }
    .zw-chart__sumLabel { font-size:10px; color:#9ca3af; margin-top:2px; }

    /* Mobile hero */
    .zw-hero {
        background: linear-gradient(145deg, #0284c7 0%, #075985 100%);
        border-radius:20px; padding:24px 22px; margin-bottom:20px;
        color:#fff; position:relative; overflow:hidden;
    }
    .zw-hero__deco { position:absolute; border-radius:50%; pointer-events:none; }
    .zw-hero__deco--1 { top:-40px; right:-40px; width:160px; height:160px; background:rgba(255,255,255,0.06); }
    .zw-hero__deco--2 { bottom:-30px; right:20px; width:100px; height:100px; background:rgba(255,255,255,0.04); }
    .zw-hero__top { display:flex; justify-content:space-between; align-items:flex-start; }
    .zw-hero__greet { font-size:20px; font-weight:800; letter-spacing:-0.01em; line-height:1.2; }
    .zw-hero__bell {
        width:36px; height:36px; border-radius:99px; background:rgba(255,255,255,0.18);
        display:grid; place-items:center; color:#fff; flex-shrink:0; margin-left:8px;
        text-decoration:none; border:none; cursor:pointer;
    }
    .zw-hero__bell:hover { background:rgba(255,255,255,0.28); }
    .zw-hero__date { font-size:12px; opacity:0.72; margin-top:2px; margin-bottom:24px; font-weight:500; }
    .zw-hero__stats { display:grid; grid-template-columns:1fr 1fr 1fr; }
    .zw-hero__stat  { text-align:center; padding:0 8px; }
    .zw-hero__num   { font-size:36px; font-weight:800; line-height:1; letter-spacing:-0.02em; }
    .zw-hero__label { font-size:11px; font-weight:600; opacity:0.9; margin-top:4px; }
    .zw-hero__sub   { font-size:10px; opacity:0.6; margin-top:1px; }

    .zw-mob-section { font-size:13px; font-weight:700; color:#374151; margin-bottom:12px; letter-spacing:-0.01em; }
    .dark .zw-mob-section { color:#d1d5db; }

    .zw-quick { display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:22px; }
    .zw-quick__card {
        background:#fff; border:1px solid #f3f4f6; border-radius:16px;
        padding:18px 16px; min-height:108px;
        display:flex; flex-direction:column; align-items:flex-start; gap:10px;
        text-decoration:none; color:inherit;
        box-shadow:0 1px 6px rgba(0,0,0,0.05);
        transition:transform .15s, box-shadow .15s;
    }
    .zw-quick__card:active { transform:scale(0.97); }
    .dark .zw-quick__card { background:#1f2937; border-color:#374151; }
    .zw-quick__iconwrap { position:relative; }
    .zw-quick__icon {
        width:42px; height:42px; border-radius:12px;
        background:rgba(2,132,199,0.12); color:#0284c7;
        display:grid; place-items:center;
    }
    .zw-quick__badge {
        position:absolute; top:-5px; right:-5px;
        background:#ef4444; color:#fff; font-size:9px; font-weight:800;
        min-width:17px; height:17px; padding:0 4px; border-radius:99px;
        display:grid; place-items:center; border:2px solid #fff;
    }
    .dark .zw-quick__badge { border-color:#1f2937; }
    .zw-quick__label { font-size:14px; font-weight:700; color:#111827; line-height:1.2; }
    .dark .zw-quick__label { color:#f9fafb; }
    .zw-quick__sub { font-size:11.5px; color:#9ca3af; margin-top:1px; }

    .zw-mob-list { display:flex; flex-direction:column; gap:10px; }
    .zw-mob-list__item {
        background:#fff; border:1px solid #f3f4f6; border-radius:14px;
        padding:13px 14px; box-shadow:0 1px 6px rgba(0,0,0,0.05);
        display:flex; align-items:center; gap:12px;
        text-decoration:none; color:inherit;
    }
    .dark .zw-mob-list__item { background:#1f2937; border-color:#374151; }
    .zw-mob-list__avatar {
        width:38px; height:38px; border-radius:99px;
        background:rgba(2,132,199,0.12); color:#0284c7;
        display:grid; place-items:center;
        font-size:13px; font-weight:700; flex-shrink:0;
    }
    .zw-mob-list__body { flex:1; min-width:0; }
    .zw-mob-list__name { font-size:13.5px; font-weight:700; color:#111827; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .dark .zw-mob-list__name { color:#f9fafb; }
    .zw-mob-list__item-name { font-size:11.5px; color:#9ca3af; margin-top:1px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .zw-mob-list__right { display:flex; flex-direction:column; align-items:flex-end; gap:5px; flex-shrink:0; }
    .zw-mob-list__total { font-size:11.5px; font-weight:700; color:#374151; }
    .dark .zw-mob-list__total { color:#d1d5db; }
    .zw-mob-empty { background:#fff; border:1px dashed #e5e7eb; border-radius:14px; }
    .dark .zw-mob-empty { background:#1f2937; border-color:#374151; }


    /* ─────────────────────────────────────────────────────── */
    /* FLOATING PROFILE CAPSULE (desktop + tablet)             */
    /* ─────────────────────────────────────────────────────── */

    [x-cloak] { display:none !important; }

    .zw-capsule-root {
        position: fixed; bottom: 20px; left: 20px; z-index: 9999;
        display: flex; flex-direction: column; align-items: flex-start; gap: 10px;
        font-family: 'Figtree', ui-sans-serif, system-ui, sans-serif;
    }
    @media (max-width: 767px) {
        .zw-capsule-root { display: none !important; }
    }

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
    .dark .zw-capsule__notif-item--unread { background: rgba(12,74,110,0.18); }
    .zw-capsule__notif-dot { width: 7px; height: 7px; border-radius: 999px; flex-shrink: 0; margin-top: 6px; }
    .zw-capsule__notif-dot--on { background: #0284c7; }
    .zw-capsule__notif-body { flex: 1; min-width: 0; }
    .zw-capsule__notif-text { font-size: 12.5px; color: #1f2937; font-weight: 600; line-height: 1.4; }
    .dark .zw-capsule__notif-text { color: #f9fafb; }
    .zw-capsule__notif-detail { font-size: 11.5px; color: #6b7280; margin-top: 2px; line-height: 1.4; }
    .zw-capsule__notif-time { font-size: 10.5px; color: #9ca3af; margin-top: 4px; }
    .zw-capsule__notif-empty { padding: 28px 18px; text-align: center; font-size: 12.5px; color: #9ca3af; }

    .zw-pop-enter { transition: opacity 180ms cubic-bezier(0.34,1.56,0.64,1), transform 180ms cubic-bezier(0.34,1.56,0.64,1); }
    .zw-pop-enter-from { opacity: 0; transform: translateY(10px) scale(0.97); }
    .zw-pop-enter-to   { opacity: 1; transform: translateY(0) scale(1); }


    /* ─────────────────────────────────────────────────────── */
    /* MOBILE BOTTOM NAV                                       */
    /* ─────────────────────────────────────────────────────── */

    .zw-mobnav-root { display: none; font-family: 'Figtree', ui-sans-serif, system-ui, sans-serif; }
    @media (max-width: 767px) {
        .zw-mobnav-root { display: block; }
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

    .zw-mobnav__plus-slot { flex: 1.2; display: flex; align-items: center; justify-content: center; }
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

    .zw-mobnav__backdrop {
        position: fixed; inset: 0; z-index: 9996;
        background: rgba(0,0,0,0.32); backdrop-filter: blur(2px);
    }

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
        background: rgba(2,132,199,0.10); color: #0284c7;
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
        background: rgba(2,132,199,0.10); color: #0284c7;
        display: grid; place-items: center;
    }

    .zw-sheet-enter { transition: opacity 200ms cubic-bezier(0.34,1.56,0.64,1), transform 200ms cubic-bezier(0.34,1.56,0.64,1); }
    .zw-sheet-enter-from { opacity: 0; transform: translateY(16px) scale(0.97); }
    .zw-sheet-enter-to   { opacity: 1; transform: translateY(0) scale(1); }


    /* ─────────────────────────────────────────────────────── */
    /* HIDE FILAMENT'S BUILT-IN SIDEBAR-FOOTER NOTIFICATION    */
    /* (replaced by floating profile capsule on desktop;       */
    /*  topbar bell + bottom nav handle it on mobile)          */
    /* ─────────────────────────────────────────────────────── */
    .fi-sidebar-footer { display: none !important; }
</style>

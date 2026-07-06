{{--
    Compact-mode immersive tweaks for the rental OPERATION pages (view / pickup /
    return). Included only on those blades, so these rules never touch the list,
    dashboard, or any other surface.

    In compact mode (body.gr-compact = phone + portrait tablet ≤1024px, set by the
    gr-compact engine in responsive-navigation.blade.php) the operator is running a
    focused flow, so:
      - drop the floating account capsule, which otherwise hovers over the operation
        UI / action buttons at the bottom-left;
      - reclaim Filament's horizontal page gutters for more working room;
      - let wide multi-column info tables scroll smoothly instead of crushing columns.

    The bottom nav is intentionally kept so the operator can still navigate away
    mid-flow. Breakpoint note: < 768px = phone, 768–1023px / portrait = tablet.
--}}
<style>
    body.gr-compact .zw-capsule-root { display: none !important; }

    @media (max-width: 1023px), (orientation: portrait) {
        /* Reclaim page gutters for more room on small screens. */
        .fi-main {
            padding-left: 0.75rem !important;
            padding-right: 0.75rem !important;
        }
        /* Wide 4-column info tables: scroll smoothly rather than crush columns. */
        .fi-section .overflow-x-auto {
            -webkit-overflow-scrolling: touch;
        }
    }

    /* Phones get the tightest gutters. */
    @media (max-width: 767px) {
        .fi-main {
            padding-left: 0.5rem !important;
            padding-right: 0.5rem !important;
        }
    }
</style>

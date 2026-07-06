{{--
    Orientation-aware compact-mode engine (ported from warehouse-ftv).

    Sets `body.gr-compact` when the viewport is PORTRAIT or narrower than 1024px,
    and clears it only in LANDSCAPE at >= 1024px. This one class gates ALL of
    Zewalo's mobile chrome — the bottom nav (zw-mobnav) and floating capsule in
    zw-styles.blade.php, plus the per-page immersive layouts (RentalEditor,
    view-rental, pickup/return) — consistently, and flips instantly on rotation
    with no page reload.

    Why orientation must beat width: a 12.9"/13" iPad Pro in portrait is exactly
    1024px wide, so a width-only rule (the old `is-mobile-view` @media 768px)
    wrongly treated it as desktop. `is-mobile-view` is still toggled below for
    backwards-compatibility with any width-based consumers.
--}}
<style>
/*
 * Remove Filament's sidebar chrome in compact mode — navigation is the bottom bar
 * (zw-mobnav) + floating capsule, so none of the sidebar remnants are needed:
 *  - .fi-sidebar          the whole sidebar (would otherwise show collapsed / off-canvas)
 *  - .fi-layout-sidebar-toggle-btn-ctn  the top-left hamburger Filament renders when the
 *                         topbar is off (opens an off-canvas sidebar we've hidden)
 *  - .fi-sidebar-close-overlay  the dimmer shown while that off-canvas sidebar is open
 * This hook renders at body.end (after the head stylesheet), so at equal specificity
 * `display:none` wins with no Vite rebuild. Only active under .gr-compact, so the
 * landscape / desktop sidebar is untouched. On the phone top-nav layout there is no
 * sidebar rendered at all, so these are harmless no-ops there.
 */
body.gr-compact .fi-sidebar,
body.gr-compact .fi-sidebar.fi-main-sidebar,
body.gr-compact .fi-layout-sidebar-toggle-btn-ctn,
body.gr-compact .fi-sidebar-close-overlay {
    display: none !important;
}
</style>

<script>
(function() {
    'use strict';

    var portraitMq = window.matchMedia('(orientation: portrait)');
    var wideMq = window.matchMedia('(min-width: 1024px)');

    /*
     * Compact (mobile-style) when the viewport is PORTRAIT, or narrower than 1024px.
     * Expanded (desktop sidebar) only when LANDSCAPE *and* width >= 1024px.
     * Driving this from JS (not CSS media queries alone) lets one class, `gr-compact`,
     * gate both the bottom nav / capsule (zw-styles) and the sidebar collapse (above)
     * consistently, flipping instantly on rotation with no page reload.
     */
    function applyMode() {
        var isCompact = portraitMq.matches || window.innerWidth < 1024;
        document.body.classList.toggle('gr-compact', isCompact);
        // Kept for backwards-compatibility with any width-based consumers.
        document.body.classList.toggle('is-mobile-view', window.innerWidth < 768);
    }

    // Rotation + resize. matchMedia 'change' fires the instant orientation flips.
    window.addEventListener('resize', applyMode);
    window.addEventListener('orientationchange', applyMode);
    if (portraitMq.addEventListener) {
        portraitMq.addEventListener('change', applyMode);
        wideMq.addEventListener('change', applyMode);
    } else if (portraitMq.addListener) {
        // Safari < 14 fallback
        portraitMq.addListener(applyMode);
        wideMq.addListener(applyMode);
    }
    document.addEventListener('DOMContentLoaded', applyMode);
    // Re-apply after Filament/Livewire SPA navigation, which can morph the <body>.
    document.addEventListener('livewire:navigated', applyMode);
    if (document.readyState !== 'loading') {
        applyMode();
    }
})();
</script>

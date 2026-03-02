<script>
(function() {
    'use strict';
    
    const MOBILE_BREAKPOINT = 768;
    let currentMode = null;
    
    function isMobile() {
        return window.innerWidth < MOBILE_BREAKPOINT;
    }
    
    function applyMobileNavigation() {
        const body = document.body;
        
        // Add mobile-nav class
        body.classList.add('fi-mobile-nav');
        
        // Force topbar to be visible if it exists but is hidden
        const topbar = document.querySelector('.fi-topbar');
        if (topbar) {
            topbar.style.display = '';
        }
        
        // Hide sidebar on mobile - will use hamburger menu
        const sidebar = document.querySelector('.fi-sidebar');
        if (sidebar && !sidebar.classList.contains('fi-sidebar-open')) {
            sidebar.setAttribute('data-mobile-hidden', 'true');
        }
        
        currentMode = 'mobile';
    }
    
    function applyDesktopNavigation() {
        const body = document.body;
        
        // Remove mobile-nav class
        body.classList.remove('fi-mobile-nav');
        
        // Reset sidebar visibility
        const sidebar = document.querySelector('.fi-sidebar');
        if (sidebar) {
            sidebar.removeAttribute('data-mobile-hidden');
        }
        
        currentMode = 'desktop';
    }
    
    function handleResize() {
        const mobile = isMobile();
        
        if (mobile && currentMode !== 'mobile') {
            applyMobileNavigation();
        } else if (!mobile && currentMode !== 'desktop') {
            applyDesktopNavigation();
        }
    }
    
    // Initial check
    document.addEventListener('DOMContentLoaded', function() {
        handleResize();
    });
    
    // Listen for resize with debounce
    let resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(handleResize, 100);
    });
    
    // Also run immediately in case DOM is already loaded
    if (document.readyState !== 'loading') {
        handleResize();
    }
})();
</script>

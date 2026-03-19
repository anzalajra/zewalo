<!-- Header / Navbar -->
<header class="sticky top-0 z-50 w-full border-b border-slate-200 bg-background-light/80 backdrop-blur-md bg-white/80">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<div class="flex h-16 items-center justify-between">
<div class="flex items-center gap-2">
<a href="<?php echo e(route('home')); ?>" class="flex items-center gap-2">
<div class="text-primary">
<svg class="h-8 w-8" fill="currentColor" viewbox="0 0 48 48">
<path d="M36.7273 44C33.9891 44 31.6043 39.8386 30.3636 33.69C29.123 39.8386 26.7382 44 24 44C21.2618 44 18.877 39.8386 17.6364 33.69C16.3957 39.8386 14.0109 44 11.2727 44C7.25611 44 4 35.0457 4 24C4 12.9543 7.25611 4 11.2727 4C14.0109 4 16.3957 8.16144 17.6364 14.31C18.877 8.16144 21.2618 4 24 4C26.7382 4 29.123 8.16144 30.3636 14.31C31.6043 8.16144 33.9891 4 36.7273 4C40.7439 4 44 12.9543 44 24C44 35.0457 40.7439 44 36.7273 44Z"></path>
</svg>
</div>
<span class="text-xl font-bold tracking-tight">Zewalo</span>
</a>
</div>
<nav class="hidden md:flex items-center gap-8">
<a class="text-sm font-medium hover:text-primary transition-colors" href="<?php echo e(route('home')); ?>#features">Features</a>
<a class="text-sm font-medium hover:text-primary transition-colors" href="<?php echo e(route('home')); ?>#solutions">Solutions</a>
<a class="text-sm font-medium <?php echo e(request()->routeIs('landing.pricing') ? 'text-primary' : 'hover:text-primary transition-colors'); ?>" href="<?php echo e(route('landing.pricing')); ?>">Pricing</a>
<a class="text-sm font-medium hover:text-primary transition-colors" href="<?php echo e(route('home')); ?>#testimonials">Testimonials</a>
</nav>
<div class="flex items-center gap-3">
<a href="/login-tenant" class="hidden sm:inline-block text-sm font-semibold px-4 py-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">Login</a>
<a href="/register-tenant" class="bg-primary hover:bg-primary/90 text-white text-sm font-bold px-5 py-2.5 rounded-lg transition-all shadow-lg shadow-primary/20">Get Started</a>
</div>
</div>
</div>
</header>
<?php /**PATH D:\6. Kints\Projects\Zewalo\resources\views/landing/partials/header.blade.php ENDPATH**/ ?>
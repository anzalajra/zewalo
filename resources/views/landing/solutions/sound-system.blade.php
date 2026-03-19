<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Zewalo - Sound System Rental Solution</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#14B8A6",
                        "background-light": "#f6f8f8",
                        "background-dark": "#11211f",
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                    borderRadius: { "DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px" },
                },
            },
        }
    </script>
</head>

<body class="font-display bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 transition-colors duration-300">
    <!-- BEGIN: MainHeader -->
    @include('landing.partials.header')
    <!-- END: MainHeader -->

    <div class="relative flex h-auto min-h-screen w-full flex-col group/design-root overflow-x-hidden">
        <div class="layout-container flex h-full grow flex-col">
            <div class="px-4 md:px-20 lg:px-40 flex flex-1 justify-center py-10">
                <div class="layout-content-container flex flex-col max-w-[960px] flex-1">
                    <!-- Hero Section -->
                    <div class="flex flex-wrap justify-between gap-3 p-4">
                        <div class="flex min-w-72 flex-col gap-3">
                            <p class="text-slate-900 dark:text-slate-100 text-5xl font-black leading-tight tracking-[-0.033em]">
                                Sound System Rental Solution</p>
                            <p class="text-primary text-lg font-medium leading-normal uppercase tracking-wider">Case
                                Study: Complex Inventory Management</p>
                        </div>
                    </div>
                    <!-- Problem Statement -->
                    <div class="bg-white dark:bg-slate-800/50 rounded-xl p-6 mt-6 border border-primary/10">
                        <h2 class="text-slate-900 dark:text-slate-100 text-[28px] font-bold leading-tight tracking-[-0.015em] pb-3">
                            The Challenge: The Auxiliary Gear Black Hole</h2>
                        <p class="text-slate-700 dark:text-slate-300 text-lg font-normal leading-relaxed pb-3">
                            Managing hundreds of XLR cables, adapters, and connectors for large-scale music festivals
                            often leads to missing gear and disorganized warehouses. For every mixer rented, dozens of
                            small components must follow—and more importantly, return.
                        </p>
                    </div>
                    <!-- Before vs After -->
                    <h2 class="text-slate-900 dark:text-slate-100 text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-12">
                        The Kit Revolution</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4">
                        <div class="flex flex-col gap-3 pb-3 bg-white dark:bg-slate-800 p-4 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700">
                            <div class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-lg"
                                data-alt="Messy pile of tangled black audio cables on warehouse floor"
                                style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuC9Jsy-T9pbUsK67zMMmcLV0TU_stfQMHYVfruRhv0nUFR4dH5JKhPS19JLOrRvxB5JvFnngtvrYfqemLUR0PdzOcZcupp9wf2_JNDuWuhRYVN1YepIx4PNy_mFDlvICIVav64SBQXVd5QSOWi3cdRYxEB8Zumqs9sVS3pk-U5Bq3qkTwGVEq8mbzrZbXTDB-jO3CFLsLYVScHmJkJwWNHZUvhamKb30Zng903HAYVZHSPHxmcbYhNxE2CIGQP1CivFwsd55AFJcAMs");'>
                            </div>
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="material-symbols-outlined text-red-500">warning</span>
                                    <p class="text-slate-900 dark:text-slate-100 text-xl font-bold leading-normal">
                                        Before: Messy Inventory</p>
                                </div>
                                <p class="text-slate-600 dark:text-slate-400 text-base font-normal leading-normal">Loose
                                    cables and unorganized bins leading to frequent losses, double-bookings, and hours
                                    of manual counting during load-outs.</p>
                            </div>
                        </div>
                        <div class="flex flex-col gap-3 pb-3 bg-primary/5 dark:bg-primary/10 p-4 rounded-xl shadow-sm border border-primary/20">
                            <div class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-lg"
                                data-alt="Organized professional audio equipment in foam-padded flight cases"
                                style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuD0O9iXadPg63dWSF33Se0ObPeZhOSfJajehD6qIecqzCeefVPZQciqqetpvGakTqQNGUrXxM6P3yjV0uioE76q1OxrPUi1-Ary-LRsYgZHCaFFnJB6900BZWl6nCObjZenKJueSHUCDV0mq04Ly4sINvkzq3dH_kPa_6gaIySONVRPjllbhXqFY3CBg_0hdXst8axNg1T1ftzSQcvmH2UpMJ24mpl_SOsmGGftka283mcEQgSinBVKRx1tZ36YiHyj0K2Jy6oquGSY");'>
                            </div>
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="material-symbols-outlined text-primary">check_circle</span>
                                    <p class="text-slate-900 dark:text-slate-100 text-xl font-bold leading-normal">
                                        After: Smart Kits</p>
                                </div>
                                <p class="text-slate-600 dark:text-slate-400 text-base font-normal leading-normal">
                                    Standardized "Kits" treated as single units. Integrated tracking for every auxiliary
                                    component ensures nothing is left behind at the venue.</p>
                            </div>
                        </div>
                    </div>
                    <!-- Testimonial Section -->
                    <div class="my-12 p-8 bg-slate-900 rounded-2xl text-white flex flex-col md:flex-row gap-8 items-center">
                        <div class="w-24 h-24 rounded-full overflow-hidden flex-shrink-0 border-4 border-primary/30">
                            <img alt="Sound Engineer Profile" class="w-full h-full object-cover"
                                data-alt="Portrait of a professional male sound engineer with headphones"
                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuAzLTgKQxq9F4shQOyQR0u2Xi8hLYFeIl_ufQwgp6Ay2tGrZFLsmm9Xn2HzMk6vp86UyRYavUi_I5N0Xue5rYDn4AWajWtX2ipUo-CsZiNshAwzqIWLnSZZGhoPmIGxBF07sWFarbWh0ieE3YR6-9fxPwUKkuzAsQEpYWXO1zlyV8lIkxNXF-S_97zXQ-dYZ0hU9UBC2B5eiYPK6NZ5fv-LytzppBWt5RDUPQMmTBpls6gkVV2viBj73JBr4SFuSfyj60n9jdyAWsj2" />
                        </div>
                        <div class="flex flex-col">
                            <div class="flex mb-2 text-primary">
                                <span class="material-symbols-outlined font-fill-1">star</span>
                                <span class="material-symbols-outlined font-fill-1">star</span>
                                <span class="material-symbols-outlined font-fill-1">star</span>
                                <span class="material-symbols-outlined font-fill-1">star</span>
                                <span class="material-symbols-outlined font-fill-1">star</span>
                            </div>
                            <p class="text-xl italic font-medium leading-relaxed mb-4 text-slate-200">
                                "The Kit-based tracking changed our entire workflow. We used to lose 15% of our XLR
                                inventory per season. Now, that number is practically zero. It's the difference between
                                profit and loss on small rentals."
                            </p>
                            <div>
                                <p class="font-bold text-lg">Marcus Thorne</p>
                                <p class="text-primary text-sm">Lead Audio Tech, Sonic Blast Events</p>
                            </div>
                        </div>
                    </div>
                    <!-- FAQ Accordion -->
                    <div class="px-4 py-8">
                        <h2 class="text-slate-900 dark:text-slate-100 text-3xl font-bold mb-8">Frequently Asked Questions</h2>
                        <div class="space-y-4">
                            <div class="group border-b border-slate-200 dark:border-slate-700 pb-4">
                                <button class="flex w-full items-center justify-between py-2 text-left">
                                    <span class="text-lg font-semibold text-slate-800 dark:text-slate-200">How do Kits handle individual cable failures?</span>
                                    <span class="material-symbols-outlined text-primary group-hover:rotate-180 transition-transform">expand_more</span>
                                </button>
                                <div class="mt-2 text-slate-600 dark:text-slate-400">
                                    Our system allows for "hot-swapping" items within a kit. If a cable fails, you can
                                    mark it for repair and replace it from bulk stock while maintaining the kit's
                                    integrity for the rental contract.
                                </div>
                            </div>
                            <div class="group border-b border-slate-200 dark:border-slate-700 pb-4">
                                <button class="flex w-full items-center justify-between py-2 text-left">
                                    <span class="text-lg font-semibold text-slate-800 dark:text-slate-200">Can I create custom kits for specific event types?</span>
                                    <span class="material-symbols-outlined text-primary group-hover:rotate-180 transition-transform">expand_more</span>
                                </button>
                                <div class="mt-2 text-slate-600 dark:text-slate-400">
                                    Absolutely. You can define templates for 'DJ Setup', 'Live Band Small', or
                                    'Corporate Speech' and the system will automatically prompt the warehouse team to
                                    pick the required auxiliary gear.
                                </div>
                            </div>
                            <div class="group border-b border-slate-200 dark:border-slate-700 pb-4">
                                <button class="flex w-full items-center justify-between py-2 text-left">
                                    <span class="text-lg font-semibold text-slate-800 dark:text-slate-200">Is RFID required for the Kit system to work?</span>
                                    <span class="material-symbols-outlined text-primary group-hover:rotate-180 transition-transform">expand_more</span>
                                </button>
                                <div class="mt-2 text-slate-600 dark:text-slate-400">
                                    While RFID speeds up the process significantly, the system supports barcode scanning
                                    and manual check-lists. You can start with what you have and upgrade as you grow.
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- CTA Section -->
                    <div class="mt-16 mb-20 p-10 rounded-2xl bg-gradient-to-br from-primary to-teal-700 text-center flex flex-col items-center gap-6">
                        <h2 class="text-3xl md:text-4xl font-black text-white">Stop losing gear today</h2>
                        <p class="text-white/90 text-lg max-w-xl">
                            Join over 500 sound rental companies who have eliminated inventory loss with our
                            Kit-management software.
                        </p>
                        <div class="flex flex-wrap justify-center gap-4 pt-4">
                            <a href="/register-tenant" class="px-8 py-4 bg-white text-teal-900 font-bold rounded-xl hover:bg-slate-100 transition-colors shadow-lg shadow-black/10">
                                Start Free Trial
                            </a>
                            <a href="/contact" class="px-8 py-4 bg-teal-900/30 text-white font-bold rounded-xl border border-white/20 hover:bg-teal-900/40 transition-colors">
                                Schedule a Demo
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- BEGIN: MainFooter -->
    @include('landing.partials.footer')
    <!-- END: MainFooter -->
</body>

</html>

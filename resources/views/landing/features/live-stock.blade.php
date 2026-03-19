<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Live Inventory Stock | StockFlow</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&amp;display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@100..700,0..1&amp;display=swap"
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
                        "display": ["Inter"]
                    },
                    borderRadius: { "DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px" },
                },
            },
        }
    </script>
</head>

<body class="font-display bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100">
    <!-- BEGIN: MainHeader -->
    @include('landing.partials.header')
    <!-- END: MainHeader -->

    <div class="relative flex min-h-screen flex-col">
        <!-- Header -->
        <header
            class="flex items-center justify-between whitespace-nowrap border-b border-solid border-primary/10 bg-white dark:bg-background-dark px-10 py-3 sticky top-0 z-50">
            <div class="flex items-center gap-4 text-slate-900 dark:text-slate-100">
                <div class="size-6 text-primary">
                    <svg fill="none" viewbox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M24 45.8096C19.6865 45.8096 15.4698 44.5305 11.8832 42.134C8.29667 39.7376 5.50128 36.3314 3.85056 32.3462C2.19985 28.361 1.76794 23.9758 2.60947 19.7452C3.451 15.5145 5.52816 11.6284 8.57829 8.5783C11.6284 5.52817 15.5145 3.45101 19.7452 2.60948C23.9758 1.76795 28.361 2.19986 32.3462 3.85057C36.3314 5.50129 39.7376 8.29668 42.134 11.8833C44.5305 15.4698 45.8096 19.6865 45.8096 24L24 24L24 45.8096Z"
                            fill="currentColor"></path>
                    </svg>
                </div>
                <h2 class="text-lg font-bold leading-tight tracking-[-0.015em]">StockFlow</h2>
            </div>
            <div class="flex flex-1 justify-end gap-8">
                <div class="flex items-center gap-9">
                    <a class="text-sm font-medium leading-normal hover:text-primary transition-colors"
                        href="#">Dashboard</a>
                    <a class="text-sm font-medium leading-normal text-primary" href="#">Inventory</a>
                    <a class="text-sm font-medium leading-normal hover:text-primary transition-colors"
                        href="#">Orders</a>
                    <a class="text-sm font-medium leading-normal hover:text-primary transition-colors"
                        href="#">Reports</a>
                </div>
                <button
                    class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em]">
                    <span class="truncate">Get Started</span>
                </button>
                <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10 border border-primary/20"
                    data-alt="User profile avatar placeholder"
                    style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuA1xxgwK6_XgjLMY0tvKfC4f6viYYEQu_djnR4Vuo5aZRC4PkkymMXOdC0vLqGOqIGFNd81iw6rYR_hm2A4DyPzdKSIWEBvB3NQh7Dxnmvb8LPeIQ2NQLXRXwCJ1V_JyCxzvXVlENkjt7YmEjglVFqzb-TbP8zP5XyhnUIKBB1rllwONpuQUH7WUSmYuLdM8OTpkGdvibqLmd4CQ5jAXeaVikqkj38MTrbkCHnybX7GkCO3VjVKhk39J-JTKaoAYccA00ldoIAI7Tcy");'>
                </div>
            </div>
        </header>
        <main class="flex-1">
            <!-- Hero Section -->
            <section class="max-w-[1200px] mx-auto px-4 py-12 @container">
                <div class="flex flex-col gap-8 @[864px]:flex-row items-center">
                    <div class="flex flex-col gap-6 w-full @[864px]:w-1/2">
                        <div
                            class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/10 text-primary text-xs font-bold uppercase tracking-wider w-fit">
                            <span class="material-symbols-outlined text-sm">bolt</span>
                            Real-Time Sync
                        </div>
                        <h1 class="text-4xl font-black leading-tight tracking-[-0.033em] @[480px]:text-6xl">
                            Live Inventory <span class="text-primary">Management</span>
                        </h1>
                        <p class="text-lg text-slate-600 dark:text-slate-400 leading-relaxed">
                            Stop overbooking and start scaling. Our intelligent tracking system syncs every rental
                            booking with your physical stock instantly.
                        </p>
                        <div class="flex flex-wrap gap-4">
                            <button
                                class="flex min-w-[160px] cursor-pointer items-center justify-center rounded-lg h-12 px-6 bg-primary text-white text-base font-bold transition-all hover:opacity-90">
                                Start Free Trial
                            </button>
                            <button
                                class="flex min-w-[160px] cursor-pointer items-center justify-center rounded-lg h-12 px-6 border border-primary/30 bg-transparent text-primary text-base font-bold hover:bg-primary/5 transition-all">
                                Watch Demo
                            </button>
                        </div>
                    </div>
                    <div class="w-full @[864px]:w-1/2">
                        <div
                            class="aspect-video bg-white dark:bg-slate-800 rounded-xl shadow-2xl overflow-hidden border border-primary/10 relative">
                            <div class="absolute inset-0 bg-gradient-to-br from-primary/5 to-transparent"></div>
                            <img class="w-full h-full object-cover mix-blend-overlay opacity-50"
                                data-alt="Modern warehouse with organized inventory shelves"
                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuC3HdiP4KYmCnN8TlEc3tIOu1WOjw4IGJhcbd0zEn07OgYJjyBx6yZ5o3uUG9E1KJH0fkp157UzEbSXYSmVawC3g33MKF0IVW2StymPSnR_6MpzgwKPIhaCx7GoCU9z3IIIdq0Db1cte-Ex-ya1u2yaUhnRm3hjFc2HI9KsCrQmMQKoontrIT_PpP6cOfK8eBh8cF8CgKzk0KHNDSi-PBPsyXCk25ztYwCD6Vh0Rg25mvuW3xi7Lu5UU3QQNZ6whIJgTl8UMwlHqwxz" />
                            <div class="absolute inset-0 flex items-center justify-center p-8">
                                <div
                                    class="w-full h-full bg-white dark:bg-background-dark rounded-lg shadow-lg p-6 flex flex-col gap-4">
                                    <div class="h-4 w-1/3 bg-primary/20 rounded"></div>
                                    <div class="grid grid-cols-4 gap-4">
                                        <div class="h-20 bg-primary/10 rounded-lg border-2 border-primary/20"></div>
                                        <div class="h-20 bg-primary/10 rounded-lg border-2 border-primary/20"></div>
                                        <div class="h-20 bg-primary/10 rounded-lg border-2 border-primary/20"></div>
                                        <div class="h-20 bg-primary/10 rounded-lg border-2 border-primary/20"></div>
                                    </div>
                                    <div class="h-4 w-full bg-slate-100 dark:bg-slate-800 rounded"></div>
                                    <div class="h-4 w-5/6 bg-slate-100 dark:bg-slate-800 rounded"></div>
                                    <div class="mt-auto h-10 w-full bg-primary rounded"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Feature Grid -->
            <section class="bg-white dark:bg-background-dark py-20 border-y border-primary/10">
                <div class="max-w-[1200px] mx-auto px-4">
                    <div class="text-center max-w-2xl mx-auto mb-16">
                        <h2 class="text-3xl font-bold mb-4">Never Miss a Rental Opportunity</h2>
                        <p class="text-slate-600 dark:text-slate-400">Our system works around the clock to ensure your
                            inventory data is the single source of truth for your business.</p>
                    </div>
                    <div class="grid md:grid-cols-3 gap-8">
                        <div
                            class="flex flex-col p-8 rounded-xl bg-background-light dark:bg-slate-800/50 border border-primary/5 hover:border-primary/20 transition-all">
                            <div
                                class="size-12 rounded-lg bg-primary/10 flex items-center justify-center text-primary mb-6">
                                <span class="material-symbols-outlined text-3xl">sync_alt</span>
                            </div>
                            <h3 class="text-xl font-bold mb-3">Automatic Deductions</h3>
                            <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed">
                                Every time a rental agreement is signed, the system instantly deducts items from
                                available stock. No manual entry, no errors.
                            </p>
                        </div>
                        <div
                            class="flex flex-col p-8 rounded-xl bg-background-light dark:bg-slate-800/50 border border-primary/5 hover:border-primary/20 transition-all">
                            <div
                                class="size-12 rounded-lg bg-primary/10 flex items-center justify-center text-primary mb-6">
                                <span class="material-symbols-outlined text-3xl">history_toggle_off</span>
                            </div>
                            <h3 class="text-xl font-bold mb-3">Late Return Buffer</h3>
                            <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed">
                                Smart algorithms calculate risk for late returns and apply configurable buffers,
                                ensuring you never over-commit during peak seasons.
                            </p>
                        </div>
                        <div
                            class="flex flex-col p-8 rounded-xl bg-background-light dark:bg-slate-800/50 border border-primary/5 hover:border-primary/20 transition-all">
                            <div
                                class="size-12 rounded-lg bg-primary/10 flex items-center justify-center text-primary mb-6">
                                <span class="material-symbols-outlined text-3xl">visibility</span>
                            </div>
                            <h3 class="text-xl font-bold mb-3">Total Visibility</h3>
                            <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed">
                                Track items by status: In Stock, Out on Rental, In Maintenance, or Reserved. One
                                dashboard for everything you own.
                            </p>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Detailed Explanation Section -->
            <section class="py-20 max-w-[1200px] mx-auto px-4">
                <div class="flex flex-col gap-16">
                    <div class="flex flex-col md:flex-row items-center gap-12">
                        <div class="w-full md:w-1/2">
                            <h2 class="text-3xl font-bold mb-6">Intelligent Overbooking Prevention</h2>
                            <p class="text-slate-600 dark:text-slate-400 mb-6 leading-relaxed">
                                Overbooking is the fastest way to lose customer trust. Our system uses "Expected Return
                                Analysis" to flag potential conflicts before they happen.
                            </p>
                            <ul class="flex flex-col gap-4">
                                <li class="flex items-start gap-3">
                                    <span class="material-symbols-outlined text-primary">check_circle</span>
                                    <span>Real-time availability checks during checkout</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="material-symbols-outlined text-primary">check_circle</span>
                                    <span>Automated alerts for low-stock items</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="material-symbols-outlined text-primary">check_circle</span>
                                    <span>Conflict resolution suggestions for staff</span>
                                </li>
                            </ul>
                        </div>
                        <div
                            class="w-full md:w-1/2 bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-primary/10">
                            <div class="flex items-center justify-between mb-6">
                                <span class="font-bold">Booking Timeline</span>
                                <span class="text-xs text-slate-400">Today: June 24, 2024</span>
                            </div>
                            <div class="space-y-4">
                                <div
                                    class="h-12 bg-primary/5 rounded border-l-4 border-primary p-2 flex justify-between items-center">
                                    <span class="text-xs font-medium">Rental #1204 - Active</span>
                                    <span class="text-[10px] bg-primary/10 text-primary px-2 py-0.5 rounded">Ends
                                        Today</span>
                                </div>
                                <div
                                    class="h-12 bg-slate-100 dark:bg-slate-700/50 rounded border-l-4 border-slate-300 p-2 flex justify-between items-center">
                                    <span class="text-xs font-medium">Maintenance Window</span>
                                    <span
                                        class="text-[10px] bg-slate-200 dark:bg-slate-600 px-2 py-0.5 rounded">Scheduled</span>
                                </div>
                                <div
                                    class="h-12 bg-primary/20 rounded border-l-4 border-primary p-2 flex justify-between items-center opacity-50">
                                    <span class="text-xs font-medium">Rental #1210 - Next</span>
                                    <span class="text-[10px] text-primary italic font-bold">Buffer Applied</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Final CTA -->
            <section class="max-w-[1200px] mx-auto px-4 py-20">
                <div class="bg-primary rounded-3xl p-12 text-white text-center relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none">
                        <svg class="w-full h-full" preserveaspectratio="none" viewbox="0 0 100 100">
                            <path d="M0 100 C 20 0 50 0 100 100 Z" fill="currentColor"></path>
                        </svg>
                    </div>
                    <h2 class="text-3xl md:text-5xl font-black mb-6 relative z-10">Ready to automate your stock?</h2>
                    <p class="text-white/80 text-lg mb-10 max-w-xl mx-auto relative z-10">
                        Join over 2,000+ rental businesses using StockFlow to manage their inventory with 100% accuracy.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center relative z-10">
                        <button
                            class="bg-white text-primary px-8 py-4 rounded-xl font-bold hover:bg-slate-50 transition-colors">
                            Get Started for Free
                        </button>
                        <button
                            class="bg-primary/20 border border-white/30 text-white px-8 py-4 rounded-xl font-bold hover:bg-primary/30 transition-colors">
                            Talk to Sales
                        </button>
                    </div>
                </div>
            </section>
        </main>
        <!-- Footer -->
        <footer class="bg-white dark:bg-background-dark border-t border-primary/10 py-12">
            <div class="max-w-[1200px] mx-auto px-4">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-12">
                    <div class="col-span-2 md:col-span-1">
                        <div class="flex items-center gap-2 mb-6 text-slate-900 dark:text-slate-100">
                            <div class="size-6 text-primary">
                                <svg fill="none" viewbox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M24 45.8096C19.6865 45.8096 15.4698 44.5305 11.8832 42.134C8.29667 39.7376 5.50128 36.3314 3.85056 32.3462C2.19985 28.361 1.76794 23.9758 2.60947 19.7452C3.451 15.5145 5.52816 11.6284 8.57829 8.5783C11.6284 5.52817 15.5145 3.45101 19.7452 2.60948C23.9758 1.76795 28.361 2.19986 32.3462 3.85057C36.3314 5.50129 39.7376 8.29668 42.134 11.8833C44.5305 15.4698 45.8096 19.6865 45.8096 24L24 24L24 45.8096Z"
                                        fill="currentColor"></path>
                                </svg>
                            </div>
                            <span class="font-bold text-xl">StockFlow</span>
                        </div>
                        <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                            The intelligent operating system for modern rental businesses.
                        </p>
                    </div>
                    <div>
                        <h4 class="font-bold mb-4">Product</h4>
                        <ul class="space-y-2 text-sm text-slate-500 dark:text-slate-400">
                            <li><a class="hover:text-primary transition-colors" href="#">Features</a></li>
                            <li><a class="hover:text-primary transition-colors" href="#">Pricing</a></li>
                            <li><a class="hover:text-primary transition-colors" href="#">Integrations</a></li>
                            <li><a class="hover:text-primary transition-colors" href="#">API</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-bold mb-4">Support</h4>
                        <ul class="space-y-2 text-sm text-slate-500 dark:text-slate-400">
                            <li><a class="hover:text-primary transition-colors" href="#">Documentation</a></li>
                            <li><a class="hover:text-primary transition-colors" href="#">Help Center</a></li>
                            <li><a class="hover:text-primary transition-colors" href="#">Community</a></li>
                            <li><a class="hover:text-primary transition-colors" href="#">Status</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-bold mb-4">Company</h4>
                        <ul class="space-y-2 text-sm text-slate-500 dark:text-slate-400">
                            <li><a class="hover:text-primary transition-colors" href="#">About Us</a></li>
                            <li><a class="hover:text-primary transition-colors" href="#">Careers</a></li>
                            <li><a class="hover:text-primary transition-colors" href="#">Contact</a></li>
                            <li><a class="hover:text-primary transition-colors" href="#">Privacy</a></li>
                        </ul>
                    </div>
                </div>
                <div
                    class="border-t border-primary/5 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-slate-400">
                    <p>© 2024 StockFlow Inc. All rights reserved.</p>
                    <div class="flex gap-6">
                        <a class="hover:text-primary transition-colors" href="#">Terms of Service</a>
                        <a class="hover:text-primary transition-colors" href="#">Privacy Policy</a>
                        <a class="hover:text-primary transition-colors" href="#">Cookie Settings</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <!-- BEGIN: MainFooter -->
    @include('landing.partials.footer')
    <!-- END: MainFooter -->
</body>

</html>
<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>ProRent SaaS - Reports &amp; Analytics</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&amp;display=swap"
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
                        "display": ["Inter", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 font-display">
    <!-- BEGIN: MainHeader -->
    @include('landing.partials.header')
    <!-- END: MainHeader -->

    <div class="relative flex min-h-screen flex-col">
        <header
            class="flex items-center justify-between whitespace-nowrap border-b border-solid border-primary/10 px-6 md:px-20 py-4 bg-white dark:bg-slate-900">
            <div class="flex items-center gap-4 text-slate-900 dark:text-white">
                <div class="size-8 text-primary">
                    <span class="material-symbols-outlined text-4xl">analytics</span>
                </div>
                <h2 class="text-xl font-bold leading-tight tracking-tight">ProRent SaaS</h2>
            </div>
            <div class="hidden md:flex flex-1 justify-end gap-8 items-center">
                <nav class="flex items-center gap-8">
                    <a class="text-slate-700 dark:text-slate-300 text-sm font-medium hover:text-primary transition-colors"
                        href="#">Dashboard</a>
                    <a class="text-slate-700 dark:text-slate-300 text-sm font-medium hover:text-primary transition-colors"
                        href="#">Properties</a>
                    <a class="text-primary text-sm font-bold border-b-2 border-primary py-1" href="#">Reports</a>
                    <a class="text-slate-700 dark:text-slate-300 text-sm font-medium hover:text-primary transition-colors"
                        href="#">Settings</a>
                </nav>
                <div class="flex gap-3">
                    <button
                        class="flex size-10 cursor-pointer items-center justify-center rounded-lg bg-primary/10 text-primary hover:bg-primary/20 transition-colors">
                        <span class="material-symbols-outlined text-xl">notifications</span>
                    </button>
                    <button
                        class="flex size-10 cursor-pointer items-center justify-center rounded-lg bg-primary/10 text-primary hover:bg-primary/20 transition-colors">
                        <span class="material-symbols-outlined text-xl">account_circle</span>
                    </button>
                </div>
            </div>
            <div class="md:hidden">
                <span class="material-symbols-outlined text-slate-900 dark:text-white">menu</span>
            </div>
        </header>
        <main class="flex-grow">
            <section class="max-w-[1200px] mx-auto px-6 py-12 md:py-20">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <div class="flex flex-col gap-8">
                        <div
                            class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/10 text-primary text-xs font-bold uppercase tracking-wider w-fit">
                            <span class="material-symbols-outlined text-sm">auto_graph</span>
                            Advanced Reporting
                        </div>
                        <h1
                            class="text-4xl md:text-6xl font-black leading-tight tracking-tight text-slate-900 dark:text-white">
                            Comprehensive Rental &amp; Financial Reporting
                        </h1>
                        <p class="text-lg text-slate-600 dark:text-slate-400 max-w-lg">
                            Gain total transparency into your property portfolio with real-time data visualizations and
                            automated financial tracking designed for modern property managers.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <button
                                class="flex h-12 items-center justify-center rounded-lg bg-primary px-8 text-white text-base font-bold shadow-lg shadow-primary/20 hover:brightness-110 transition-all">
                                Get Started Free
                            </button>
                            <button
                                class="flex h-12 items-center justify-center rounded-lg border-2 border-primary/20 bg-transparent px-8 text-primary text-base font-bold hover:bg-primary/5 transition-all">
                                Watch Demo
                            </button>
                        </div>
                    </div>
                    <div class="relative">
                        <div class="w-full aspect-video bg-cover bg-center rounded-xl shadow-2xl border border-primary/10 overflow-hidden"
                            data-alt="Interactive financial dashboard with bar charts and line graphs"
                            style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuD-bA--setTkKYgdq0PydhgtVF3RSWekcNl533qhCGwL69sIrv6sLG2CDayYynxV0yc608Jj1GOnfNMw-N6bhBhRHFnKBCQGZL0GLEEbudZeYZBNz9cPMBpHjvn8BRFcBloraYurgumVMNxL0mdv-TFaPkmUd8gVGyOaIvTldKELQfOBIM5RASbM9wquEea1kdDV80XB7IhoVT_W3nle672SBM_TOdY6FmfY45UYZ_wHgpv2F8VjUo-vt9eMvxG5uK2sW7k83LMMzkf')">
                        </div>
                        <div
                            class="absolute -bottom-6 -left-6 hidden md:block w-48 bg-white dark:bg-slate-800 p-4 rounded-lg shadow-xl border border-primary/10">
                            <p class="text-xs font-bold text-slate-400 uppercase">Growth</p>
                            <p class="text-2xl font-black text-primary">+24.8%</p>
                            <div class="w-full h-1 bg-primary/10 mt-2 rounded-full overflow-hidden">
                                <div class="w-3/4 h-full bg-primary"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="bg-white dark:bg-slate-900/50 py-20">
                <div class="max-w-[1200px] mx-auto px-6">
                    <div class="text-center mb-16">
                        <h2 class="text-3xl font-bold text-slate-900 dark:text-white mb-4">Detailed Operational Insights
                        </h2>
                        <p class="text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">
                            Powerful tools to help you manage your properties more effectively with data-driven decision
                            making.
                        </p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div
                            class="flex flex-col gap-6 p-8 rounded-xl border border-primary/10 bg-background-light dark:bg-slate-800 hover:border-primary/40 transition-all group">
                            <div
                                class="size-12 rounded-lg bg-primary flex items-center justify-center text-white shadow-lg shadow-primary/30 group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-2xl">bar_chart</span>
                            </div>
                            <div class="flex flex-col gap-2">
                                <h3 class="text-xl font-bold text-slate-900 dark:text-white">Rental Performance</h3>
                                <p class="text-slate-600 dark:text-slate-400">Track occupancy rates, lease expirations,
                                    and tenant turnover across your entire portfolio.</p>
                            </div>
                        </div>
                        <div
                            class="flex flex-col gap-6 p-8 rounded-xl border border-primary/10 bg-background-light dark:bg-slate-800 hover:border-primary/40 transition-all group">
                            <div
                                class="size-12 rounded-lg bg-primary flex items-center justify-center text-white shadow-lg shadow-primary/30 group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-2xl">receipt_long</span>
                            </div>
                            <div class="flex flex-col gap-2">
                                <h3 class="text-xl font-bold text-slate-900 dark:text-white">Expense Tracking</h3>
                                <p class="text-slate-600 dark:text-slate-400">Monitor maintenance costs and utility
                                    overheads automatically with granular categorization.</p>
                            </div>
                        </div>
                        <div
                            class="flex flex-col gap-6 p-8 rounded-xl border border-primary/10 bg-background-light dark:bg-slate-800 hover:border-primary/40 transition-all group">
                            <div
                                class="size-12 rounded-lg bg-primary flex items-center justify-center text-white shadow-lg shadow-primary/30 group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-2xl">description</span>
                            </div>
                            <div class="flex flex-col gap-2">
                                <h3 class="text-xl font-bold text-slate-900 dark:text-white">Tax Readiness</h3>
                                <p class="text-slate-600 dark:text-slate-400">Generate P&amp;L statements and Schedule E
                                    reports in one click, ready for your accountant.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="max-w-[1200px] mx-auto px-6 py-20">
                <div class="flex flex-col gap-16">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                        <div class="order-2 lg:order-1">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="p-6 rounded-xl bg-primary/5 border border-primary/10">
                                    <div
                                        class="size-10 rounded-full bg-primary/20 text-primary flex items-center justify-center mb-4">
                                        <span class="material-symbols-outlined text-xl">sync</span>
                                    </div>
                                    <h4 class="font-bold mb-2">Automated Reconciliation</h4>
                                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">Sync with your
                                        bank accounts to match transactions instantly and reduce manual entry errors.
                                    </p>
                                </div>
                                <div class="p-6 rounded-xl bg-primary/5 border border-primary/10">
                                    <div
                                        class="size-10 rounded-full bg-primary/20 text-primary flex items-center justify-center mb-4">
                                        <span class="material-symbols-outlined text-xl">share</span>
                                    </div>
                                    <h4 class="font-bold mb-2">Investor Portals</h4>
                                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">Share
                                        professional financial summaries with stakeholders securely via private access
                                        links.</p>
                                </div>
                                <div class="p-6 rounded-xl bg-primary/5 border border-primary/10">
                                    <div
                                        class="size-10 rounded-full bg-primary/20 text-primary flex items-center justify-center mb-4">
                                        <span class="material-symbols-outlined text-xl">notifications_active</span>
                                    </div>
                                    <h4 class="font-bold mb-2">Custom Alerts</h4>
                                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">Get notified
                                        when expenses exceed budgets or when revenue goals are met.</p>
                                </div>
                                <div class="p-6 rounded-xl bg-primary/5 border border-primary/10">
                                    <div
                                        class="size-10 rounded-full bg-primary/20 text-primary flex items-center justify-center mb-4">
                                        <span class="material-symbols-outlined text-xl">cloud_download</span>
                                    </div>
                                    <h4 class="font-bold mb-2">Multi-Format Export</h4>
                                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">Download
                                        reports in PDF, CSV, or XLSX formats for further external analysis.</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col gap-6 order-1 lg:order-2">
                            <h2 class="text-4xl font-black text-slate-900 dark:text-white leading-tight">Full Financial
                                Transparency</h2>
                            <p class="text-slate-600 dark:text-slate-400 text-lg">
                                Our platform ensures every cent is accounted for, providing owners and managers with
                                audit-ready documents and real-time clarity.
                            </p>
                            <ul class="flex flex-col gap-4 mt-4">
                                <li class="flex items-start gap-3">
                                    <span class="material-symbols-outlined text-primary">check_circle</span>
                                    <span class="text-slate-700 dark:text-slate-300 font-medium">Bank-grade security
                                        protocols for all data</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="material-symbols-outlined text-primary">check_circle</span>
                                    <span class="text-slate-700 dark:text-slate-300 font-medium">Real-time portfolio
                                        valuations</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="material-symbols-outlined text-primary">check_circle</span>
                                    <span class="text-slate-700 dark:text-slate-300 font-medium">Historical data
                                        archiving for compliance</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>
            <section class="max-w-[1200px] mx-auto px-6 py-20">
                <div class="relative bg-background-dark text-white rounded-3xl p-12 overflow-hidden">
                    <div
                        class="absolute top-0 right-0 w-1/3 h-full bg-gradient-to-l from-primary/20 to-transparent pointer-events-none">
                    </div>
                    <div class="relative z-10 flex flex-col items-center text-center max-w-3xl mx-auto gap-8">
                        <h2 class="text-4xl font-black">Ready to master your property finances?</h2>
                        <p class="text-slate-300 text-lg">
                            Join thousands of property owners who have optimized their operations with our advanced
                            reporting tools.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 w-full justify-center">
                            <button
                                class="px-10 py-4 bg-primary text-white font-bold rounded-lg hover:brightness-110 transition-all text-lg">
                                Start Your 14-Day Trial
                            </button>
                            <button
                                class="px-10 py-4 bg-white/10 text-white font-bold rounded-lg hover:bg-white/20 transition-all text-lg">
                                Talk to Sales
                            </button>
                        </div>
                        <p class="text-sm text-slate-500">No credit card required. Cancel anytime.</p>
                    </div>
                </div>
            </section>
        </main>
        <footer class="bg-white dark:bg-slate-900 border-t border-primary/10 px-6 md:px-20 py-12">
            <div class="max-w-[1200px] mx-auto grid grid-cols-1 md:grid-cols-4 gap-12">
                <div class="flex flex-col gap-4">
                    <div class="flex items-center gap-2 text-slate-900 dark:text-white">
                        <span class="material-symbols-outlined text-primary">analytics</span>
                        <h2 class="text-lg font-bold">ProRent SaaS</h2>
                    </div>
                    <p class="text-sm text-slate-500 leading-relaxed">
                        Leading the property management industry with innovative, data-driven solutions for modern
                        landlords and property managers.
                    </p>
                </div>
                <div class="flex flex-col gap-4">
                    <h4 class="font-bold text-slate-900 dark:text-white uppercase text-xs tracking-widest">Platform</h4>
                    <a class="text-sm text-slate-500 hover:text-primary transition-colors" href="#">Dashboard</a>
                    <a class="text-sm text-slate-500 hover:text-primary transition-colors" href="#">Properties</a>
                    <a class="text-sm text-slate-500 hover:text-primary transition-colors" href="#">Maintenance</a>
                    <a class="text-sm text-slate-500 hover:text-primary transition-colors" href="#">Tenants</a>
                </div>
                <div class="flex flex-col gap-4">
                    <h4 class="font-bold text-slate-900 dark:text-white uppercase text-xs tracking-widest">Resources
                    </h4>
                    <a class="text-sm text-slate-500 hover:text-primary transition-colors" href="#">Help Center</a>
                    <a class="text-sm text-slate-500 hover:text-primary transition-colors" href="#">Guides</a>
                    <a class="text-sm text-slate-500 hover:text-primary transition-colors" href="#">API Docs</a>
                    <a class="text-sm text-slate-500 hover:text-primary transition-colors" href="#">Status</a>
                </div>
                <div class="flex flex-col gap-4">
                    <h4 class="font-bold text-slate-900 dark:text-white uppercase text-xs tracking-widest">Company</h4>
                    <a class="text-sm text-slate-500 hover:text-primary transition-colors" href="#">About Us</a>
                    <a class="text-sm text-slate-500 hover:text-primary transition-colors" href="#">Privacy Policy</a>
                    <a class="text-sm text-slate-500 hover:text-primary transition-colors" href="#">Terms of Service</a>
                    <a class="text-sm text-slate-500 hover:text-primary transition-colors" href="#">Contact</a>
                </div>
            </div>
            <div
                class="max-w-[1200px] mx-auto mt-12 pt-8 border-t border-primary/10 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-sm text-slate-500">© 2024 ProRent SaaS Inc. All rights reserved.</p>
                <div class="flex gap-6">
                    <a class="text-slate-400 hover:text-primary" href="#"><span
                            class="material-symbols-outlined">public</span></a>
                    <a class="text-slate-400 hover:text-primary" href="#"><span
                            class="material-symbols-outlined">group</span></a>
                    <a class="text-slate-400 hover:text-primary" href="#"><span
                            class="material-symbols-outlined">alternate_email</span></a>
                </div>
            </div>
        </footer>
    </div>
    <!-- BEGIN: MainFooter -->
    @include('landing.partials.footer')
    <!-- END: MainFooter -->
</body>

</html>
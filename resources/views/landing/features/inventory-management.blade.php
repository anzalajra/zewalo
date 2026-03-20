<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<style>[x-cloak] { display: none !important; }</style>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
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
    <title>{{ __('landing.feat_inventory.page_title') }}</title>
</head>

<body class="bg-background-light dark:bg-background-dark font-display text-slate-900 dark:text-slate-100">
    <!-- BEGIN: MainHeader -->
    @include('landing.partials.header')
    <!-- END: MainHeader -->

    <div class="relative flex min-h-screen w-full flex-col overflow-x-hidden">
        <header
            class="flex items-center justify-between whitespace-nowrap border-b border-solid border-slate-200 dark:border-slate-800 bg-white dark:bg-background-dark px-6 md:px-10 py-3 sticky top-0 z-50">
            <div class="flex items-center gap-8">
                <div class="flex items-center gap-4 text-slate-900 dark:text-slate-100">
                    <div class="size-6 text-primary">
                        <span class="material-symbols-outlined text-3xl">inventory_2</span>
                    </div>
                    <h2 class="text-slate-900 dark:text-slate-100 text-lg font-bold leading-tight tracking-[-0.015em]">
                        {{ __('landing.feat_inventory.brand_name') }}</h2>
                </div>
                <nav class="hidden md:flex items-center gap-9">
                    <a class="text-slate-700 dark:text-slate-300 text-sm font-medium leading-normal hover:text-primary transition-colors"
                        href="#">{{ __('landing.feat_inventory.nav_dashboard') }}</a>
                    <a class="text-primary text-sm font-bold leading-normal" href="#">{{ __('landing.feat_inventory.nav_inventory') }}</a>
                    <a class="text-slate-700 dark:text-slate-300 text-sm font-medium leading-normal hover:text-primary transition-colors"
                        href="#">{{ __('landing.feat_inventory.nav_orders') }}</a>
                    <a class="text-slate-700 dark:text-slate-300 text-sm font-medium leading-normal hover:text-primary transition-colors"
                        href="#">{{ __('landing.feat_inventory.nav_reports') }}</a>
                </nav>
            </div>
            <div class="flex flex-1 justify-end gap-4 md:gap-8 items-center">
                <label class="hidden lg:flex flex-col min-w-40 !h-10 max-w-64">
                    <div class="flex w-full flex-1 items-stretch rounded-lg h-full bg-slate-100 dark:bg-slate-800">
                        <div class="text-slate-500 flex items-center justify-center pl-4">
                            <span class="material-symbols-outlined text-xl">search</span>
                        </div>
                        <input
                            class="form-input flex w-full min-w-0 flex-1 border-none bg-transparent focus:ring-0 h-full placeholder:text-slate-500 px-4 pl-2 text-base font-normal"
                            placeholder="{{ __('landing.feat_inventory.search_placeholder') }}" value="" />
                    </div>
                </label>
                <button
                    class="flex min-w-[84px] cursor-pointer items-center justify-center rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold hover:bg-primary/90 transition-colors">
                    <span class="truncate">{{ __('landing.feat_inventory.get_started') }}</span>
                </button>
                <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10 border border-slate-200 dark:border-slate-700"
                    data-alt="User profile avatar placeholder"
                    style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuAUMxlWz_fN0rVDWU0J1a4yqbjmgdDNE5HdFBNf3wvbUQbtMXZbIitiubcxC-XRGWk35zP2M5e-qmpMr_Ow9AfBGnC3Rl1DcB7yNtyULE0n3AfqawnBd0vt_RU8eJPzJ6MSVYegMBYk4uMWLNmDqiX2D3aM0IgvEbWOgCVCJHzBdLGSozzOlr0NBfl5Rcrk6MQufShNDeuJ0gKbUGm12GAHXNuYnSCNea19MB_mbQDUYlLtLLBF1-DKZFQ60KFFpIadQyCYPziRFQNM");'>
                </div>
            </div>
        </header>
        <main class="flex-1">
            <section class="px-6 md:px-20 lg:px-40 py-16 md:py-24 bg-white dark:bg-background-dark">
                <div class="max-w-[1200px] mx-auto flex flex-col lg:flex-row gap-12 items-center">
                    <div class="flex flex-col gap-6 lg:w-1/2">
                        <div
                            class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/10 text-primary text-xs font-bold uppercase tracking-wider">
                            <span class="material-symbols-outlined text-sm">auto_awesome</span>
                            <span>{{ __('landing.feat_inventory.hero_badge') }}</span>
                        </div>
                        <h1
                            class="text-slate-900 dark:text-slate-100 text-4xl md:text-6xl font-black leading-tight tracking-tight">
                            {{ __('landing.feat_inventory.hero_title') }}
                        </h1>
                        <p class="text-slate-600 dark:text-slate-400 text-lg md:text-xl leading-relaxed">
                            {{ __('landing.feat_inventory.hero_description') }}
                        </p>
                        <div class="flex flex-wrap gap-4 mt-4">
                            <button
                                class="px-8 py-4 bg-primary text-white font-bold rounded-lg hover:bg-primary/90 transition-all shadow-lg shadow-primary/20">
                                {{ __('landing.feat_inventory.start_trial') }}
                            </button>
                            <button
                                class="px-8 py-4 bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-slate-100 font-bold rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700 transition-all">
                                {{ __('landing.feat_inventory.watch_demo') }}
                            </button>
                        </div>
                    </div>
                    <div class="lg:w-1/2 w-full">
                        <div
                            class="relative rounded-2xl overflow-hidden shadow-2xl border border-slate-200 dark:border-slate-800 aspect-video bg-slate-100 dark:bg-slate-900 flex items-center justify-center">
                            <div class="absolute inset-0 bg-gradient-to-tr from-primary/20 to-transparent"></div>
                            <img class="object-cover w-full h-full opacity-90"
                                data-alt="Modern logistics warehouse with organized shelves and digital displays"
                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuD4gUwpoSGl82OQyAAv_cQRw1_dXmfeNyxaSYT6gq-bQkyF_Fp-RDiFchYNlUXYQn_QjVaP4zf0DwOpx1GoX4a2MwXqnHfK7obOZTVInqgl46Bl1Zs4iCIjS5cq_z15b4tGWbuojRH5jm2wQy3qkX1z0Ban4RM7i-cTT_hj-dJ7vcQVSFLZ2j4hDGQceoT5AGiBrVgsa-DB0Z8IVn9RDLsQPtxabLA8imMNTuURAVUvZ-WxX18jIHDHUPTWKljv63T-5LIHn3fmu9du" />
                        </div>
                    </div>
                </div>
            </section>
            <section class="px-6 md:px-20 lg:px-40 py-20 bg-background-light dark:bg-background-dark/50">
                <div class="max-w-[1200px] mx-auto">
                    <div class="text-center mb-16">
                        <h2 class="text-3xl md:text-4xl font-bold text-slate-900 dark:text-slate-100 mb-4">{{ __('landing.feat_inventory.features_title') }}</h2>
                        <p class="text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">{{ __('landing.feat_inventory.features_subtitle') }}</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <div
                            class="bg-white dark:bg-slate-900 p-8 rounded-xl border border-slate-200 dark:border-slate-800 hover:border-primary/50 transition-all group">
                            <div
                                class="w-12 h-12 rounded-lg bg-primary/10 text-primary flex items-center justify-center mb-6 group-hover:bg-primary group-hover:text-white transition-colors">
                                <span class="material-symbols-outlined">layers</span>
                            </div>
                            <h3 class="text-xl font-bold mb-3 text-slate-900 dark:text-slate-100">{{ __('landing.feat_inventory.feature1_title') }}
                            </h3>
                            <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                                {{ __('landing.feat_inventory.feature1_desc') }}
                            </p>
                        </div>
                        <div
                            class="bg-white dark:bg-slate-900 p-8 rounded-xl border border-slate-200 dark:border-slate-800 hover:border-primary/50 transition-all group">
                            <div
                                class="w-12 h-12 rounded-lg bg-primary/10 text-primary flex items-center justify-center mb-6 group-hover:bg-primary group-hover:text-white transition-colors">
                                <span class="material-symbols-outlined">inventory</span>
                            </div>
                            <h3 class="text-xl font-bold mb-3 text-slate-900 dark:text-slate-100">{{ __('landing.feat_inventory.feature2_title') }}</h3>
                            <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                                {{ __('landing.feat_inventory.feature2_desc') }}
                            </p>
                        </div>
                        <div
                            class="bg-white dark:bg-slate-900 p-8 rounded-xl border border-slate-200 dark:border-slate-800 hover:border-primary/50 transition-all group">
                            <div
                                class="w-12 h-12 rounded-lg bg-primary/10 text-primary flex items-center justify-center mb-6 group-hover:bg-primary group-hover:text-white transition-colors">
                                <span class="material-symbols-outlined">square_foot</span>
                            </div>
                            <h3 class="text-xl font-bold mb-3 text-slate-900 dark:text-slate-100">{{ __('landing.feat_inventory.feature3_title') }}
                            </h3>
                            <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                                {{ __('landing.feat_inventory.feature3_desc') }}
                            </p>
                        </div>
                        <div
                            class="bg-white dark:bg-slate-900 p-8 rounded-xl border border-slate-200 dark:border-slate-800 hover:border-primary/50 transition-all group">
                            <div
                                class="w-12 h-12 rounded-lg bg-primary/10 text-primary flex items-center justify-center mb-6 group-hover:bg-primary group-hover:text-white transition-colors">
                                <span class="material-symbols-outlined">calendar_today</span>
                            </div>
                            <h3 class="text-xl font-bold mb-3 text-slate-900 dark:text-slate-100">{{ __('landing.feat_inventory.feature4_title') }}
                            </h3>
                            <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                                {{ __('landing.feat_inventory.feature4_desc') }}
                            </p>
                        </div>
                        <div
                            class="bg-white dark:bg-slate-900 p-8 rounded-xl border border-slate-200 dark:border-slate-800 hover:border-primary/50 transition-all group">
                            <div
                                class="w-12 h-12 rounded-lg bg-primary/10 text-primary flex items-center justify-center mb-6 group-hover:bg-primary group-hover:text-white transition-colors">
                                <span class="material-symbols-outlined">domain</span>
                            </div>
                            <h3 class="text-xl font-bold mb-3 text-slate-900 dark:text-slate-100">{{ __('landing.feat_inventory.feature5_title') }}</h3>
                            <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                                {{ __('landing.feat_inventory.feature5_desc') }}
                            </p>
                        </div>
                        <div
                            class="bg-white dark:bg-slate-900 p-8 rounded-xl border border-slate-200 dark:border-slate-800 hover:border-primary/50 transition-all group">
                            <div
                                class="w-12 h-12 rounded-lg bg-primary/10 text-primary flex items-center justify-center mb-6 group-hover:bg-primary group-hover:text-white transition-colors">
                                <span class="material-symbols-outlined">monitoring</span>
                            </div>
                            <h3 class="text-xl font-bold mb-3 text-slate-900 dark:text-slate-100">{{ __('landing.feat_inventory.feature6_title') }}
                            </h3>
                            <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                                {{ __('landing.feat_inventory.feature6_desc') }}
                            </p>
                        </div>
                    </div>
                </div>
            </section>
            <section class="px-6 md:px-20 lg:px-40 py-20 bg-white dark:bg-background-dark">
                <div class="max-w-[1200px] mx-auto flex flex-col md:flex-row gap-16 items-center">
                    <div class="w-full md:w-1/2">
                        <div class="rounded-2xl bg-slate-900 p-6 shadow-xl text-white">
                            <div class="flex items-center justify-between mb-8">
                                <h4 class="font-bold">{{ __('landing.feat_inventory.valuation_title') }}</h4>
                                <span class="text-primary text-sm font-mono">{{ __('landing.feat_inventory.valuation_live') }}</span>
                            </div>
                            <div class="space-y-4">
                                <div class="h-4 bg-slate-800 rounded-full overflow-hidden">
                                    <div class="h-full bg-primary w-3/4"></div>
                                </div>
                                <div class="flex justify-between text-sm text-slate-400">
                                    <span>{{ __('landing.feat_inventory.valuation_main_warehouse') }}</span>
                                    <span>$452,000.00</span>
                                </div>
                                <div class="h-4 bg-slate-800 rounded-full overflow-hidden">
                                    <div class="h-full bg-primary w-1/4"></div>
                                </div>
                                <div class="flex justify-between text-sm text-slate-400">
                                    <span>{{ __('landing.feat_inventory.valuation_satellite') }}</span>
                                    <span>$128,500.00</span>
                                </div>
                                <div class="pt-6 border-t border-slate-800">
                                    <div class="flex justify-between items-end">
                                        <div>
                                            <p class="text-xs text-slate-500 uppercase">{{ __('landing.feat_inventory.valuation_depreciation') }}</p>
                                            <p class="text-2xl font-bold text-red-400">-$12,403.20</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-xs text-slate-500 uppercase">{{ __('landing.feat_inventory.valuation_total') }}</p>
                                            <p class="text-3xl font-black text-primary">$568,096.80</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-full md:w-1/2 flex flex-col gap-6">
                        <h2 class="text-3xl md:text-4xl font-bold">{{ __('landing.feat_inventory.detail_title') }}</h2>
                        <p class="text-slate-600 dark:text-slate-400 text-lg">
                            {{ __('landing.feat_inventory.detail_desc') }}
                        </p>
                        <ul class="space-y-4">
                            <li class="flex gap-3 items-start">
                                <span class="material-symbols-outlined text-primary">check_circle</span>
                                <span>{{ __('landing.feat_inventory.detail_check1') }}</span>
                            </li>
                            <li class="flex gap-3 items-start">
                                <span class="material-symbols-outlined text-primary">check_circle</span>
                                <span>{{ __('landing.feat_inventory.detail_check2') }}</span>
                            </li>
                            <li class="flex gap-3 items-start">
                                <span class="material-symbols-outlined text-primary">check_circle</span>
                                <span>{{ __('landing.feat_inventory.detail_check3') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </section>
            <section class="px-6 md:px-20 lg:px-40 py-24 bg-primary text-white">
                <div class="max-w-[1200px] mx-auto text-center flex flex-col items-center gap-8">
                    <h2 class="text-4xl md:text-5xl font-black tracking-tight max-w-3xl">{{ __('landing.feat_inventory.cta_title') }}</h2>
                    <p class="text-white/80 text-lg md:text-xl max-w-2xl">
                        {{ __('landing.feat_inventory.cta_description') }}
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 mt-4 w-full justify-center">
                        <button
                            class="bg-white text-primary px-10 py-4 rounded-lg font-bold text-lg hover:shadow-xl transition-all">
                            {{ __('landing.feat_inventory.cta_get_started') }}
                        </button>
                        <button
                            class="bg-primary-dark/20 border border-white/30 backdrop-blur-sm px-10 py-4 rounded-lg font-bold text-lg hover:bg-white/10 transition-all">
                            {{ __('landing.feat_inventory.cta_contact_sales') }}
                        </button>
                    </div>
                    <p class="text-sm text-white/60">{{ __('landing.feat_inventory.cta_no_credit_card') }}</p>
                </div>
            </section>
        </main>
        <footer
            class="bg-white dark:bg-background-dark border-t border-slate-200 dark:border-slate-800 px-6 md:px-10 py-12">
            <div class="max-w-[1200px] mx-auto grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-12">
                <div class="col-span-2">
                    <div class="flex items-center gap-4 text-slate-900 dark:text-slate-100 mb-6">
                        <span class="material-symbols-outlined text-primary text-3xl">inventory_2</span>
                        <h2 class="text-lg font-bold">{{ __('landing.feat_inventory.brand_name') }}</h2>
                    </div>
                    <p class="text-slate-600 dark:text-slate-400 mb-6 max-w-xs">
                        {{ __('landing.feat_inventory.footer_tagline') }}
                    </p>
                    <div class="flex gap-4">
                        <a class="text-slate-400 hover:text-primary transition-colors" href="#"><span
                                class="material-symbols-outlined">public</span></a>
                        <a class="text-slate-400 hover:text-primary transition-colors" href="#"><span
                                class="material-symbols-outlined">mail</span></a>
                        <a class="text-slate-400 hover:text-primary transition-colors" href="#"><span
                                class="material-symbols-outlined">share</span></a>
                    </div>
                </div>
                <div>
                    <h4 class="font-bold mb-6">{{ __('landing.feat_inventory.footer_product') }}</h4>
                    <ul class="space-y-4 text-slate-600 dark:text-slate-400 text-sm">
                        <li><a class="hover:text-primary transition-colors" href="#">{{ __('landing.feat_inventory.nav_features') }}</a></li>
                        <li><a class="hover:text-primary transition-colors" href="#">{{ __('landing.feat_inventory.footer_pricing') }}</a></li>
                        <li><a class="hover:text-primary transition-colors" href="#">{{ __('landing.feat_inventory.footer_integrations') }}</a></li>
                        <li><a class="hover:text-primary transition-colors" href="#">{{ __('landing.feat_inventory.footer_changelog') }}</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-6">{{ __('landing.feat_inventory.footer_company') }}</h4>
                    <ul class="space-y-4 text-slate-600 dark:text-slate-400 text-sm">
                        <li><a class="hover:text-primary transition-colors" href="#">{{ __('landing.feat_inventory.footer_about') }}</a></li>
                        <li><a class="hover:text-primary transition-colors" href="#">{{ __('landing.feat_inventory.footer_customers') }}</a></li>
                        <li><a class="hover:text-primary transition-colors" href="#">{{ __('landing.feat_inventory.footer_careers') }}</a></li>
                        <li><a class="hover:text-primary transition-colors" href="#">{{ __('landing.feat_inventory.footer_contact') }}</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-6">{{ __('landing.feat_inventory.footer_legal') }}</h4>
                    <ul class="space-y-4 text-slate-600 dark:text-slate-400 text-sm">
                        <li><a class="hover:text-primary transition-colors" href="#">{{ __('landing.feat_inventory.footer_privacy') }}</a></li>
                        <li><a class="hover:text-primary transition-colors" href="#">{{ __('landing.feat_inventory.footer_terms') }}</a></li>
                        <li><a class="hover:text-primary transition-colors" href="#">{{ __('landing.feat_inventory.footer_security') }}</a></li>
                    </ul>
                </div>
            </div>
            <div
                class="max-w-[1200px] mx-auto mt-12 pt-8 border-t border-slate-100 dark:border-slate-800 text-center text-slate-500 text-xs">
                {{ __('landing.feat_inventory.footer_copyright') }}
            </div>
        </footer>
    </div>
    <!-- BEGIN: MainFooter -->
    @include('landing.partials.footer')
    <!-- END: MainFooter -->
</body>

</html>
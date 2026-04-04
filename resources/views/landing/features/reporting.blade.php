<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>{{ __('landing.feat_reporting.page_title') }}</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<style>[x-cloak] { display: none !important; }</style>
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
        <main class="flex-grow">
            <section class="max-w-[1200px] mx-auto px-6 py-12 md:py-20">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <div class="flex flex-col gap-8">
                        <div
                            class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/10 text-primary text-xs font-bold uppercase tracking-wider w-fit">
                            <span class="material-symbols-outlined text-sm">auto_graph</span>
                            {{ __('landing.feat_reporting.hero_badge') }}
                        </div>
                        <h1
                            class="text-4xl md:text-6xl font-black leading-tight tracking-tight text-slate-900 dark:text-white">
                            {{ __('landing.feat_reporting.hero_title') }}
                        </h1>
                        <p class="text-lg text-slate-600 dark:text-slate-400 max-w-lg">
                            {{ __('landing.feat_reporting.hero_description') }}
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <button
                                class="flex h-12 items-center justify-center rounded-lg bg-primary px-8 text-white text-base font-bold shadow-lg shadow-primary/20 hover:brightness-110 transition-all">
                                {{ __('landing.feat_reporting.get_started_free') }}
                            </button>
                            <button
                                class="flex h-12 items-center justify-center rounded-lg border-2 border-primary/20 bg-transparent px-8 text-primary text-base font-bold hover:bg-primary/5 transition-all">
                                {{ __('landing.feat_reporting.watch_demo') }}
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
                            <p class="text-xs font-bold text-slate-400 uppercase">{{ __('landing.feat_reporting.growth_label') }}</p>
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
                        <h2 class="text-3xl font-bold text-slate-900 dark:text-white mb-4">{{ __('landing.feat_reporting.features_title') }}
                        </h2>
                        <p class="text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">
                            {{ __('landing.feat_reporting.features_subtitle') }}
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
                                <h3 class="text-xl font-bold text-slate-900 dark:text-white">{{ __('landing.feat_reporting.feature1_title') }}</h3>
                                <p class="text-slate-600 dark:text-slate-400">{{ __('landing.feat_reporting.feature1_desc') }}</p>
                            </div>
                        </div>
                        <div
                            class="flex flex-col gap-6 p-8 rounded-xl border border-primary/10 bg-background-light dark:bg-slate-800 hover:border-primary/40 transition-all group">
                            <div
                                class="size-12 rounded-lg bg-primary flex items-center justify-center text-white shadow-lg shadow-primary/30 group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-2xl">receipt_long</span>
                            </div>
                            <div class="flex flex-col gap-2">
                                <h3 class="text-xl font-bold text-slate-900 dark:text-white">{{ __('landing.feat_reporting.feature2_title') }}</h3>
                                <p class="text-slate-600 dark:text-slate-400">{{ __('landing.feat_reporting.feature2_desc') }}</p>
                            </div>
                        </div>
                        <div
                            class="flex flex-col gap-6 p-8 rounded-xl border border-primary/10 bg-background-light dark:bg-slate-800 hover:border-primary/40 transition-all group">
                            <div
                                class="size-12 rounded-lg bg-primary flex items-center justify-center text-white shadow-lg shadow-primary/30 group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-2xl">description</span>
                            </div>
                            <div class="flex flex-col gap-2">
                                <h3 class="text-xl font-bold text-slate-900 dark:text-white">{{ __('landing.feat_reporting.feature3_title') }}</h3>
                                <p class="text-slate-600 dark:text-slate-400">{{ __('landing.feat_reporting.feature3_desc') }}</p>
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
                                    <h4 class="font-bold mb-2">{{ __('landing.feat_reporting.subfeature1_title') }}</h4>
                                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">{{ __('landing.feat_reporting.subfeature1_desc') }}
                                    </p>
                                </div>
                                <div class="p-6 rounded-xl bg-primary/5 border border-primary/10">
                                    <div
                                        class="size-10 rounded-full bg-primary/20 text-primary flex items-center justify-center mb-4">
                                        <span class="material-symbols-outlined text-xl">share</span>
                                    </div>
                                    <h4 class="font-bold mb-2">{{ __('landing.feat_reporting.subfeature2_title') }}</h4>
                                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">{{ __('landing.feat_reporting.subfeature2_desc') }}</p>
                                </div>
                                <div class="p-6 rounded-xl bg-primary/5 border border-primary/10">
                                    <div
                                        class="size-10 rounded-full bg-primary/20 text-primary flex items-center justify-center mb-4">
                                        <span class="material-symbols-outlined text-xl">notifications_active</span>
                                    </div>
                                    <h4 class="font-bold mb-2">{{ __('landing.feat_reporting.subfeature3_title') }}</h4>
                                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">{{ __('landing.feat_reporting.subfeature3_desc') }}</p>
                                </div>
                                <div class="p-6 rounded-xl bg-primary/5 border border-primary/10">
                                    <div
                                        class="size-10 rounded-full bg-primary/20 text-primary flex items-center justify-center mb-4">
                                        <span class="material-symbols-outlined text-xl">cloud_download</span>
                                    </div>
                                    <h4 class="font-bold mb-2">{{ __('landing.feat_reporting.subfeature4_title') }}</h4>
                                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">{{ __('landing.feat_reporting.subfeature4_desc') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col gap-6 order-1 lg:order-2">
                            <h2 class="text-4xl font-black text-slate-900 dark:text-white leading-tight">{{ __('landing.feat_reporting.detail_title') }}</h2>
                            <p class="text-slate-600 dark:text-slate-400 text-lg">
                                {{ __('landing.feat_reporting.detail_desc') }}
                            </p>
                            <ul class="flex flex-col gap-4 mt-4">
                                <li class="flex items-start gap-3">
                                    <span class="material-symbols-outlined text-primary">check_circle</span>
                                    <span class="text-slate-700 dark:text-slate-300 font-medium">{{ __('landing.feat_reporting.detail_check1') }}</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="material-symbols-outlined text-primary">check_circle</span>
                                    <span class="text-slate-700 dark:text-slate-300 font-medium">{{ __('landing.feat_reporting.detail_check2') }}</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="material-symbols-outlined text-primary">check_circle</span>
                                    <span class="text-slate-700 dark:text-slate-300 font-medium">{{ __('landing.feat_reporting.detail_check3') }}</span>
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
                        <h2 class="text-4xl font-black">{{ __('landing.feat_reporting.cta_title') }}</h2>
                        <p class="text-slate-300 text-lg">
                            {{ __('landing.feat_reporting.cta_description') }}
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 w-full justify-center">
                            <button
                                class="px-10 py-4 bg-primary text-white font-bold rounded-lg hover:brightness-110 transition-all text-lg">
                                {{ __('landing.feat_reporting.cta_trial_btn') }}
                            </button>
                            <button
                                class="px-10 py-4 bg-white/10 text-white font-bold rounded-lg hover:bg-white/20 transition-all text-lg">
                                {{ __('landing.feat_reporting.cta_talk_sales') }}
                            </button>
                        </div>
                        <p class="text-sm text-slate-500">{{ __('landing.feat_reporting.cta_no_credit_card') }}</p>
                    </div>
                </div>
            </section>
        </main>
        <footer class="bg-white dark:bg-slate-900 border-t border-primary/10 px-6 md:px-20 py-12">
            <div class="max-w-[1200px] mx-auto grid grid-cols-1 md:grid-cols-4 gap-12">
                <div class="flex flex-col gap-4">
                    <div class="flex items-center gap-2 text-slate-900 dark:text-white">
                        <span class="material-symbols-outlined text-primary">analytics</span>
                        <h2 class="text-lg font-bold">{{ __('landing.feat_reporting.brand_name') }}</h2>
                    </div>
                    <p class="text-sm text-slate-500 leading-relaxed">
                        {{ __('landing.feat_reporting.footer_tagline') }}
                    </p>
                </div>
                <div class="flex flex-col gap-4">
                    <h4 class="font-bold text-slate-900 dark:text-white uppercase text-xs tracking-widest">{{ __('landing.feat_reporting.footer_platform') }}</h4>
                    <a class="text-sm text-slate-500 hover:text-primary transition-colors" href="#">{{ __('landing.feat_reporting.nav_dashboard') }}</a>
                    <a class="text-sm text-slate-500 hover:text-primary transition-colors" href="#">{{ __('landing.feat_reporting.nav_properties') }}</a>
                    <a class="text-sm text-slate-500 hover:text-primary transition-colors" href="#">{{ __('landing.feat_reporting.footer_maintenance') }}</a>
                    <a class="text-sm text-slate-500 hover:text-primary transition-colors" href="#">{{ __('landing.feat_reporting.footer_tenants') }}</a>
                </div>
                <div class="flex flex-col gap-4">
                    <h4 class="font-bold text-slate-900 dark:text-white uppercase text-xs tracking-widest">{{ __('landing.feat_reporting.footer_resources') }}
                    </h4>
                    <a class="text-sm text-slate-500 hover:text-primary transition-colors" href="#">{{ __('landing.feat_reporting.footer_help_center') }}</a>
                    <a class="text-sm text-slate-500 hover:text-primary transition-colors" href="#">{{ __('landing.feat_reporting.footer_guides') }}</a>
                    <a class="text-sm text-slate-500 hover:text-primary transition-colors" href="#">{{ __('landing.feat_reporting.footer_api_docs') }}</a>
                    <a class="text-sm text-slate-500 hover:text-primary transition-colors" href="#">{{ __('landing.feat_reporting.footer_status') }}</a>
                </div>
                <div class="flex flex-col gap-4">
                    <h4 class="font-bold text-slate-900 dark:text-white uppercase text-xs tracking-widest">{{ __('landing.feat_reporting.footer_company') }}</h4>
                    <a class="text-sm text-slate-500 hover:text-primary transition-colors" href="#">{{ __('landing.feat_reporting.footer_about_us') }}</a>
                    <a class="text-sm text-slate-500 hover:text-primary transition-colors" href="#">{{ __('landing.feat_reporting.footer_privacy_policy') }}</a>
                    <a class="text-sm text-slate-500 hover:text-primary transition-colors" href="#">{{ __('landing.feat_reporting.footer_terms_of_service') }}</a>
                    <a class="text-sm text-slate-500 hover:text-primary transition-colors" href="#">{{ __('landing.feat_reporting.footer_contact') }}</a>
                </div>
            </div>
            <div
                class="max-w-[1200px] mx-auto mt-12 pt-8 border-t border-primary/10 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-sm text-slate-500">{{ __('landing.feat_reporting.footer_copyright') }}</p>
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
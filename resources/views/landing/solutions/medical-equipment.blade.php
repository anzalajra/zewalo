<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>{{ __('landing.sol_medical.page_title') }}</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<style>[x-cloak] { display: none !important; }</style>
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

<body class="font-display bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100">
    <!-- BEGIN: MainHeader -->
    @include('landing.partials.header')
    <!-- END: MainHeader -->

    <div class="relative flex h-auto min-h-screen w-full flex-col group/design-root overflow-x-hidden">
        <div class="layout-container flex h-full grow flex-col">
            <div class="px-6 lg:px-40 flex flex-1 justify-center py-12 lg:py-20">
                <div class="layout-content-container flex flex-col max-w-[960px] flex-1">
                    <div class="flex flex-wrap justify-between gap-6 p-4">
                        <div class="flex min-w-72 flex-col gap-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-primary/10 text-primary text-sm font-bold w-fit">
                                {{ __('landing.sol_medical.badge') }}
                            </span>
                            <h1 class="text-slate-900 dark:text-slate-50 text-4xl lg:text-5xl font-black leading-tight tracking-tight">
                                {{ __('landing.sol_medical.hero_title') }}
                            </h1>
                            <p class="text-slate-600 dark:text-slate-400 text-lg font-normal leading-normal max-w-2xl">
                                {{ __('landing.sol_medical.hero_description') }}
                            </p>
                            <div class="flex gap-4 pt-4">
                                <a href="/register-tenant" class="bg-primary hover:bg-primary/90 text-white px-8 py-3 rounded-lg font-bold transition-colors">{{ __('landing.sol_medical.hero_cta_trial') }}</a>
                                <a href="/contact" class="border border-primary text-primary hover:bg-primary/5 px-8 py-3 rounded-lg font-bold transition-colors">{{ __('landing.sol_medical.hero_cta_demo') }}</a>
                            </div>
                        </div>
                    </div>
                    <!-- Statistics Dashboard -->
                    <div class="flex flex-wrap gap-4 p-4 mt-8">
                        <div class="flex min-w-[158px] flex-1 flex-col gap-2 rounded-xl p-6 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/50 shadow-sm">
                            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium uppercase tracking-wider">{{ __('landing.sol_medical.stat1_label') }}</p>
                            <p class="text-slate-900 dark:text-slate-50 tracking-tight text-3xl font-bold leading-tight">{{ __('landing.sol_medical.stat1_value') }}</p>
                            <p class="text-emerald-600 dark:text-emerald-400 text-sm font-bold flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">trending_up</span> {{ __('landing.sol_medical.stat1_note') }}
                            </p>
                        </div>
                        <div class="flex min-w-[158px] flex-1 flex-col gap-2 rounded-xl p-6 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/50 shadow-sm">
                            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium uppercase tracking-wider">{{ __('landing.sol_medical.stat2_label') }}</p>
                            <p class="text-slate-900 dark:text-slate-50 tracking-tight text-3xl font-bold leading-tight">{{ __('landing.sol_medical.stat2_value') }}</p>
                            <p class="text-amber-600 dark:text-amber-400 text-sm font-bold flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">warning</span> {{ __('landing.sol_medical.stat2_note') }}
                            </p>
                        </div>
                        <div class="flex min-w-[158px] flex-1 flex-col gap-2 rounded-xl p-6 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/50 shadow-sm">
                            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium uppercase tracking-wider">{{ __('landing.sol_medical.stat3_label') }}</p>
                            <p class="text-slate-900 dark:text-slate-50 tracking-tight text-3xl font-bold leading-tight">{{ __('landing.sol_medical.stat3_value') }}</p>
                            <p class="text-rose-600 dark:text-rose-400 text-sm font-bold flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">schedule</span> {{ __('landing.sol_medical.stat3_note') }}
                            </p>
                        </div>
                    </div>
                    <!-- Feature Focus: Automated Reminders -->
                    <div class="mt-16">
                        <h2 class="text-slate-900 dark:text-slate-50 text-[28px] font-bold leading-tight tracking-tight px-4 pb-6">
                            {{ __('landing.sol_medical.reminders_title') }}</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-4">
                            <div class="flex flex-1 gap-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/50 p-6 flex-col hover:border-primary transition-colors">
                                <div class="text-primary bg-primary/10 w-12 h-12 rounded-lg flex items-center justify-center">
                                    <span class="material-symbols-outlined">build</span>
                                </div>
                                <div class="flex flex-col gap-2">
                                    <h3 class="text-slate-900 dark:text-slate-50 text-lg font-bold">{{ __('landing.sol_medical.reminder1_title') }}</h3>
                                    <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed">{{ __('landing.sol_medical.reminder1_description') }}</p>
                                </div>
                            </div>
                            <div class="flex flex-1 gap-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/50 p-6 flex-col hover:border-primary transition-colors">
                                <div class="text-primary bg-primary/10 w-12 h-12 rounded-lg flex items-center justify-center">
                                    <span class="material-symbols-outlined">badge</span>
                                </div>
                                <div class="flex flex-col gap-2">
                                    <h3 class="text-slate-900 dark:text-slate-50 text-lg font-bold">{{ __('landing.sol_medical.reminder2_title') }}</h3>
                                    <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed">{{ __('landing.sol_medical.reminder2_description') }}</p>
                                </div>
                            </div>
                            <div class="flex flex-1 gap-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/50 p-6 flex-col hover:border-primary transition-colors">
                                <div class="text-primary bg-primary/10 w-12 h-12 rounded-lg flex items-center justify-center">
                                    <span class="material-symbols-outlined">verified_user</span>
                                </div>
                                <div class="flex flex-col gap-2">
                                    <h3 class="text-slate-900 dark:text-slate-50 text-lg font-bold">{{ __('landing.sol_medical.reminder3_title') }}</h3>
                                    <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed">{{ __('landing.sol_medical.reminder3_description') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Storefront Integration -->
                    <div class="mt-20 p-4">
                        <div class="flex flex-col lg:flex-row items-center gap-12 bg-slate-100 dark:bg-slate-800/50 rounded-2xl p-8 lg:p-12 overflow-hidden border border-slate-200 dark:border-slate-800">
                            <div class="flex-1 space-y-6">
                                <h2 class="text-slate-900 dark:text-slate-50 text-3xl font-bold leading-tight">{{ __('landing.sol_medical.storefront_title') }}</h2>
                                <p class="text-slate-600 dark:text-slate-400 text-lg">
                                    {{ __('landing.sol_medical.storefront_description') }}
                                </p>
                                <ul class="space-y-3">
                                    <li class="flex items-center gap-3 text-slate-700 dark:text-slate-300">
                                        <span class="material-symbols-outlined text-primary">check_circle</span>
                                        {{ __('landing.sol_medical.storefront_item1') }}
                                    </li>
                                    <li class="flex items-center gap-3 text-slate-700 dark:text-slate-300">
                                        <span class="material-symbols-outlined text-primary">check_circle</span> {{ __('landing.sol_medical.storefront_item2') }}
                                    </li>
                                    <li class="flex items-center gap-3 text-slate-700 dark:text-slate-300">
                                        <span class="material-symbols-outlined text-primary">check_circle</span>
                                        {{ __('landing.sol_medical.storefront_item3') }}
                                    </li>
                                </ul>
                            </div>
                            <div class="flex-1 w-full max-w-sm">
                                <div class="bg-white dark:bg-slate-900 rounded-xl shadow-xl p-4 border border-slate-200 dark:border-slate-700 transform rotate-2">
                                    <div class="aspect-video bg-primary/5 rounded-lg mb-4 flex items-center justify-center">
                                        <img alt="{{ __('landing.sol_medical.storefront_image_alt') }}" class="rounded-lg object-cover h-full w-full"
                                            data-alt="{{ __('landing.sol_medical.storefront_image_alt') }}"
                                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuCrK0Ei9SccKLKEgt8_x66JcSpUfVoMD8hd8-pEF_sm7SPxsK1tzt5l_bDXaI7HeBda63e3dYdVtKbl3N-fWp8EjjrLi9MJX2aJamLbwmE3dMX2TDf87iXnDj7PkmAlrYVBoGPf_MGQINovHer35OY8UP5lY37j3VKoCIrVQxFYaiue3ACpnt_7yQZB5CWtWoNoFAh2jjV_7-q9_9rgZ1moZqVfz5O1FZUMCHIcAf92xd4G69vgHYNyK2gRKUo4ptZs7UdbrDfMw49C" />
                                    </div>
                                    <div class="space-y-2">
                                        <div class="h-4 w-3/4 bg-slate-200 dark:bg-slate-700 rounded"></div>
                                        <div class="h-4 w-1/2 bg-slate-100 dark:bg-slate-800 rounded"></div>
                                        <div class="mt-4 p-2 bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 text-xs font-bold rounded text-center">
                                            {{ __('landing.sol_medical.storefront_maintenance_badge') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Review -->
                    <div class="mt-24 px-4">
                        <div class="max-w-3xl mx-auto text-center">
                            <div class="flex justify-center mb-6">
                                <div class="flex text-amber-400">
                                    <span class="material-symbols-outlined">star</span>
                                    <span class="material-symbols-outlined">star</span>
                                    <span class="material-symbols-outlined">star</span>
                                    <span class="material-symbols-outlined">star</span>
                                    <span class="material-symbols-outlined">star</span>
                                </div>
                            </div>
                            <blockquote class="text-2xl lg:text-3xl font-medium italic text-slate-800 dark:text-slate-100 mb-8 leading-relaxed">
                                "{{ __('landing.sol_medical.testimonial_quote') }}"
                            </blockquote>
                            <div class="flex items-center justify-center gap-4">
                                <img alt="{{ __('landing.sol_medical.testimonial_image_alt') }}" class="w-16 h-16 rounded-full border-2 border-primary"
                                    data-alt="{{ __('landing.sol_medical.testimonial_image_alt') }}"
                                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuAFYhjUwkAA1X1Yju3MvI6L4P_LZ0uaVT8NrsgGeoVyCspVadPZ8Di7hnKNjFN8c7j4sPqYM0Cn7ZDtNH5zJGTk9NkBDqFBT1Pq0_2c9i8GlND0unZNT8F_HqCoG7A0fD6XFE10c4X3ZopHLWXs2O-Vwki9W1xVGP_LMho5BFkpMfRaSziAjvEl_FRJOT2fgVDgYzDiBtBwNPzbO7bHHcC2KtEBMrTC4YHX3JFRWu4rzHoMYOVD_mx8C-BkBQ9yC52scmwvjjP5PS4V" />
                                <div class="text-left">
                                    <p class="text-slate-900 dark:text-slate-50 font-bold">{{ __('landing.sol_medical.testimonial_name') }}</p>
                                    <p class="text-slate-500 dark:text-slate-400 text-sm">{{ __('landing.sol_medical.testimonial_role') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- FAQ Accordion -->
                    <div class="mt-24 px-4 mb-20">
                        <h2 class="text-slate-900 dark:text-slate-50 text-[28px] font-bold text-center mb-12">{{ __('landing.sol_medical.faq_title') }}</h2>
                        <div class="max-w-3xl mx-auto space-y-4">
                            <div class="border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden bg-white dark:bg-slate-900/50">
                                <button class="w-full flex items-center justify-between p-6 text-left hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                                    <span class="font-bold text-slate-900 dark:text-slate-50">{{ __('landing.sol_medical.faq1_question') }}</span>
                                    <span class="material-symbols-outlined">expand_more</span>
                                </button>
                                <div class="px-6 pb-6 text-slate-600 dark:text-slate-400">
                                    {{ __('landing.sol_medical.faq1_answer') }}
                                </div>
                            </div>
                            <div class="border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden bg-white dark:bg-slate-900/50">
                                <button class="w-full flex items-center justify-between p-6 text-left hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                                    <span class="font-bold text-slate-900 dark:text-slate-50">{{ __('landing.sol_medical.faq2_question') }}</span>
                                    <span class="material-symbols-outlined">expand_more</span>
                                </button>
                                <div class="px-6 pb-6 text-slate-600 dark:text-slate-400">
                                    {{ __('landing.sol_medical.faq2_answer') }}
                                </div>
                            </div>
                            <div class="border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden bg-white dark:bg-slate-900/50">
                                <button class="w-full flex items-center justify-between p-6 text-left hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                                    <span class="font-bold text-slate-900 dark:text-slate-50">{{ __('landing.sol_medical.faq3_question') }}</span>
                                    <span class="material-symbols-outlined">expand_more</span>
                                </button>
                                <div class="px-6 pb-6 text-slate-600 dark:text-slate-400">
                                    {{ __('landing.sol_medical.faq3_answer') }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Final CTA Section -->
                    <div class="mb-20 px-4">
                        <div class="bg-primary rounded-3xl p-12 text-center text-white relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                            <div class="absolute bottom-0 left-0 w-32 h-32 bg-black/10 rounded-full translate-y-1/2 -translate-x-1/2"></div>
                            <h2 class="text-3xl lg:text-4xl font-black mb-6 relative z-10">{{ __('landing.sol_medical.cta_title') }}</h2>
                            <p class="text-white/90 text-lg mb-10 max-w-xl mx-auto relative z-10">
                                {{ __('landing.sol_medical.cta_description') }}
                            </p>
                            <div class="flex flex-col sm:flex-row gap-4 justify-center relative z-10">
                                <a href="/register-tenant" class="bg-white text-primary hover:bg-slate-50 px-10 py-4 rounded-xl font-black text-lg shadow-lg transition-transform active:scale-95">
                                    {{ __('landing.sol_medical.cta_start') }}
                                </a>
                                <a href="/contact" class="bg-primary-dark/20 border border-white/30 backdrop-blur-sm hover:bg-white/10 px-10 py-4 rounded-xl font-bold text-lg transition-transform active:scale-95">
                                    {{ __('landing.sol_medical.cta_sales') }}
                                </a>
                            </div>
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

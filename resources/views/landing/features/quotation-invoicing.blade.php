<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>{{ __('landing.feat_quotation.page_title') }}</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<style>[x-cloak] { display: none !important; }</style>
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

<body class="bg-background-light dark:bg-background-dark font-display text-slate-900 dark:text-slate-100">
    <!-- BEGIN: MainHeader -->
    @include('landing.partials.header')
    <!-- END: MainHeader -->

    <div class="relative flex h-auto min-h-screen w-full flex-col group/design-root overflow-x-hidden">
        <div class="layout-container flex h-full grow flex-col">
            
            <main class="flex flex-col flex-1">
                <section class="px-4 md:px-20 lg:px-40 py-12 md:py-20 bg-white dark:bg-slate-900">
                    <div class="max-w-[1200px] mx-auto flex flex-col md:flex-row items-center gap-12">
                        <div class="flex-1 flex flex-col gap-6">
                            <div
                                class="inline-flex items-center px-3 py-1 rounded-full bg-primary/10 text-primary text-xs font-bold uppercase tracking-wider">
                                {{ __('landing.feat_quotation.hero_badge') }}
                            </div>
                            <h1
                                class="text-slate-900 dark:text-slate-100 text-4xl md:text-6xl font-black leading-tight tracking-[-0.033em]">
                                {{ __('landing.feat_quotation.hero_title') }}
                            </h1>
                            <p
                                class="text-slate-600 dark:text-slate-400 text-lg md:text-xl font-normal leading-relaxed">
                                {{ __('landing.feat_quotation.hero_description') }}
                            </p>
                            <div class="flex flex-wrap gap-4 pt-4">
                                <button
                                    class="flex min-w-[160px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-14 px-6 bg-primary text-white text-base font-bold transition-transform hover:scale-105">
                                    {{ __('landing.feat_quotation.start_free_trial') }}
                                </button>
                                <button
                                    class="flex min-w-[160px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-14 px-6 border-2 border-primary text-primary text-base font-bold hover:bg-primary/5">
                                    {{ __('landing.feat_quotation.view_demo') }}
                                </button>
                            </div>
                        </div>
                        <div class="flex-1 w-full">
                            <div
                                class="relative rounded-xl overflow-hidden shadow-2xl border border-slate-200 dark:border-slate-700">
                                <div
                                    class="absolute inset-0 bg-gradient-to-tr from-primary/20 to-transparent pointer-events-none">
                                </div>
                                <img alt="{{ __('landing.feat_quotation.hero_image_alt') }}"
                                    class="w-full h-full object-cover aspect-video"
                                    data-alt="Modern software dashboard with financial charts"
                                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuB290Avc9FYfp6DHvzpw_lmo4-86oKm5NQwdz2TgMxREm8q1OWBD-J6VZdvqLgaXwITquocmJvX31OBNGia2AnP7OQSJLmjl7QhyLKYSmnN9Jj5MmdPlTVmNy_av9R7GjCxhUh0XhCEFoCVEQ02Bra1OSYlW9RsNnKp7uRlQdggjqWe6qAEec0lp_k3LavmSPR9iBhdGaAdEGWKcFMjCwze2aOzW3oXFSFH10WwjuiTpxloCkjGlUBpOydX-CFBMzhV9VEODuwvdjNz" />
                            </div>
                        </div>
                    </div>
                </section>
                <section class="px-4 md:px-20 lg:px-40 py-20 bg-background-light dark:bg-background-dark">
                    <div class="max-w-[1200px] mx-auto">
                        <div class="text-center mb-16">
                            <h2 class="text-slate-900 dark:text-slate-100 text-3xl md:text-4xl font-bold mb-4">{{ __('landing.feat_quotation.features_title') }}</h2>
                            <p class="text-slate-600 dark:text-slate-400 text-lg max-w-2xl mx-auto">{{ __('landing.feat_quotation.features_subtitle') }}</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <div
                                class="flex flex-col gap-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm hover:shadow-md transition-shadow">
                                <div
                                    class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
                                    <span class="material-symbols-outlined">description</span>
                                </div>
                                <h3 class="text-slate-900 dark:text-slate-100 text-xl font-bold leading-tight">
                                    {{ __('landing.feat_quotation.feature1_title') }}</h3>
                                <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed">
                                    {{ __('landing.feat_quotation.feature1_desc') }}
                                </p>
                            </div>
                            <div
                                class="flex flex-col gap-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm hover:shadow-md transition-shadow">
                                <div
                                    class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
                                    <span class="material-symbols-outlined">sync_alt</span>
                                </div>
                                <h3 class="text-slate-900 dark:text-slate-100 text-xl font-bold leading-tight">{{ __('landing.feat_quotation.feature2_title') }}</h3>
                                <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed">
                                    {{ __('landing.feat_quotation.feature2_desc') }}
                                </p>
                            </div>
                            <div
                                class="flex flex-col gap-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm hover:shadow-md transition-shadow">
                                <div
                                    class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
                                    <span class="material-symbols-outlined">mail</span>
                                </div>
                                <h3 class="text-slate-900 dark:text-slate-100 text-xl font-bold leading-tight">{{ __('landing.feat_quotation.feature3_title') }}</h3>
                                <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed">
                                    {{ __('landing.feat_quotation.feature3_desc') }}
                                </p>
                            </div>
                            <div
                                class="flex flex-col gap-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm hover:shadow-md transition-shadow">
                                <div
                                    class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
                                    <span class="material-symbols-outlined">download</span>
                                </div>
                                <h3 class="text-slate-900 dark:text-slate-100 text-xl font-bold leading-tight">{{ __('landing.feat_quotation.feature4_title') }}</h3>
                                <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed">
                                    {{ __('landing.feat_quotation.feature4_desc') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="px-4 md:px-20 lg:px-40 py-20 bg-white dark:bg-slate-900">
                    <div class="max-w-[1200px] mx-auto">
                        <div class="flex flex-col lg:flex-row items-center gap-16">
                            <div class="w-full lg:w-1/2">
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="rounded-lg overflow-hidden h-48 bg-primary/5">
                                        <img class="w-full h-full object-cover"
                                            data-alt="Data visualization chart on tablet screen"
                                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuD5dJ5mUMX11xnO69VmKP6vDbtLYvF-wWYELSx3rEcjL0Z1EzcHPsbhtpFcEAuHSxgtA9gIo0fg2lNt_ffJ9RKVtzfHIvRBn9r_UNYrkF_e--QO_nXDrAi4h6sL91GMdiiV4JiyIoDyevVkZHnIM1wT9Ww_35_JPl3Spni2Qbec2oh0Lx1BqWimR7OurxgZhJDqBi7Zv4Bl0irF_KbyXc6TPsZalbUOz_4QO1aeEn5yIqcJ2F2KQYXg0w4vDLe6BY4ojuUNruM725fl" />
                                    </div>
                                    <div class="rounded-lg overflow-hidden h-48 mt-8 bg-primary/5">
                                        <img class="w-full h-full object-cover"
                                            data-alt="Financial analytics dashboard overview"
                                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuBhA1tVyUAPBu3PC7A6RS0lbZsZ1VvMGylzA_NTumZ7VfIJGXT2KynE7q1jr8XHRnhAwZFd1bmSXWV6gn4IWzXvifSd18cPsTjKTfgh1W4EKwwgKXMjR5QZ7Dw9HtnKKtTE-gZyZYOMLK3HbXyY8zc23Y8CS7YbR0XVYbE_o56jbcXdV4d0mtmxrreKQmJ26dycWzBzK-b9k_xYETFa_8HLjWfC2QOUqGKNSxVlij8KRRDcO4RIk9sZJ3_WVWBQk8mqX-xdFZL1g_ud" />
                                    </div>
                                    <div class="rounded-lg overflow-hidden h-48 -mt-8 bg-primary/5">
                                        <img class="w-full h-full object-cover"
                                            data-alt="Person reviewing digital invoice on phone"
                                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuBv0s43nlTqb6UROQLGEjgL_gGbYB7yu41RjjCcc5OpQGRVWnNDBmaipndmG1vIO55Extz5w5jBqHfZUL9LcbQ0HXl-XH2zHeA4nNJriaztdPm_kYShJQclyEPICtl3cTicCfm6drZoYB8TizfxBBPfTxIp2ObxjVMdVO6AQWrjxP7wufTx1xKEyLSZWcHdNY--oCcMywsLSkwgCJff8qC3NljqclEk8EjFKPbIz6wFceWNeap-4QdRJgxjrGsFb-gP_lBOV73qx7_g" />
                                    </div>
                                    <div class="rounded-lg overflow-hidden h-48 bg-primary/5">
                                        <img class="w-full h-full object-cover"
                                            data-alt="Digital signature on electronic document"
                                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuCG8AegCCh1qIAtWv4R_hNBhPn77LlXOx0rd2ZEJdq6_kXjxTPaW2nXQS-ATntt7jmTHXaRuWzVArPtR_Gm-ZCd7iwHtgIUCTyCnT_5mI2onravXrtk09X1TTAFbPpxWC8H6vo166-qzvb3OGEDe_lto9K2mYuonWRLtncsNXoUj19CWtP9Sn8ETGV8oIvNhVgOqxpnSkJ0Ynhllf15vj8iR3r-OEvVrFchOS-nW6NOAfuhdxVAvDd8lwGnquWC7jnbeptDpCitq_uL" />
                                    </div>
                                </div>
                            </div>
                            <div class="w-full lg:w-1/2 flex flex-col gap-8">
                                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 dark:text-slate-100">{{ __('landing.feat_quotation.why_title') }}</h2>
                                <div class="space-y-6">
                                    <div class="flex gap-4">
                                        <div
                                            class="flex-shrink-0 w-8 h-8 rounded-full bg-primary flex items-center justify-center text-white">
                                            <span class="material-symbols-outlined text-sm">check</span>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-slate-900 dark:text-slate-100">{{ __('landing.feat_quotation.why_check1_title') }}
                                            </h4>
                                            <p class="text-slate-600 dark:text-slate-400">{{ __('landing.feat_quotation.why_check1_desc') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex gap-4">
                                        <div
                                            class="flex-shrink-0 w-8 h-8 rounded-full bg-primary flex items-center justify-center text-white">
                                            <span class="material-symbols-outlined text-sm">check</span>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-slate-900 dark:text-slate-100">{{ __('landing.feat_quotation.why_check2_title') }}</h4>
                                            <p class="text-slate-600 dark:text-slate-400">{{ __('landing.feat_quotation.why_check2_desc') }}</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-4">
                                        <div
                                            class="flex-shrink-0 w-8 h-8 rounded-full bg-primary flex items-center justify-center text-white">
                                            <span class="material-symbols-outlined text-sm">check</span>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-slate-900 dark:text-slate-100">{{ __('landing.feat_quotation.why_check3_title') }}
                                            </h4>
                                            <p class="text-slate-600 dark:text-slate-400">{{ __('landing.feat_quotation.why_check3_desc') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="px-4 py-20">
                    <div class="max-w-[1200px] mx-auto">
                        <div
                            class="bg-primary rounded-2xl p-8 md:p-16 text-center text-white flex flex-col items-center gap-6 relative overflow-hidden">
                            <div class="absolute top-0 right-0 p-4 opacity-10">
                                <span class="material-symbols-outlined text-[200px]">payments</span>
                            </div>
                            <h2 class="text-3xl md:text-5xl font-black max-w-2xl">{{ __('landing.feat_quotation.cta_title') }}</h2>
                            <p class="text-lg opacity-90 max-w-xl">{{ __('landing.feat_quotation.cta_description') }}</p>
                            <button
                                class="bg-white text-primary px-10 py-4 rounded-xl font-bold text-lg hover:bg-slate-50 transition-colors shadow-lg mt-4">
                                {{ __('landing.feat_quotation.cta_get_started') }}
                            </button>
                        </div>
                    </div>
                </section>
            </main>
            <footer class="bg-slate-900 text-slate-400 px-10 py-12">
                <div
                    class="max-w-[1200px] mx-auto grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-12 border-b border-slate-800 pb-12 mb-12">
                    <div class="col-span-2">
                        <div class="flex items-center gap-3 text-white mb-6">
                            <span class="material-symbols-outlined text-primary text-3xl">receipt_long</span>
                            <span class="text-xl font-bold tracking-tight">{{ __('landing.feat_quotation.brand_name') }}</span>
                        </div>
                        <p class="max-w-xs leading-relaxed">{{ __('landing.feat_quotation.footer_tagline') }}</p>
                    </div>
                    <div>
                        <h5 class="text-white font-bold mb-6">{{ __('landing.feat_quotation.footer_product') }}</h5>
                        <ul class="space-y-4 text-sm">
                            <li><a class="hover:text-primary" href="#">{{ __('landing.feat_quotation.nav_features') }}</a></li>
                            <li><a class="hover:text-primary" href="#">{{ __('landing.feat_quotation.footer_integrations') }}</a></li>
                            <li><a class="hover:text-primary" href="#">{{ __('landing.feat_quotation.nav_pricing') }}</a></li>
                            <li><a class="hover:text-primary" href="#">{{ __('landing.feat_quotation.footer_roadmap') }}</a></li>
                        </ul>
                    </div>
                    <div>
                        <h5 class="text-white font-bold mb-6">{{ __('landing.feat_quotation.footer_company') }}</h5>
                        <ul class="space-y-4 text-sm">
                            <li><a class="hover:text-primary" href="#">{{ __('landing.feat_quotation.footer_about_us') }}</a></li>
                            <li><a class="hover:text-primary" href="#">{{ __('landing.feat_quotation.footer_careers') }}</a></li>
                            <li><a class="hover:text-primary" href="#">{{ __('landing.feat_quotation.footer_blog') }}</a></li>
                            <li><a class="hover:text-primary" href="#">{{ __('landing.feat_quotation.footer_contact') }}</a></li>
                        </ul>
                    </div>
                    <div>
                        <h5 class="text-white font-bold mb-6">{{ __('landing.feat_quotation.footer_support') }}</h5>
                        <ul class="space-y-4 text-sm">
                            <li><a class="hover:text-primary" href="#">{{ __('landing.feat_quotation.footer_help_center') }}</a></li>
                            <li><a class="hover:text-primary" href="#">{{ __('landing.feat_quotation.footer_api_docs') }}</a></li>
                            <li><a class="hover:text-primary" href="#">{{ __('landing.feat_quotation.footer_community') }}</a></li>
                            <li><a class="hover:text-primary" href="#">{{ __('landing.feat_quotation.footer_security') }}</a></li>
                        </ul>
                    </div>
                </div>
                <div class="max-w-[1200px] mx-auto flex flex-col md:flex-row justify-between items-center gap-6">
                    <p class="text-sm">{{ __('landing.feat_quotation.footer_copyright') }}</p>
                    <div class="flex gap-8 text-sm">
                        <a class="hover:text-white" href="#">{{ __('landing.feat_quotation.footer_privacy_policy') }}</a>
                        <a class="hover:text-white" href="#">{{ __('landing.feat_quotation.footer_terms_of_service') }}</a>
                        <a class="hover:text-white" href="#">{{ __('landing.feat_quotation.footer_cookie_settings') }}</a>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <!-- BEGIN: MainFooter -->
    @include('landing.partials.footer')
    <!-- END: MainFooter -->
</body>

</html>
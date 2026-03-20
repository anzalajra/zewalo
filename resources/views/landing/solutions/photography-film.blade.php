<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>{{ __('landing.sol_photography.page_title') }}</title>
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

<body class="font-display bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 antialiased">
    <!-- BEGIN: MainHeader -->
    @include('landing.partials.header')
    <!-- END: MainHeader -->

    <div class="relative flex h-auto min-h-screen w-full flex-col group/design-root overflow-x-hidden">
        <div class="layout-container flex h-full grow flex-col">
            <div class="px-4 md:px-20 lg:px-40 flex flex-1 justify-center py-12">
                <div class="layout-content-container flex flex-col max-w-[960px] flex-1">
                    <div class="flex flex-wrap justify-between gap-6 p-4">
                        <div class="flex min-w-72 flex-col gap-4">
                            <span
                                class="inline-flex items-center gap-2 text-primary font-bold uppercase tracking-wider text-xs">
                                <span class="material-symbols-outlined text-sm">inventory_2</span>
                                {{ __('landing.sol_photography.badge') }}
                            </span>
                            <h1
                                class="text-slate-900 dark:text-slate-100 text-4xl md:text-5xl font-black leading-tight tracking-[-0.033em]">
                                {{ __('landing.sol_photography.hero_title') }}
                            </h1>
                            <p class="text-slate-600 dark:text-slate-400 text-lg font-normal leading-relaxed max-w-2xl">
                                {{ __('landing.sol_photography.hero_description') }}
                            </p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 mt-8">
                        <div
                            class="flex flex-col gap-4 p-6 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm">
                            <div class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-lg overflow-hidden border border-slate-100 dark:border-slate-700"
                                data-alt="{{ __('landing.sol_photography.before_image_alt') }}"
                                style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuC5tpsClIgsLaxBxcKi2Xq4x3ExKu3yRhr5r6rWDyEVQuBUjyyTgVtCdbLiXBUpB7-kJ0ua09lUKpO1b0vBfJxUGDj1NJaN-HBnLcFuHkm-e7sZlXY5qn6FR_05i0Q3vz_ogoFVwMMssdUp5tLAXG1V4E2uwdA4ot7YO1_qUdIHTAkoLKMyw6o8qybpMST9ozzT57r54wA5_CmoeSq8j9yLHDc2xHhROjHDkftgLuf4lDQbe3FuEDzLKy-GLkwn4mnpBaGOl3_WqWgZ");'>
                            </div>
                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="material-symbols-outlined text-red-500">error</span>
                                    <p class="text-slate-900 dark:text-slate-100 text-lg font-bold leading-normal">
                                        {{ __('landing.sol_photography.before_title') }}</p>
                                </div>
                                <p class="text-slate-600 dark:text-slate-400 text-base font-normal leading-normal">
                                    {{ __('landing.sol_photography.before_description') }}
                                </p>
                            </div>
                        </div>
                        <div
                            class="flex flex-col gap-4 p-6 rounded-xl bg-white dark:bg-slate-800 border-2 border-primary/30 shadow-md">
                            <div class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-lg overflow-hidden border border-slate-100 dark:border-slate-700"
                                data-alt="{{ __('landing.sol_photography.after_image_alt') }}"
                                style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCOb7JW5CioFighjKgd28pfFuXmvOpbU8KyZ6tCSMutBOIKW5KcFV7f1BKWOsC_icLyxyw4RWZFDgpgaTZSP8j41jNWmeOc_8DtLrgkqfyMcDys_AnNzrSD6e2L6KIEIEGtiXloHUvaylD6XYuQ4eXSbleEOWiAt3R_hbmF5V7wCbocy5BmuElHAKi-ctoj7zKgh5XpqcKl5uJtrPXHNzZslx0HQbrgfNiFKh-bkK1y1xxOPb6qruRyd7QUNYphfcIxnExK1mfwIpZk");'>
                            </div>
                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="material-symbols-outlined text-primary">check_circle</span>
                                    <p class="text-slate-900 dark:text-slate-100 text-lg font-bold leading-normal">
                                        {{ __('landing.sol_photography.after_title') }}</p>
                                </div>
                                <p class="text-slate-600 dark:text-slate-400 text-base font-normal leading-normal">
                                    {{ __('landing.sol_photography.after_description') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <h2
                        class="text-slate-900 dark:text-slate-100 text-2xl font-bold leading-tight tracking-[-0.015em] px-4 pb-4 pt-12">
                        {{ __('landing.sol_photography.features_title') }}</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 p-4">
                        <div
                            class="flex flex-1 gap-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 flex-col hover:border-primary transition-colors">
                            <div
                                class="text-primary bg-primary/10 w-12 h-12 flex items-center justify-center rounded-lg">
                                <span class="material-symbols-outlined">package_2</span>
                            </div>
                            <div class="flex flex-col gap-2">
                                <h3 class="text-slate-900 dark:text-slate-100 text-base font-bold leading-tight">{{ __('landing.sol_photography.feature1_title') }}</h3>
                                <p class="text-slate-600 dark:text-slate-400 text-sm font-normal leading-relaxed">{{ __('landing.sol_photography.feature1_description') }}</p>
                            </div>
                        </div>
                        <div
                            class="flex flex-1 gap-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 flex-col hover:border-primary transition-colors">
                            <div
                                class="text-primary bg-primary/10 w-12 h-12 flex items-center justify-center rounded-lg">
                                <span class="material-symbols-outlined">checklist</span>
                            </div>
                            <div class="flex flex-col gap-2">
                                <h3 class="text-slate-900 dark:text-slate-100 text-base font-bold leading-tight">{{ __('landing.sol_photography.feature2_title') }}</h3>
                                <p class="text-slate-600 dark:text-slate-400 text-sm font-normal leading-relaxed">{{ __('landing.sol_photography.feature2_description') }}</p>
                            </div>
                        </div>
                        <div
                            class="flex flex-1 gap-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 flex-col hover:border-primary transition-colors">
                            <div
                                class="text-primary bg-primary/10 w-12 h-12 flex items-center justify-center rounded-lg">
                                <span class="material-symbols-outlined">schedule</span>
                            </div>
                            <div class="flex flex-col gap-2">
                                <h3 class="text-slate-900 dark:text-slate-100 text-base font-bold leading-tight">{{ __('landing.sol_photography.feature3_title') }}</h3>
                                <p class="text-slate-600 dark:text-slate-400 text-sm font-normal leading-relaxed">{{ __('landing.sol_photography.feature3_description') }}</p>
                            </div>
                        </div>
                        <div
                            class="flex flex-1 gap-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 flex-col hover:border-primary transition-colors">
                            <div
                                class="text-primary bg-primary/10 w-12 h-12 flex items-center justify-center rounded-lg">
                                <span class="material-symbols-outlined">shield</span>
                            </div>
                            <div class="flex flex-col gap-2">
                                <h3 class="text-slate-900 dark:text-slate-100 text-base font-bold leading-tight">{{ __('landing.sol_photography.feature4_title') }}</h3>
                                <p class="text-slate-600 dark:text-slate-400 text-sm font-normal leading-relaxed">{{ __('landing.sol_photography.feature4_description') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 mt-12">
                        <div class="bg-primary/5 dark:bg-primary/10 rounded-2xl p-8 border border-primary/20">
                            <div class="flex flex-col md:flex-row gap-8 items-center">
                                <div
                                    class="w-24 h-24 rounded-full overflow-hidden border-4 border-white dark:border-slate-700 shadow-lg shrink-0">
                                    <img alt="{{ __('landing.sol_photography.testimonial_image_alt') }}" class="w-full h-full object-cover"
                                        data-alt="{{ __('landing.sol_photography.testimonial_image_alt') }}"
                                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuCGZSb6MTX04c9_-CcimmnHmkixyL8t_qL5ff1S2z9Y-8NXUl64uC262FM8ddCaIerOQwWvkCCPbj2m54evbd19tqlTuCmGypFcZAUu14cJkTZoHMYsidTea0aYHPTsMbQXzkZVyvGVA6px-6XaLZ295JU2isuQDGebWWGp4OAy6hn3McNhgf3F0dkpFkw_sru0eVEv2Pmz1Lh2PtaAhDIwAIfhNTG-uhUjYMSpUFahTF4Zj4Mbl4s6SPh6WpxIilYMLJDQsy5r2N8B" />
                                </div>
                                <div class="flex flex-col gap-4">
                                    <div class="flex gap-1 text-primary">
                                        <span class="material-symbols-outlined">star</span>
                                        <span class="material-symbols-outlined">star</span>
                                        <span class="material-symbols-outlined">star</span>
                                        <span class="material-symbols-outlined">star</span>
                                        <span class="material-symbols-outlined">star</span>
                                    </div>
                                    <p
                                        class="text-slate-800 dark:text-slate-200 text-xl font-medium italic leading-relaxed">
                                        "{{ __('landing.sol_photography.testimonial_quote') }}"
                                    </p>
                                    <div>
                                        <p class="text-slate-900 dark:text-slate-100 font-bold">{{ __('landing.sol_photography.testimonial_name') }}</p>
                                        <p class="text-slate-500 dark:text-slate-400 text-sm">{{ __('landing.sol_photography.testimonial_role') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h2
                        class="text-slate-900 dark:text-slate-100 text-2xl font-bold leading-tight tracking-[-0.015em] px-4 pb-4 pt-16 text-center">
                        {{ __('landing.sol_photography.faq_title') }}</h2>
                    <div class="flex flex-col gap-3 p-4">
                        <div
                            class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-5">
                            <div class="flex justify-between items-center cursor-pointer">
                                <p class="text-slate-900 dark:text-slate-100 font-bold">{{ __('landing.sol_photography.faq1_question') }}</p>
                                <span class="material-symbols-outlined text-primary">expand_more</span>
                            </div>
                            <p class="mt-4 text-slate-600 dark:text-slate-400 text-sm leading-relaxed">{{ __('landing.sol_photography.faq1_answer') }}</p>
                        </div>
                        <div
                            class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-5">
                            <div class="flex justify-between items-center cursor-pointer">
                                <p class="text-slate-900 dark:text-slate-100 font-bold">{{ __('landing.sol_photography.faq2_question') }}</p>
                                <span class="material-symbols-outlined text-primary">expand_more</span>
                            </div>
                            <p class="mt-4 text-slate-600 dark:text-slate-400 text-sm leading-relaxed">{{ __('landing.sol_photography.faq2_answer') }}</p>
                        </div>
                        <div
                            class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-5">
                            <div class="flex justify-between items-center cursor-pointer">
                                <p class="text-slate-900 dark:text-slate-100 font-bold">{{ __('landing.sol_photography.faq3_question') }}</p>
                                <span class="material-symbols-outlined text-primary">expand_more</span>
                            </div>
                            <p class="mt-4 text-slate-600 dark:text-slate-400 text-sm leading-relaxed">{{ __('landing.sol_photography.faq3_answer') }}</p>
                        </div>
                    </div>
                    <div class="p-4 mt-16 mb-20">
                        <div
                            class="relative overflow-hidden rounded-3xl bg-slate-900 dark:bg-primary/20 p-8 md:p-12 text-center flex flex-col items-center gap-6">
                            <div class="absolute inset-0 opacity-10 pointer-events-none"
                                style="background-image: radial-gradient(circle at 2px 2px, #14B8A6 1px, transparent 0); background-size: 24px 24px;">
                            </div>
                            <h2 class="text-white text-3xl md:text-4xl font-black max-w-xl relative z-10">{{ __('landing.sol_photography.cta_title') }}</h2>
                            <p class="text-slate-300 text-lg max-w-2xl relative z-10">{{ __('landing.sol_photography.cta_description') }}</p>
                            <div class="flex flex-wrap justify-center gap-4 relative z-10">
                                <a href="/register-tenant"
                                    class="bg-primary hover:bg-primary/90 text-white font-bold py-4 px-10 rounded-full transition-all transform hover:scale-105 shadow-xl">
                                    {{ __('landing.sol_photography.cta_trial') }}
                                </a>
                                <a href="/contact"
                                    class="bg-white/10 hover:bg-white/20 text-white font-bold py-4 px-10 rounded-full border border-white/20 backdrop-blur-sm transition-all">
                                    {{ __('landing.sol_photography.cta_demo') }}
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

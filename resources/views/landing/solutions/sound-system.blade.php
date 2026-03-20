<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>{{ __('landing.sol_sound.page_title') }}</title>
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
                                {{ __('landing.sol_sound.hero_title') }}</p>
                            <p class="text-primary text-lg font-medium leading-normal uppercase tracking-wider">{{ __('landing.sol_sound.hero_subtitle') }}</p>
                        </div>
                    </div>
                    <!-- Problem Statement -->
                    <div class="bg-white dark:bg-slate-800/50 rounded-xl p-6 mt-6 border border-primary/10">
                        <h2 class="text-slate-900 dark:text-slate-100 text-[28px] font-bold leading-tight tracking-[-0.015em] pb-3">
                            {{ __('landing.sol_sound.challenge_title') }}</h2>
                        <p class="text-slate-700 dark:text-slate-300 text-lg font-normal leading-relaxed pb-3">
                            {{ __('landing.sol_sound.challenge_description') }}
                        </p>
                    </div>
                    <!-- Before vs After -->
                    <h2 class="text-slate-900 dark:text-slate-100 text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-12">
                        {{ __('landing.sol_sound.comparison_title') }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4">
                        <div class="flex flex-col gap-3 pb-3 bg-white dark:bg-slate-800 p-4 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700">
                            <div class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-lg"
                                data-alt="{{ __('landing.sol_sound.before_image_alt') }}"
                                style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuC9Jsy-T9pbUsK67zMMmcLV0TU_stfQMHYVfruRhv0nUFR4dH5JKhPS19JLOrRvxB5JvFnngtvrYfqemLUR0PdzOcZcupp9wf2_JNDuWuhRYVN1YepIx4PNy_mFDlvICIVav64SBQXVd5QSOWi3cdRYxEB8Zumqs9sVS3pk-U5Bq3qkTwGVEq8mbzrZbXTDB-jO3CFLsLYVScHmJkJwWNHZUvhamKb30Zng903HAYVZHSPHxmcbYhNxE2CIGQP1CivFwsd55AFJcAMs");'>
                            </div>
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="material-symbols-outlined text-red-500">warning</span>
                                    <p class="text-slate-900 dark:text-slate-100 text-xl font-bold leading-normal">
                                        {{ __('landing.sol_sound.before_title') }}</p>
                                </div>
                                <p class="text-slate-600 dark:text-slate-400 text-base font-normal leading-normal">{{ __('landing.sol_sound.before_description') }}</p>
                            </div>
                        </div>
                        <div class="flex flex-col gap-3 pb-3 bg-primary/5 dark:bg-primary/10 p-4 rounded-xl shadow-sm border border-primary/20">
                            <div class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-lg"
                                data-alt="{{ __('landing.sol_sound.after_image_alt') }}"
                                style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuD0O9iXadPg63dWSF33Se0ObPeZhOSfJajehD6qIecqzCeefVPZQciqqetpvGakTqQNGUrXxM6P3yjV0uioE76q1OxrPUi1-Ary-LRsYgZHCaFFnJB6900BZWl6nCObjZenKJueSHUCDV0mq04Ly4sINvkzq3dH_kPa_6gaIySONVRPjllbhXqFY3CBg_0hdXst8axNg1T1ftzSQcvmH2UpMJ24mpl_SOsmGGftka283mcEQgSinBVKRx1tZ36YiHyj0K2Jy6oquGSY");'>
                            </div>
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="material-symbols-outlined text-primary">check_circle</span>
                                    <p class="text-slate-900 dark:text-slate-100 text-xl font-bold leading-normal">
                                        {{ __('landing.sol_sound.after_title') }}</p>
                                </div>
                                <p class="text-slate-600 dark:text-slate-400 text-base font-normal leading-normal">
                                    {{ __('landing.sol_sound.after_description') }}</p>
                            </div>
                        </div>
                    </div>
                    <!-- Testimonial Section -->
                    <div class="my-12 p-8 bg-slate-900 rounded-2xl text-white flex flex-col md:flex-row gap-8 items-center">
                        <div class="w-24 h-24 rounded-full overflow-hidden flex-shrink-0 border-4 border-primary/30">
                            <img alt="{{ __('landing.sol_sound.testimonial_image_alt') }}" class="w-full h-full object-cover"
                                data-alt="{{ __('landing.sol_sound.testimonial_image_alt') }}"
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
                                "{{ __('landing.sol_sound.testimonial_quote') }}"
                            </p>
                            <div>
                                <p class="font-bold text-lg">{{ __('landing.sol_sound.testimonial_name') }}</p>
                                <p class="text-primary text-sm">{{ __('landing.sol_sound.testimonial_role') }}</p>
                            </div>
                        </div>
                    </div>
                    <!-- FAQ Accordion -->
                    <div class="px-4 py-8">
                        <h2 class="text-slate-900 dark:text-slate-100 text-3xl font-bold mb-8">{{ __('landing.sol_sound.faq_title') }}</h2>
                        <div class="space-y-4">
                            <div class="group border-b border-slate-200 dark:border-slate-700 pb-4">
                                <button class="flex w-full items-center justify-between py-2 text-left">
                                    <span class="text-lg font-semibold text-slate-800 dark:text-slate-200">{{ __('landing.sol_sound.faq1_question') }}</span>
                                    <span class="material-symbols-outlined text-primary group-hover:rotate-180 transition-transform">expand_more</span>
                                </button>
                                <div class="mt-2 text-slate-600 dark:text-slate-400">
                                    {{ __('landing.sol_sound.faq1_answer') }}
                                </div>
                            </div>
                            <div class="group border-b border-slate-200 dark:border-slate-700 pb-4">
                                <button class="flex w-full items-center justify-between py-2 text-left">
                                    <span class="text-lg font-semibold text-slate-800 dark:text-slate-200">{{ __('landing.sol_sound.faq2_question') }}</span>
                                    <span class="material-symbols-outlined text-primary group-hover:rotate-180 transition-transform">expand_more</span>
                                </button>
                                <div class="mt-2 text-slate-600 dark:text-slate-400">
                                    {{ __('landing.sol_sound.faq2_answer') }}
                                </div>
                            </div>
                            <div class="group border-b border-slate-200 dark:border-slate-700 pb-4">
                                <button class="flex w-full items-center justify-between py-2 text-left">
                                    <span class="text-lg font-semibold text-slate-800 dark:text-slate-200">{{ __('landing.sol_sound.faq3_question') }}</span>
                                    <span class="material-symbols-outlined text-primary group-hover:rotate-180 transition-transform">expand_more</span>
                                </button>
                                <div class="mt-2 text-slate-600 dark:text-slate-400">
                                    {{ __('landing.sol_sound.faq3_answer') }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- CTA Section -->
                    <div class="mt-16 mb-20 p-10 rounded-2xl bg-gradient-to-br from-primary to-teal-700 text-center flex flex-col items-center gap-6">
                        <h2 class="text-3xl md:text-4xl font-black text-white">{{ __('landing.sol_sound.cta_title') }}</h2>
                        <p class="text-white/90 text-lg max-w-xl">
                            {{ __('landing.sol_sound.cta_description') }}
                        </p>
                        <div class="flex flex-wrap justify-center gap-4 pt-4">
                            <a href="/register-tenant" class="px-8 py-4 bg-white text-teal-900 font-bold rounded-xl hover:bg-slate-100 transition-colors shadow-lg shadow-black/10">
                                {{ __('landing.sol_sound.cta_trial') }}
                            </a>
                            <a href="/contact" class="px-8 py-4 bg-teal-900/30 text-white font-bold rounded-xl border border-white/20 hover:bg-teal-900/40 transition-colors">
                                {{ __('landing.sol_sound.cta_demo') }}
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

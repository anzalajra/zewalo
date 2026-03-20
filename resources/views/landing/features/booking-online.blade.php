<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<style>[x-cloak] { display: none !important; }</style>
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
            <!-- Header (Matches SCREEN_11 style) -->
            <header
                class="flex items-center justify-between whitespace-nowrap border-b border-solid border-slate-200 dark:border-slate-800 bg-white dark:bg-background-dark px-6 md:px-40 py-3 sticky top-0 z-50">
                <div class="flex items-center gap-4">
                    <div class="text-primary size-8">
                        <span class="material-symbols-outlined text-4xl">calendar_month</span>
                    </div>
                    <h2 class="text-slate-900 dark:text-slate-100 text-lg font-bold leading-tight tracking-[-0.015em]">
                        {{ __('landing.feat_booking.brand_name') }}</h2>
                </div>
                <div class="flex flex-1 justify-end gap-8 items-center">
                    <nav class="hidden md:flex items-center gap-9">
                        <a class="text-slate-700 dark:text-slate-300 text-sm font-medium hover:text-primary transition-colors"
                            href="#">{{ __('landing.feat_booking.nav_features') }}</a>
                        <a class="text-slate-700 dark:text-slate-300 text-sm font-medium hover:text-primary transition-colors"
                            href="#">{{ __('landing.feat_booking.nav_pricing') }}</a>
                        <a class="text-slate-700 dark:text-slate-300 text-sm font-medium hover:text-primary transition-colors"
                            href="#">{{ __('landing.feat_booking.nav_about') }}</a>
                    </nav>
                    <button
                        class="flex min-w-[100px] cursor-pointer items-center justify-center rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold hover:opacity-90 transition-opacity">
                        <span>{{ __('landing.feat_booking.get_started') }}</span>
                    </button>
                    <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10 border border-slate-200 dark:border-slate-700"
                        data-alt="User profile avatar placeholder"
                        style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDeYk5sS6nyMP4ppxwNksfnKSwR-2PSpg98uDR2_SbTOT1wsllKkl2naw4UbmUeO1M4avyffU21cLU1td8dPuTea1K2bvbk4vyMtDDNPLgtGx0QXZCjVe7O2mJfOHTiyu-ZaHbkgth85KKQ54uY2aUZdDgcAw7Se7hjsfnZkh-J4sEse80RRHk2fBzQqSvVc6LyXyEUxksLFi8FzHJUnl1XM-PyfaG_ypXXygglYTZHtNWeUffOf6x871CAwutEepxSR5SJDQqi3zoW");'>
                    </div>
                </div>
            </header>
            <main class="flex flex-col items-center">
                <!-- Hero Section -->
                <section class="max-w-[1200px] w-full px-6 py-12 md:py-20">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                        <div class="flex flex-col gap-6">
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/10 text-primary text-sm font-semibold w-fit">
                                <span class="material-symbols-outlined text-sm">bolt</span>
                                {{ __('landing.feat_booking.hero_badge') }}
                            </div>
                            <h1
                                class="text-slate-900 dark:text-slate-100 text-4xl md:text-6xl font-black leading-tight tracking-tight">
                                {{ __('landing.feat_booking.hero_title_prefix') }} <span class="text-primary">{{ __('landing.feat_booking.hero_title_highlight') }}</span> {{ __('landing.feat_booking.hero_title_suffix') }}
                            </h1>
                            <p class="text-slate-600 dark:text-slate-400 text-lg md:text-xl leading-relaxed">
                                {{ __('landing.feat_booking.hero_description') }}
                            </p>
                            <div class="flex flex-wrap gap-4">
                                <button
                                    class="flex min-w-[160px] cursor-pointer items-center justify-center rounded-lg h-12 px-6 bg-primary text-white text-base font-bold shadow-lg shadow-primary/20 hover:scale-[1.02] transition-transform">
                                    {{ __('landing.feat_booking.start_free_trial') }}
                                </button>
                                <button
                                    class="flex min-w-[160px] cursor-pointer items-center justify-center rounded-lg h-12 px-6 border-2 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 text-base font-bold hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                                    {{ __('landing.feat_booking.view_demo') }}
                                </button>
                            </div>
                        </div>
                        <div class="relative">
                            <div
                                class="w-full aspect-[4/3] bg-primary/5 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-2xl">
                                <div
                                    class="bg-slate-100 dark:bg-slate-900 h-8 flex items-center px-4 gap-1.5 border-b border-slate-200 dark:border-slate-800">
                                    <div class="size-2.5 rounded-full bg-red-400"></div>
                                    <div class="size-2.5 rounded-full bg-yellow-400"></div>
                                    <div class="size-2.5 rounded-full bg-green-400"></div>
                                </div>
                                <div class="p-4 h-full bg-white dark:bg-slate-950 flex flex-col gap-4">
                                    <div class="w-full h-32 bg-slate-100 dark:bg-slate-900 rounded-lg animate-pulse"
                                        data-alt="Modern saas dashboard interface preview"
                                        style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCnnmw7S8dAtEcuNXzGaZn4qVxP6reZWBitaTc3ztGytVz6IQ4cOzHiY-xDy641VBRKc_dLxIKSySNCK4ASD__ScTSMYHMrYo9l6M12j6Lcjvm1arfSxM02JHGAfGdwpqe9K4XzhC9ct9kFtKYxXJypzHCdPbL-HUhXMf8UueymJtY-SFBwQ6zH4uzGGwmIYkTAc3Je4H-rTH6cZ5ySSz_3KyrLzg44l7ELgypKyB1ALErUlCCB-sLyQzM-jmg7v06oo-NNuTCkJTYs");'>
                                    </div>
                                    <div class="grid grid-cols-3 gap-3">
                                        <div class="h-24 bg-slate-50 dark:bg-slate-900 rounded-lg"></div>
                                        <div class="h-24 bg-slate-50 dark:bg-slate-900 rounded-lg"></div>
                                        <div class="h-24 bg-slate-50 dark:bg-slate-900 rounded-lg"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Detailed Features Grid -->
                <section
                    class="w-full bg-white dark:bg-slate-950/50 py-20 flex justify-center border-y border-slate-200 dark:border-slate-800">
                    <div class="max-w-[1200px] w-full px-6">
                        <div class="text-center mb-16">
                            <h2 class="text-slate-900 dark:text-slate-100 text-3xl md:text-4xl font-bold mb-4">{{ __('landing.feat_booking.features_title') }}</h2>
                            <p class="text-slate-600 dark:text-slate-400 text-lg max-w-2xl mx-auto">{{ __('landing.feat_booking.features_subtitle') }}</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            <!-- Feature 1: Customer Storefront -->
                            <div
                                class="group p-8 rounded-2xl bg-background-light dark:bg-background-dark border border-slate-200 dark:border-slate-800 hover:border-primary/50 transition-all duration-300 hover:shadow-xl hover:shadow-primary/5">
                                <div
                                    class="size-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                                    <span class="material-symbols-outlined text-3xl">storefront</span>
                                </div>
                                <h3 class="text-xl font-bold mb-3 text-slate-900 dark:text-slate-100">{{ __('landing.feat_booking.feature1_title') }}
                                </h3>
                                <p class="text-slate-600 dark:text-slate-400 leading-relaxed">{{ __('landing.feat_booking.feature1_desc') }}</p>
                            </div>
                            <!-- Feature 2: Live Calendars -->
                            <div
                                class="group p-8 rounded-2xl bg-background-light dark:bg-background-dark border border-slate-200 dark:border-slate-800 hover:border-primary/50 transition-all duration-300 hover:shadow-xl hover:shadow-primary/5">
                                <div
                                    class="size-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                                    <span class="material-symbols-outlined text-3xl">event_available</span>
                                </div>
                                <h3 class="text-xl font-bold mb-3 text-slate-900 dark:text-slate-100">{{ __('landing.feat_booking.feature2_title') }}</h3>
                                <p class="text-slate-600 dark:text-slate-400 leading-relaxed">{{ __('landing.feat_booking.feature2_desc') }}</p>
                            </div>
                            <!-- Feature 3: Quantity Settings -->
                            <div
                                class="group p-8 rounded-2xl bg-background-light dark:bg-background-dark border border-slate-200 dark:border-slate-800 hover:border-primary/50 transition-all duration-300 hover:shadow-xl hover:shadow-primary/5">
                                <div
                                    class="size-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                                    <span class="material-symbols-outlined text-3xl">reorder</span>
                                </div>
                                <h3 class="text-xl font-bold mb-3 text-slate-900 dark:text-slate-100">{{ __('landing.feat_booking.feature3_title') }}</h3>
                                <p class="text-slate-600 dark:text-slate-400 leading-relaxed">{{ __('landing.feat_booking.feature3_desc') }}
                                </p>
                            </div>
                            <!-- Feature 4: Batch Date Editing -->
                            <div
                                class="group p-8 rounded-2xl bg-background-light dark:bg-background-dark border border-slate-200 dark:border-slate-800 hover:border-primary/50 transition-all duration-300 hover:shadow-xl hover:shadow-primary/5">
                                <div
                                    class="size-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                                    <span class="material-symbols-outlined text-3xl">edit_calendar</span>
                                </div>
                                <h3 class="text-xl font-bold mb-3 text-slate-900 dark:text-slate-100">{{ __('landing.feat_booking.feature4_title') }}
                                </h3>
                                <p class="text-slate-600 dark:text-slate-400 leading-relaxed">{{ __('landing.feat_booking.feature4_desc') }}</p>
                            </div>
                            <!-- Feature 5: Automated Payments -->
                            <div
                                class="group p-8 rounded-2xl bg-background-light dark:bg-background-dark border border-slate-200 dark:border-slate-800 hover:border-primary/50 transition-all duration-300 hover:shadow-xl hover:shadow-primary/5">
                                <div
                                    class="size-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                                    <span class="material-symbols-outlined text-3xl">payments</span>
                                </div>
                                <h3 class="text-xl font-bold mb-3 text-slate-900 dark:text-slate-100">{{ __('landing.feat_booking.feature5_title') }}</h3>
                                <p class="text-slate-600 dark:text-slate-400 leading-relaxed">{{ __('landing.feat_booking.feature5_desc') }}</p>
                            </div>
                            <!-- Feature 6: Offline Options -->
                            <div
                                class="group p-8 rounded-2xl bg-background-light dark:bg-background-dark border border-slate-200 dark:border-slate-800 hover:border-primary/50 transition-all duration-300 hover:shadow-xl hover:shadow-primary/5">
                                <div
                                    class="size-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                                    <span class="material-symbols-outlined text-3xl">point_of_sale</span>
                                </div>
                                <h3 class="text-xl font-bold mb-3 text-slate-900 dark:text-slate-100">{{ __('landing.feat_booking.feature6_title') }}</h3>
                                <p class="text-slate-600 dark:text-slate-400 leading-relaxed">{{ __('landing.feat_booking.feature6_desc') }}</p>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Detailed Explanation Section (Alternating) -->
                <section class="max-w-[1200px] w-full px-6 py-20">
                    <div class="flex flex-col gap-24">
                        <!-- Batch Editing Detail -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                            <div class="order-2 lg:order-1 relative p-4 bg-slate-100 dark:bg-slate-900 rounded-2xl">
                                <div
                                    class="bg-white dark:bg-slate-950 rounded-xl shadow-lg p-6 border border-slate-200 dark:border-slate-800">
                                    <div class="flex items-center justify-between mb-6">
                                        <h4 class="font-bold text-slate-900 dark:text-slate-100">{{ __('landing.feat_booking.batch_mock_title') }}</h4>
                                        <span
                                            class="px-2 py-1 bg-green-100 text-green-700 text-xs font-bold rounded">{{ __('landing.feat_booking.batch_mock_badge') }}</span>
                                    </div>
                                    <div class="space-y-4">
                                        <div
                                            class="flex gap-4 items-center p-3 border border-slate-100 dark:border-slate-800 rounded-lg">
                                            <div class="size-10 rounded bg-slate-100 dark:bg-slate-800"></div>
                                            <div class="flex-1 space-y-2">
                                                <div class="h-2 w-24 bg-slate-100 dark:bg-slate-800 rounded"></div>
                                                <div class="h-2 w-full bg-slate-50 dark:bg-slate-900 rounded"></div>
                                            </div>
                                            <div class="size-4 border-2 border-primary rounded-sm bg-primary/10"></div>
                                        </div>
                                        <div
                                            class="flex gap-4 items-center p-3 border border-slate-100 dark:border-slate-800 rounded-lg">
                                            <div class="size-10 rounded bg-slate-100 dark:bg-slate-800"></div>
                                            <div class="flex-1 space-y-2">
                                                <div class="h-2 w-24 bg-slate-100 dark:bg-slate-800 rounded"></div>
                                                <div class="h-2 w-full bg-slate-50 dark:bg-slate-900 rounded"></div>
                                            </div>
                                            <div class="size-4 border-2 border-primary rounded-sm bg-primary/10"></div>
                                        </div>
                                        <div
                                            class="p-3 border-t border-slate-100 dark:border-slate-800 mt-4 flex justify-between items-center">
                                            <span class="text-xs text-slate-400">{{ __('landing.feat_booking.batch_mock_apply') }}</span>
                                            <button
                                                class="bg-primary text-white text-xs px-4 py-2 rounded font-bold">{{ __('landing.feat_booking.batch_mock_update_btn') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="order-1 lg:order-2 space-y-6">
                                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100 leading-tight">{{ __('landing.feat_booking.detail1_title') }}</h2>
                                <p class="text-slate-600 dark:text-slate-400 text-lg leading-relaxed">
                                    {{ __('landing.feat_booking.detail1_desc') }}
                                </p>
                                <ul class="space-y-3">
                                    <li class="flex items-center gap-3 text-slate-700 dark:text-slate-300">
                                        <span class="material-symbols-outlined text-primary">check_circle</span>
                                        {{ __('landing.feat_booking.detail1_check1') }}
                                    </li>
                                    <li class="flex items-center gap-3 text-slate-700 dark:text-slate-300">
                                        <span class="material-symbols-outlined text-primary">check_circle</span>
                                        {{ __('landing.feat_booking.detail1_check2') }}
                                    </li>
                                    <li class="flex items-center gap-3 text-slate-700 dark:text-slate-300">
                                        <span class="material-symbols-outlined text-primary">check_circle</span>
                                        {{ __('landing.feat_booking.detail1_check3') }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- Payments Detail -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                            <div class="space-y-6">
                                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100 leading-tight">{{ __('landing.feat_booking.detail2_title') }}</h2>
                                <p class="text-slate-600 dark:text-slate-400 text-lg leading-relaxed">
                                    {{ __('landing.feat_booking.detail2_desc') }}
                                </p>
                                <div class="grid grid-cols-2 gap-4">
                                    <div
                                        class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
                                        <span class="material-symbols-outlined text-primary mb-2">account_balance</span>
                                        <h4 class="font-bold text-slate-900 dark:text-slate-100">{{ __('landing.feat_booking.payment_bank_title') }}</h4>
                                        <p class="text-xs text-slate-500">{{ __('landing.feat_booking.payment_bank_desc') }}</p>
                                    </div>
                                    <div
                                        class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
                                        <span class="material-symbols-outlined text-primary mb-2">credit_score</span>
                                        <h4 class="font-bold text-slate-900 dark:text-slate-100">{{ __('landing.feat_booking.payment_stripe_title') }}</h4>
                                        <p class="text-xs text-slate-500">{{ __('landing.feat_booking.payment_stripe_desc') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="relative p-8 bg-primary/5 rounded-3xl flex justify-center items-center">
                                <div
                                    class="w-full max-w-sm bg-white dark:bg-slate-950 p-6 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-800">
                                    <div class="flex justify-between items-center mb-8">
                                        <span class="text-slate-500 font-medium">{{ __('landing.feat_booking.checkout_label') }}</span>
                                        <span class="material-symbols-outlined">shopping_cart</span>
                                    </div>
                                    <div class="space-y-6">
                                        <div class="flex justify-between font-bold text-xl">
                                            <span class="text-slate-900 dark:text-slate-100">{{ __('landing.feat_booking.checkout_total') }}</span>
                                            <span class="text-primary">$149.00</span>
                                        </div>
                                        <div class="space-y-3">
                                            <label
                                                class="block p-4 border-2 border-primary bg-primary/5 rounded-xl cursor-pointer">
                                                <div class="flex justify-between items-center">
                                                    <span class="font-bold text-slate-900 dark:text-slate-100">{{ __('landing.feat_booking.checkout_credit_card') }}</span>
                                                    <div class="size-4 rounded-full border-4 border-primary bg-white">
                                                    </div>
                                                </div>
                                            </label>
                                            <label
                                                class="block p-4 border border-slate-200 dark:border-slate-800 rounded-xl cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-900">
                                                <div class="flex justify-between items-center">
                                                    <span class="font-bold text-slate-500">{{ __('landing.feat_booking.checkout_pay_at_venue') }}</span>
                                                    <div class="size-4 rounded-full border border-slate-300"></div>
                                                </div>
                                            </label>
                                        </div>
                                        <button
                                            class="w-full h-12 bg-slate-900 dark:bg-primary text-white font-bold rounded-xl mt-4">{{ __('landing.feat_booking.checkout_confirm') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- CTA Section -->
                <section class="w-full px-6 py-20 flex justify-center">
                    <div
                        class="max-w-[1000px] w-full bg-primary rounded-[2rem] p-8 md:p-16 flex flex-col items-center text-center gap-8 shadow-2xl shadow-primary/30 text-white">
                        <h2 class="text-3xl md:text-5xl font-black leading-tight">{{ __('landing.feat_booking.cta_title') }}</h2>
                        <p class="text-white/80 text-lg md:text-xl max-w-2xl">
                            {{ __('landing.feat_booking.cta_description') }}
                        </p>
                        <div class="flex flex-wrap gap-4 justify-center">
                            <button
                                class="h-14 px-10 bg-white text-primary rounded-xl font-bold text-lg hover:scale-105 transition-transform">
                                {{ __('landing.feat_booking.cta_trial_btn') }}
                            </button>
                            <button
                                class="h-14 px-10 bg-primary/20 text-white border-2 border-white/20 rounded-xl font-bold text-lg hover:bg-white/10 transition-colors">
                                {{ __('landing.feat_booking.cta_demo_btn') }}
                            </button>
                        </div>
                        <p class="text-sm text-white/60">{{ __('landing.feat_booking.cta_no_credit_card') }}</p>
                    </div>
                </section>
            </main>
            <!-- Footer (Matches SCREEN_11 style) -->
            <footer
                class="bg-white dark:bg-background-dark border-t border-slate-200 dark:border-slate-800 px-6 md:px-40 py-16">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
                    <div class="flex flex-col gap-4">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary text-3xl">calendar_month</span>
                            <span class="text-slate-900 dark:text-slate-100 text-xl font-bold">{{ __('landing.feat_booking.brand_name') }}</span>
                        </div>
                        <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed">
                            {{ __('landing.feat_booking.footer_tagline') }}
                        </p>
                    </div>
                    <div>
                        <h4 class="text-slate-900 dark:text-slate-100 font-bold mb-6">{{ __('landing.feat_booking.footer_product') }}</h4>
                        <ul class="space-y-4 text-sm text-slate-500 dark:text-slate-400">
                            <li><a class="hover:text-primary" href="#">{{ __('landing.feat_booking.nav_features') }}</a></li>
                            <li><a class="hover:text-primary" href="#">{{ __('landing.feat_booking.footer_integrations') }}</a></li>
                            <li><a class="hover:text-primary" href="#">{{ __('landing.feat_booking.footer_enterprise') }}</a></li>
                            <li><a class="hover:text-primary" href="#">{{ __('landing.feat_booking.nav_pricing') }}</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-slate-900 dark:text-slate-100 font-bold mb-6">{{ __('landing.feat_booking.footer_company') }}</h4>
                        <ul class="space-y-4 text-sm text-slate-500 dark:text-slate-400">
                            <li><a class="hover:text-primary" href="#">{{ __('landing.feat_booking.footer_about_us') }}</a></li>
                            <li><a class="hover:text-primary" href="#">{{ __('landing.feat_booking.footer_careers') }}</a></li>
                            <li><a class="hover:text-primary" href="#">{{ __('landing.feat_booking.footer_blog') }}</a></li>
                            <li><a class="hover:text-primary" href="#">{{ __('landing.feat_booking.footer_contact') }}</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-slate-900 dark:text-slate-100 font-bold mb-6">{{ __('landing.feat_booking.footer_support') }}</h4>
                        <ul class="space-y-4 text-sm text-slate-500 dark:text-slate-400">
                            <li><a class="hover:text-primary" href="#">{{ __('landing.feat_booking.footer_help_center') }}</a></li>
                            <li><a class="hover:text-primary" href="#">{{ __('landing.feat_booking.footer_api_docs') }}</a></li>
                            <li><a class="hover:text-primary" href="#">{{ __('landing.feat_booking.footer_security') }}</a></li>
                        </ul>
                    </div>
                </div>
                <div
                    class="border-t border-slate-200 dark:border-slate-800 mt-16 pt-8 flex flex-col md:flex-row justify-between items-center gap-6">
                    <p class="text-slate-500 dark:text-slate-400 text-sm">{{ __('landing.feat_booking.footer_copyright') }}</p>
                    <div class="flex gap-6">
                        <a class="text-slate-400 hover:text-primary" href="#"><span
                                class="material-symbols-outlined">public</span></a>
                        <a class="text-slate-400 hover:text-primary" href="#"><span
                                class="material-symbols-outlined">alternate_email</span></a>
                        <a class="text-slate-400 hover:text-primary" href="#"><span
                                class="material-symbols-outlined">chat</span></a>
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
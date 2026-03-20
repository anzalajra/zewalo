<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>{{ __('landing.sol_party.page_title') }}</title>
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
            <div class="px-4 md:px-20 lg:px-40 flex flex-1 justify-center py-10">
                <div class="layout-content-container flex flex-col max-w-[960px] flex-1 gap-8">
                    <!-- Hero Section -->
                    <div class="flex flex-wrap justify-between gap-3 p-4">
                        <div class="flex min-w-72 flex-col gap-3">
                            <h1 class="text-slate-900 dark:text-slate-100 text-4xl font-black leading-tight tracking-[-0.033em]">
                                {{ __('landing.sol_party.hero_title') }}</h1>
                            <p class="text-slate-600 dark:text-slate-400 text-lg font-normal leading-normal">{{ __('landing.sol_party.hero_description') }}</p>
                        </div>
                    </div>
                    <!-- Stats Overview -->
                    <div class="flex flex-wrap gap-4 p-4">
                        <div class="flex min-w-[158px] flex-1 flex-col gap-2 rounded-xl p-6 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
                            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium uppercase tracking-wider">{{ __('landing.sol_party.stat1_label') }}</p>
                            <p class="text-slate-900 dark:text-slate-100 tracking-tight text-3xl font-bold">{{ __('landing.sol_party.stat1_value') }}</p>
                        </div>
                        <div class="flex min-w-[158px] flex-1 flex-col gap-2 rounded-xl p-6 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
                            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium uppercase tracking-wider">{{ __('landing.sol_party.stat2_label') }}</p>
                            <p class="text-primary tracking-tight text-3xl font-bold">{{ __('landing.sol_party.stat2_value') }}</p>
                        </div>
                        <div class="flex min-w-[158px] flex-1 flex-col gap-2 rounded-xl p-6 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
                            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium uppercase tracking-wider">{{ __('landing.sol_party.stat3_label') }}</p>
                            <p class="text-slate-900 dark:text-slate-100 tracking-tight text-3xl font-bold">{{ __('landing.sol_party.stat3_value') }}</p>
                        </div>
                    </div>
                    <!-- Feature Tabs -->
                    <div class="pb-3 px-4">
                        <div class="flex border-b border-slate-200 dark:border-slate-800 gap-8 overflow-x-auto">
                            <a class="flex flex-col items-center justify-center border-b-[3px] border-primary text-primary pb-[13px] pt-4 whitespace-nowrap" href="#">
                                <p class="text-sm font-bold leading-normal tracking-[0.015em]">{{ __('landing.sol_party.tab1') }}</p>
                            </a>
                            <a class="flex flex-col items-center justify-center border-b-[3px] border-transparent text-slate-500 dark:text-slate-400 pb-[13px] pt-4 whitespace-nowrap" href="#">
                                <p class="text-sm font-bold leading-normal tracking-[0.015em]">{{ __('landing.sol_party.tab2') }}</p>
                            </a>
                            <a class="flex flex-col items-center justify-center border-b-[3px] border-transparent text-slate-500 dark:text-slate-400 pb-[13px] pt-4 whitespace-nowrap" href="#">
                                <p class="text-sm font-bold leading-normal tracking-[0.015em]">{{ __('landing.sol_party.tab3') }}</p>
                            </a>
                        </div>
                    </div>
                    <!-- Main Feature Focus -->
                    <div class="flex flex-col lg:flex-row gap-8 p-4">
                        <div class="flex-1">
                            <h2 class="text-slate-900 dark:text-slate-100 text-2xl font-bold mb-4 flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary">calendar_month</span>
                                {{ __('landing.sol_party.feature_main_title') }}
                            </h2>
                            <p class="text-slate-600 dark:text-slate-400 mb-6">{{ __('landing.sol_party.feature_main_description') }}</p>
                            <!-- Mini Calendar UI -->
                            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm">
                                <div class="flex items-center justify-between mb-4">
                                    <button class="p-2 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-full transition-colors">
                                        <span class="material-symbols-outlined">chevron_left</span>
                                    </button>
                                    <p class="text-slate-900 dark:text-slate-100 font-bold">{{ __('landing.sol_party.calendar_month') }}</p>
                                    <button class="p-2 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-full transition-colors">
                                        <span class="material-symbols-outlined">chevron_right</span>
                                    </button>
                                </div>
                                <div class="grid grid-cols-7 text-center text-xs font-bold text-slate-400 mb-2">
                                    <div>S</div><div>M</div><div>T</div><div>W</div><div>T</div><div>F</div><div>S</div>
                                </div>
                                <div class="grid grid-cols-7 gap-1">
                                    <div class="h-10 flex items-center justify-center text-slate-300">26</div>
                                    <div class="h-10 flex items-center justify-center text-slate-300">27</div>
                                    <div class="h-10 flex items-center justify-center text-slate-300">28</div>
                                    <div class="h-10 flex items-center justify-center text-slate-300">29</div>
                                    <div class="h-10 flex items-center justify-center text-slate-300">30</div>
                                    <div class="h-10 flex items-center justify-center text-slate-300">31</div>
                                    <div class="h-10 flex items-center justify-center rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer">1</div>
                                    <div class="h-10 flex items-center justify-center rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer">2</div>
                                    <div class="h-10 flex items-center justify-center bg-primary/20 text-primary font-bold rounded-lg">3</div>
                                    <div class="h-10 flex items-center justify-center bg-primary/20 text-primary font-bold rounded-lg">4</div>
                                    <div class="h-10 flex items-center justify-center bg-primary/20 text-primary font-bold rounded-lg">5</div>
                                    <div class="h-10 flex items-center justify-center rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer">6</div>
                                    <div class="h-10 flex items-center justify-center rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer">7</div>
                                    <div class="h-10 flex items-center justify-center rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer">8</div>
                                    <div class="h-10 flex items-center justify-center rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer">9</div>
                                    <div class="h-10 flex items-center justify-center bg-red-100 text-red-600 font-bold rounded-lg relative group">
                                        10
                                        <span class="absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-[10px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 whitespace-nowrap pointer-events-none">{{ __('landing.sol_party.calendar_conflict') }}</span>
                                    </div>
                                    <div class="h-10 flex items-center justify-center rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer">11</div>
                                    <div class="h-10 flex items-center justify-center rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer">12</div>
                                    <div class="h-10 flex items-center justify-center rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer">13</div>
                                    <div class="h-10 flex items-center justify-center rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer">14</div>
                                    <div class="h-10 flex items-center justify-center rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer">15</div>
                                </div>
                            </div>
                        </div>
                        <!-- Batch Editor Sidebar -->
                        <div class="lg:w-1/3 bg-primary/5 rounded-xl p-6 border border-primary/20">
                            <div class="flex items-center gap-2 mb-4 text-primary">
                                <span class="material-symbols-outlined">edit_square</span>
                                <h3 class="font-bold">{{ __('landing.sol_party.sidebar_title') }}</h3>
                            </div>
                            <ul class="space-y-4">
                                <li class="flex items-start gap-3">
                                    <span class="material-symbols-outlined text-sm mt-1">check_circle</span>
                                    <p class="text-sm text-slate-700 dark:text-slate-300 font-medium">{{ __('landing.sol_party.sidebar_item1') }}</p>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="material-symbols-outlined text-sm mt-1">check_circle</span>
                                    <p class="text-sm text-slate-700 dark:text-slate-300 font-medium">{{ __('landing.sol_party.sidebar_item2') }}</p>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="material-symbols-outlined text-sm mt-1">check_circle</span>
                                    <p class="text-sm text-slate-700 dark:text-slate-300 font-medium">{{ __('landing.sol_party.sidebar_item3') }}</p>
                                </li>
                            </ul>
                            <a href="/register-tenant" class="block w-full mt-6 bg-primary text-white font-bold py-3 rounded-lg hover:bg-primary/90 transition-colors text-center">
                                {{ __('landing.sol_party.sidebar_cta') }}
                            </a>
                        </div>
                    </div>
                    <!-- Case Study Section -->
                    <div class="px-4 py-8 bg-slate-100 dark:bg-slate-800/50 rounded-2xl flex flex-col md:flex-row gap-8 items-center border border-slate-200 dark:border-slate-700">
                        <div class="w-24 h-24 md:w-32 md:h-32 rounded-full overflow-hidden flex-shrink-0 border-4 border-white dark:border-slate-700">
                            <img class="w-full h-full object-cover" data-alt="{{ __('landing.sol_party.testimonial_image_alt') }}"
                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuBhZXaxbVE3xbPv7-vIuCoMDOZ_IQBvothO5XETDwSLpSYrZCpLCOTmzburrBhn7TqyfTj6q74Y4reV-kzK5xL_AMw14DT9Au56kb2Jhbx2f4gDiVdclJumD08L-9EiszE4enJWPDmOEUNC8fiixEyuLUSzoeHOK4so1oTxULrMXQVk38IBd8j0yaBc7F66XQjrzblCv3oazCAJWQ5A4MKX-Ui2IqskTVbt1Z-oziNG0nh6VGxlasw_FP2to6fhMs1qeCFVeJ0D1YwC" />
                        </div>
                        <div>
                            <div class="flex text-primary mb-2">
                                <span class="material-symbols-outlined fill-1">star</span>
                                <span class="material-symbols-outlined fill-1">star</span>
                                <span class="material-symbols-outlined fill-1">star</span>
                                <span class="material-symbols-outlined fill-1">star</span>
                                <span class="material-symbols-outlined fill-1">star</span>
                            </div>
                            <p class="italic text-lg text-slate-800 dark:text-slate-200 mb-4">"{{ __('landing.sol_party.testimonial_quote') }}"</p>
                            <div>
                                <p class="font-bold text-slate-900 dark:text-slate-100">{{ __('landing.sol_party.testimonial_name') }}</p>
                                <p class="text-sm text-slate-500">{{ __('landing.sol_party.testimonial_role') }}</p>
                            </div>
                        </div>
                    </div>
                    <!-- FAQ Section -->
                    <div class="p-4">
                        <h2 class="text-2xl font-bold mb-6">{{ __('landing.sol_party.faq_title') }}</h2>
                        <div class="space-y-3">
                            <div class="group border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden bg-white dark:bg-slate-900">
                                <div class="flex items-center justify-between p-4 cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                                    <span class="font-semibold">{{ __('landing.sol_party.faq1_question') }}</span>
                                    <span class="material-symbols-outlined text-slate-400 group-hover:rotate-180 transition-transform">expand_more</span>
                                </div>
                                <div class="hidden group-hover:block p-4 border-t border-slate-100 dark:border-slate-800 text-slate-600 dark:text-slate-400">
                                    {{ __('landing.sol_party.faq1_answer') }}
                                </div>
                            </div>
                            <div class="group border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden bg-white dark:bg-slate-900">
                                <div class="flex items-center justify-between p-4 cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                                    <span class="font-semibold">{{ __('landing.sol_party.faq2_question') }}</span>
                                    <span class="material-symbols-outlined text-slate-400 group-hover:rotate-180 transition-transform">expand_more</span>
                                </div>
                                <div class="hidden group-hover:block p-4 border-t border-slate-100 dark:border-slate-800 text-slate-600 dark:text-slate-400">
                                    {{ __('landing.sol_party.faq2_answer') }}
                                </div>
                            </div>
                            <div class="group border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden bg-white dark:bg-slate-900">
                                <div class="flex items-center justify-between p-4 cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                                    <span class="font-semibold">{{ __('landing.sol_party.faq3_question') }}</span>
                                    <span class="material-symbols-outlined text-slate-400 group-hover:rotate-180 transition-transform">expand_more</span>
                                </div>
                                <div class="hidden group-hover:block p-4 border-t border-slate-100 dark:border-slate-800 text-slate-600 dark:text-slate-400">
                                    {{ __('landing.sol_party.faq3_answer') }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Call to Action -->
                    <div class="m-4 p-8 bg-background-dark text-white rounded-3xl flex flex-col items-center text-center gap-6 overflow-hidden relative border border-slate-800">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-primary/20 blur-[80px] rounded-full -mr-32 -mt-32"></div>
                        <h2 class="text-3xl font-black max-w-lg relative z-10">{{ __('landing.sol_party.cta_title') }}</h2>
                        <p class="text-slate-300 max-w-md relative z-10">{{ __('landing.sol_party.cta_description') }}</p>
                        <div class="flex flex-wrap gap-4 relative z-10">
                            <a href="/register-tenant" class="bg-primary hover:bg-primary/90 text-white font-bold py-4 px-8 rounded-xl transition-all shadow-lg shadow-primary/20">
                                {{ __('landing.sol_party.cta_trial') }}
                            </a>
                            <a href="/contact" class="bg-white/10 hover:bg-white/20 text-white border border-white/20 font-bold py-4 px-8 rounded-xl transition-all">
                                {{ __('landing.sol_party.cta_demo') }}
                            </a>
                        </div>
                        <p class="text-xs text-slate-500 mt-2 relative z-10">{{ __('landing.sol_party.cta_note') }}</p>
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

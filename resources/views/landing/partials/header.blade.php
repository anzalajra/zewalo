<style>
        .mega-menu {
            display: none;
        }
        .group:hover .mega-menu {
            display: block;
        }
    </style>
<header
                class="sticky top-0 z-50 bg-white/80 dark:bg-background-dark/80 backdrop-blur-md border-b border-slate-200 dark:border-slate-800">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between h-16">
                        <a href="{{ route('home') }}" class="flex items-center gap-2">
                            <div class="text-primary">
                                <svg class="size-8" fill="none" viewbox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M36.7273 44C33.9891 44 31.6043 39.8386 30.3636 33.69C29.123 39.8386 26.7382 44 24 44C21.2618 44 18.877 39.8386 17.6364 33.69C16.3957 39.8386 14.0109 44 11.2727 44C7.25611 44 4 35.0457 4 24C4 12.9543 7.25611 4 11.2727 4C14.0109 4 16.3957 8.16144 17.6364 14.31C18.877 8.16144 21.2618 4 24 4C26.7382 4 29.123 8.16144 30.3636 14.31C31.6043 8.16144 33.9891 4 36.7273 4C40.7439 4 44 12.9543 44 24C44 35.0457 40.7439 44 36.7273 44Z"
                                        fill="currentColor"></path>
                                </svg>
                            </div>
                            <h1 class="text-xl font-bold tracking-tight">Zewalo</h1>
                        </a>
                        <nav class="hidden md:flex flex-1 justify-center items-center h-full">
                            <div class="flex items-center gap-8 h-full">
                                <div class="group h-full flex items-center">
                                    <button
                                        class="flex items-center gap-1 text-sm font-medium hover:text-primary transition-colors py-5">
                                        {{ __('landing.header.features') }}
                                        <span class="material-symbols-outlined text-sm">expand_more</span>
                                    </button>
                                    <div
                                        class="mega-menu absolute top-full left-0 w-full bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 shadow-xl animate-in fade-in slide-in-from-top-2 duration-200">
                                        <div class="max-w-7xl mx-auto grid grid-cols-12 gap-8 p-8">
                                            <div class="col-span-8 grid grid-cols-2 gap-x-8 gap-y-6">
                                                <a class="group/item flex items-start gap-4 p-3 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-all"
                                                    href="{{ url('/feature/live-stock') }}">
                                                    <div
                                                        class="flex-shrink-0 w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary group-hover/item:bg-primary group-hover/item:text-white transition-colors">
                                                        <span class="material-symbols-outlined">show_chart</span>
                                                    </div>
                                                    <div>
                                                        <h3
                                                            class="font-bold text-slate-900 dark:text-white group-hover/item:text-primary transition-colors">
                                                            {{ __('landing.header.live_inventory_stock') }}</h3>
                                                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                                                            {{ __('landing.header.live_inventory_stock_desc') }}</p>
                                                    </div>
                                                </a>
                                                <a class="group/item flex items-start gap-4 p-3 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-all"
                                                    href="{{ url('/feature/inventory-management') }}">
                                                    <div
                                                        class="flex-shrink-0 w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary group-hover/item:bg-primary group-hover/item:text-white transition-colors">
                                                        <span class="material-symbols-outlined">inventory_2</span>
                                                    </div>
                                                    <div>
                                                        <h3
                                                            class="font-bold text-slate-900 dark:text-white group-hover/item:text-primary transition-colors">
                                                            {{ __('landing.header.advanced_management') }}</h3>
                                                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                                                            {{ __('landing.header.advanced_management_desc') }}</p>
                                                    </div>
                                                </a>
                                                <a class="group/item flex items-start gap-4 p-3 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-all"
                                                    href="{{ url('/feature/booking-online') }}">
                                                    <div
                                                        class="flex-shrink-0 w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary group-hover/item:bg-primary group-hover/item:text-white transition-colors">
                                                        <span class="material-symbols-outlined">calendar_today</span>
                                                    </div>
                                                    <div>
                                                        <h3
                                                            class="font-bold text-slate-900 dark:text-white group-hover/item:text-primary transition-colors">
                                                            {{ __('landing.header.booking_online') }}</h3>
                                                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                                                            {{ __('landing.header.booking_online_desc') }}</p>
                                                    </div>
                                                </a>
                                                <a class="group/item flex items-start gap-4 p-3 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-all"
                                                    href="{{ url('/feature/quotation-invoicing') }}">
                                                    <div
                                                        class="flex-shrink-0 w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary group-hover/item:bg-primary group-hover/item:text-white transition-colors">
                                                        <span class="material-symbols-outlined">description</span>
                                                    </div>
                                                    <div>
                                                        <h3
                                                            class="font-bold text-slate-900 dark:text-white group-hover/item:text-primary transition-colors">
                                                            {{ __('landing.header.quotation_invoicing') }}</h3>
                                                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                                                            {{ __('landing.header.quotation_invoicing_desc') }}</p>
                                                    </div>
                                                </a>
                                                <a class="group/item flex items-start gap-4 p-3 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-all col-span-2"
                                                    href="{{ url('/feature/reporting') }}">
                                                    <div
                                                        class="flex-shrink-0 w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary group-hover/item:bg-primary group-hover/item:text-white transition-colors">
                                                        <span class="material-symbols-outlined">bar_chart</span>
                                                    </div>
                                                    <div>
                                                        <h3
                                                            class="font-bold text-slate-900 dark:text-white group-hover/item:text-primary transition-colors">
                                                            {{ __('landing.header.rental_reports') }}</h3>
                                                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('landing.header.rental_reports_desc') }}</p>
                                                    </div>
                                                </a>
                                            </div>
                                            <div
                                                class="col-span-4 border-l border-slate-100 dark:border-slate-800 pl-8 flex flex-col justify-between">
                                                <div>
                                                    <div
                                                        class="relative rounded-xl overflow-hidden aspect-video bg-slate-100 dark:bg-slate-800 mb-4 group/img">
                                                        <div
                                                            class="absolute inset-0 bg-gradient-to-tr from-primary/40 to-primary/10">
                                                        </div>
                                                        <img alt="Mobile App Interface"
                                                            class="w-full h-full object-cover mix-blend-overlay group-hover/img:scale-105 transition-transform duration-500"
                                                            data-alt="Smartphone showing modern rental management app interface"
                                                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuDTWU39Dfm5N8WxRg8baNff83f4QI4-Xp9Ver0e3JbtoqWFTu91J1H2c2gXgFp-lQfXQ4Q3-owcQpg0vy1gHyuR6wt09wD1Kd0LzNdAh5mqVzXIk7DniTF7nWHiF2vgT2IgbYVsWFIvPa541mla8BKAuEjznjGGDUnahr-ju-HY3XOwtBFtB1ZZKH49N6KJX9ajKHVFHJdsK5edTvEwGddtutqu3QPzKiJpkT6t9yZAoHxqd-r6ppA4FSB6NKM6DZHA2NS-AP-uXw1c" />
                                                        <div
                                                            class="absolute bottom-3 left-3 bg-white/90 backdrop-blur dark:bg-slate-900/90 px-3 py-1 rounded-full text-[10px] font-bold tracking-widest uppercase">
                                                            {{ __('landing.header.new_feature') }}</div>
                                                    </div>
                                                    <h4 class="font-bold text-slate-900 dark:text-white">{{ __('landing.header.mobile_management') }}</h4>
                                                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('landing.header.mobile_management_desc') }}</p>
                                                </div>
                                                <a class="mt-6 flex items-center justify-between p-4 rounded-xl bg-primary/5 hover:bg-primary/10 border border-primary/10 transition-colors"
                                                    href="{{ url('/') }}">
                                                    <div class="flex flex-col">
                                                        <span class="font-bold text-slate-900 dark:text-white">{{ __('landing.header.explore_all_features') }}</span>
                                                        <span
                                                            class="text-xs text-slate-500 dark:text-slate-400">{{ __('landing.header.discover_everything') }}</span>
                                                    </div>
                                                    <span
                                                        class="material-symbols-outlined text-primary">arrow_forward</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="group h-full flex items-center">
                                    <button
                                        class="flex items-center gap-1 text-sm font-medium hover:text-primary transition-colors py-5">
                                        {{ __('landing.header.solutions') }}
                                        <span class="material-symbols-outlined text-sm">expand_more</span>
                                    </button>
                                    <div
                                        class="mega-menu absolute top-full left-0 w-full bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 shadow-xl animate-in fade-in slide-in-from-top-2 duration-200">
                                        <div class="max-w-7xl mx-auto grid grid-cols-12 gap-8 p-8">
                                            <div class="col-span-8 grid grid-cols-2 gap-x-8 gap-y-6">
                                                <a class="group/item flex items-start gap-4 p-3 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-all"
                                                    href="{{ url('/solution/photography-film') }}">
                                                    <div
                                                        class="flex-shrink-0 w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary group-hover/item:bg-primary group-hover/item:text-white transition-colors">
                                                        <span class="material-symbols-outlined">photo_camera</span>
                                                    </div>
                                                    <div>
                                                        <h3
                                                            class="font-bold text-slate-900 dark:text-white group-hover/item:text-primary transition-colors">
                                                            {{ __('landing.header.photography_film') }}</h3>
                                                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                                                            {{ __('landing.header.photography_film_desc') }}</p>
                                                    </div>
                                                </a>
                                                <a class="group/item flex items-start gap-4 p-3 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-all"
                                                    href="{{ url('/solution/outdoor-camping') }}">
                                                    <div
                                                        class="flex-shrink-0 w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary group-hover/item:bg-primary group-hover/item:text-white transition-colors">
                                                        <span class="material-symbols-outlined">hiking</span>
                                                    </div>
                                                    <div>
                                                        <h3
                                                            class="font-bold text-slate-900 dark:text-white group-hover/item:text-primary transition-colors">
                                                            {{ __('landing.header.outdoor_camping') }}</h3>
                                                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                                                            {{ __('landing.header.outdoor_camping_desc') }}</p>
                                                    </div>
                                                </a>
                                                <a class="group/item flex items-start gap-4 p-3 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-all"
                                                    href="{{ url('/solution/party-event') }}">
                                                    <div
                                                        class="flex-shrink-0 w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary group-hover/item:bg-primary group-hover/item:text-white transition-colors">
                                                        <span class="material-symbols-outlined">celebration</span>
                                                    </div>
                                                    <div>
                                                        <h3
                                                            class="font-bold text-slate-900 dark:text-white group-hover/item:text-primary transition-colors">
                                                            {{ __('landing.header.party_event') }}</h3>
                                                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                                                            {{ __('landing.header.party_event_desc') }}</p>
                                                    </div>
                                                </a>
                                                <a class="group/item flex items-start gap-4 p-3 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-all"
                                                    href="{{ url('/solution/sound-system') }}">
                                                    <div
                                                        class="flex-shrink-0 w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary group-hover/item:bg-primary group-hover/item:text-white transition-colors">
                                                        <span class="material-symbols-outlined">speaker</span>
                                                    </div>
                                                    <div>
                                                        <h3
                                                            class="font-bold text-slate-900 dark:text-white group-hover/item:text-primary transition-colors">
                                                            {{ __('landing.header.sound_system') }}</h3>
                                                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                                                            {{ __('landing.header.sound_system_desc') }}</p>
                                                    </div>
                                                </a>
                                                <a class="group/item flex items-start gap-4 p-3 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-all"
                                                    href="{{ url('/solution/vehicle') }}">
                                                    <div
                                                        class="flex-shrink-0 w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary group-hover/item:bg-primary group-hover/item:text-white transition-colors">
                                                        <span class="material-symbols-outlined">directions_car</span>
                                                    </div>
                                                    <div>
                                                        <h3
                                                            class="font-bold text-slate-900 dark:text-white group-hover/item:text-primary transition-colors">
                                                            {{ __('landing.header.car_motorcycle') }}</h3>
                                                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                                                            {{ __('landing.header.car_motorcycle_desc') }}</p>
                                                    </div>
                                                </a>
                                                <a class="group/item flex items-start gap-4 p-3 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-all"
                                                    href="{{ url('/solution/medical-equipment') }}">
                                                    <div
                                                        class="flex-shrink-0 w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary group-hover/item:bg-primary group-hover/item:text-white transition-colors">
                                                        <span class="material-symbols-outlined">medical_services</span>
                                                    </div>
                                                    <div>
                                                        <h3
                                                            class="font-bold text-slate-900 dark:text-white group-hover/item:text-primary transition-colors">
                                                            {{ __('landing.header.medical_equipment') }}</h3>
                                                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                                                            {{ __('landing.header.medical_equipment_desc') }}</p>
                                                    </div>
                                                </a>
                                                <a class="group/item flex items-start gap-4 p-3 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-all col-span-2"
                                                    href="{{ url('/solution/baby-mom-needs') }}">
                                                    <div
                                                        class="flex-shrink-0 w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary group-hover/item:bg-primary group-hover/item:text-white transition-colors">
                                                        <span class="material-symbols-outlined">child_care</span>
                                                    </div>
                                                    <div>
                                                        <h3
                                                            class="font-bold text-slate-900 dark:text-white group-hover/item:text-primary transition-colors">
                                                            {{ __('landing.header.baby_mom_needs') }}</h3>
                                                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                                                            {{ __('landing.header.baby_mom_needs_desc') }}</p>
                                                    </div>
                                                </a>
                                            </div>
                                            <div
                                                class="col-span-4 border-l border-slate-100 dark:border-slate-800 pl-8 flex flex-col justify-between">
                                                <div>
                                                    <div
                                                        class="relative rounded-xl overflow-hidden aspect-video bg-slate-100 dark:bg-slate-800 mb-4 group/img">
                                                        <div
                                                            class="absolute inset-0 bg-gradient-to-tr from-primary/40 to-primary/10">
                                                        </div>
                                                        <img alt="{{ __('landing.header.industry_solutions') }}"
                                                            class="w-full h-full object-cover mix-blend-overlay group-hover/img:scale-105 transition-transform duration-500"
                                                            data-alt="Various rental business types showcasing industry solutions"
                                                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuDTWU39Dfm5N8WxRg8baNff83f4QI4-Xp9Ver0e3JbtoqWFTu91J1H2c2gXgFp-lQfXQ4Q3-owcQpg0vy1gHyuR6wt09wD1Kd0LzNdAh5mqVzXIk7DniTF7nWHiF2vgT2IgbYVsWFIvPa541mla8BKAuEjznjGGDUnahr-ju-HY3XOwtBFtB1ZZKH49N6KJX9ajKHVFHJdsK5edTvEwGddtutqu3QPzKiJpkT6t9yZAoHxqd-r6ppA4FSB6NKM6DZHA2NS-AP-uXw1c" />
                                                        <div
                                                            class="absolute bottom-3 left-3 bg-white/90 backdrop-blur dark:bg-slate-900/90 px-3 py-1 rounded-full text-[10px] font-bold tracking-widest uppercase">
                                                            {{ __('landing.header.industry_solutions') }}</div>
                                                    </div>
                                                    <h4 class="font-bold text-slate-900 dark:text-white">{{ __('landing.header.tailored_industry') }}</h4>
                                                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('landing.header.tailored_industry_desc') }}</p>
                                                </div>
                                                <a class="mt-6 flex items-center justify-between p-4 rounded-xl bg-primary/5 hover:bg-primary/10 border border-primary/10 transition-colors"
                                                    href="{{ route('home') }}#solutions">
                                                    <div class="flex flex-col">
                                                        <span class="font-bold text-slate-900 dark:text-white">{{ __('landing.header.explore_all_solutions') }}</span>
                                                        <span
                                                            class="text-xs text-slate-500 dark:text-slate-400">{{ __('landing.header.perfect_fit') }}</span>
                                                    </div>
                                                    <span
                                                        class="material-symbols-outlined text-primary">arrow_forward</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <a class="text-sm font-medium {{ request()->routeIs('landing.pricing') ? 'text-primary' : 'hover:text-primary transition-colors' }}" href="{{ route('landing.pricing') }}">{{ __('landing.header.pricing') }}</a>
                                <a class="text-sm font-medium hover:text-primary transition-colors"
                                    href="{{ route('home') }}#testimonials">{{ __('landing.header.testimonials') }}</a>
                            </div>
                        </nav>
                        <div class="flex items-center gap-3">
                            <x-language-switcher class="hidden sm:block" />
                            <a href="/login-tenant"
                                class="hidden sm:flex h-10 px-4 items-center justify-center text-sm font-bold rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">{{ __('common.login') }}</a>
                            <a href="/register-tenant"
                                class="bg-primary hover:bg-primary/90 text-white text-sm font-bold px-5 py-2.5 rounded-lg transition-all shadow-lg shadow-primary/20">{{ __('common.get_started') }}</a>
                        </div>
                    </div>
                </div>
            </header>
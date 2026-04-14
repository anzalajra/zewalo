<header x-data="{ openMenu: null, mobileOpen: false }" @click.outside="openMenu = null"
    class="sticky top-0 z-50 bg-white/80 dark:bg-background-dark/80 backdrop-blur-md border-b border-slate-200 dark:border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <x-central-brand-logo class="size-8" :showName="true" nameClass="text-xl font-bold tracking-tight" />
            </a>

            {{-- Desktop Navigation --}}
            <nav class="hidden md:flex flex-1 justify-center items-center h-full">
                <div class="flex items-center gap-8 h-full">
                    {{-- Features Dropdown --}}
                    <div class="h-full flex items-center"
                        @mouseenter="openMenu = 'features'" @mouseleave="openMenu = null">
                        <button @click="openMenu = openMenu === 'features' ? null : 'features'"
                            class="flex items-center gap-1 text-sm font-medium hover:text-primary transition-colors py-5">
                            {{ __('landing.header.features') }}
                            <span class="material-symbols-outlined text-sm transition-transform"
                                :class="openMenu === 'features' ? 'rotate-180' : ''">expand_more</span>
                        </button>
                        <div x-show="openMenu === 'features'" x-cloak
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 -translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-1"
                            class="absolute top-full left-1/2 -translate-x-1/2 w-[calc(100vw-2rem)] max-w-7xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xl rounded-b-xl">
                            <div class="grid grid-cols-12 gap-8 p-8">
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

                    {{-- Solutions Dropdown --}}
                    <div class="h-full flex items-center"
                        @mouseenter="openMenu = 'solutions'" @mouseleave="openMenu = null">
                        <button @click="openMenu = openMenu === 'solutions' ? null : 'solutions'"
                            class="flex items-center gap-1 text-sm font-medium hover:text-primary transition-colors py-5">
                            {{ __('landing.header.solutions') }}
                            <span class="material-symbols-outlined text-sm transition-transform"
                                :class="openMenu === 'solutions' ? 'rotate-180' : ''">expand_more</span>
                        </button>
                        <div x-show="openMenu === 'solutions'" x-cloak
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 -translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-1"
                            class="absolute top-full left-1/2 -translate-x-1/2 w-[calc(100vw-2rem)] max-w-7xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xl rounded-b-xl">
                            <div class="grid grid-cols-12 gap-8 p-8">
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
                    class="hidden md:inline-flex bg-primary hover:bg-primary/90 text-white text-sm font-bold px-5 py-2.5 rounded-lg transition-all shadow-lg shadow-primary/20">{{ __('common.get_started') }}</a>

                {{-- Mobile hamburger button --}}
                <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                    <span class="material-symbols-outlined" x-text="mobileOpen ? 'close' : 'menu'">menu</span>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Navigation --}}
    <div x-show="mobileOpen" x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="md:hidden bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 shadow-lg max-h-[80vh] overflow-y-auto">
        <div class="px-4 py-4 space-y-2">
            {{-- Features Section --}}
            <div x-data="{ open: false }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between py-3 text-sm font-medium">
                    {{ __('landing.header.features') }}
                    <span class="material-symbols-outlined text-sm transition-transform"
                        :class="open ? 'rotate-180' : ''">expand_more</span>
                </button>
                <div x-show="open" x-collapse class="pl-4 space-y-1 pb-2">
                    <a href="{{ url('/feature/live-stock') }}" class="flex items-center gap-3 py-2 text-sm text-slate-600 dark:text-slate-400 hover:text-primary">
                        <span class="material-symbols-outlined text-base">show_chart</span>
                        {{ __('landing.header.live_inventory_stock') }}
                    </a>
                    <a href="{{ url('/feature/inventory-management') }}" class="flex items-center gap-3 py-2 text-sm text-slate-600 dark:text-slate-400 hover:text-primary">
                        <span class="material-symbols-outlined text-base">inventory_2</span>
                        {{ __('landing.header.advanced_management') }}
                    </a>
                    <a href="{{ url('/feature/booking-online') }}" class="flex items-center gap-3 py-2 text-sm text-slate-600 dark:text-slate-400 hover:text-primary">
                        <span class="material-symbols-outlined text-base">calendar_today</span>
                        {{ __('landing.header.booking_online') }}
                    </a>
                    <a href="{{ url('/feature/quotation-invoicing') }}" class="flex items-center gap-3 py-2 text-sm text-slate-600 dark:text-slate-400 hover:text-primary">
                        <span class="material-symbols-outlined text-base">description</span>
                        {{ __('landing.header.quotation_invoicing') }}
                    </a>
                    <a href="{{ url('/feature/reporting') }}" class="flex items-center gap-3 py-2 text-sm text-slate-600 dark:text-slate-400 hover:text-primary">
                        <span class="material-symbols-outlined text-base">bar_chart</span>
                        {{ __('landing.header.rental_reports') }}
                    </a>
                </div>
            </div>

            {{-- Solutions Section --}}
            <div x-data="{ open: false }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between py-3 text-sm font-medium">
                    {{ __('landing.header.solutions') }}
                    <span class="material-symbols-outlined text-sm transition-transform"
                        :class="open ? 'rotate-180' : ''">expand_more</span>
                </button>
                <div x-show="open" x-collapse class="pl-4 space-y-1 pb-2">
                    <a href="{{ url('/solution/photography-film') }}" class="flex items-center gap-3 py-2 text-sm text-slate-600 dark:text-slate-400 hover:text-primary">
                        <span class="material-symbols-outlined text-base">photo_camera</span>
                        {{ __('landing.header.photography_film') }}
                    </a>
                    <a href="{{ url('/solution/outdoor-camping') }}" class="flex items-center gap-3 py-2 text-sm text-slate-600 dark:text-slate-400 hover:text-primary">
                        <span class="material-symbols-outlined text-base">hiking</span>
                        {{ __('landing.header.outdoor_camping') }}
                    </a>
                    <a href="{{ url('/solution/party-event') }}" class="flex items-center gap-3 py-2 text-sm text-slate-600 dark:text-slate-400 hover:text-primary">
                        <span class="material-symbols-outlined text-base">celebration</span>
                        {{ __('landing.header.party_event') }}
                    </a>
                    <a href="{{ url('/solution/sound-system') }}" class="flex items-center gap-3 py-2 text-sm text-slate-600 dark:text-slate-400 hover:text-primary">
                        <span class="material-symbols-outlined text-base">speaker</span>
                        {{ __('landing.header.sound_system') }}
                    </a>
                    <a href="{{ url('/solution/vehicle') }}" class="flex items-center gap-3 py-2 text-sm text-slate-600 dark:text-slate-400 hover:text-primary">
                        <span class="material-symbols-outlined text-base">directions_car</span>
                        {{ __('landing.header.car_motorcycle') }}
                    </a>
                    <a href="{{ url('/solution/medical-equipment') }}" class="flex items-center gap-3 py-2 text-sm text-slate-600 dark:text-slate-400 hover:text-primary">
                        <span class="material-symbols-outlined text-base">medical_services</span>
                        {{ __('landing.header.medical_equipment') }}
                    </a>
                    <a href="{{ url('/solution/baby-mom-needs') }}" class="flex items-center gap-3 py-2 text-sm text-slate-600 dark:text-slate-400 hover:text-primary">
                        <span class="material-symbols-outlined text-base">child_care</span>
                        {{ __('landing.header.baby_mom_needs') }}
                    </a>
                </div>
            </div>

            <a href="{{ route('landing.pricing') }}" class="block py-3 text-sm font-medium hover:text-primary">{{ __('landing.header.pricing') }}</a>
            <a href="{{ route('home') }}#testimonials" class="block py-3 text-sm font-medium hover:text-primary">{{ __('landing.header.testimonials') }}</a>

            <div class="pt-4 border-t border-slate-200 dark:border-slate-800 space-y-2">
                <a href="/login-tenant" class="block w-full text-center py-2.5 text-sm font-bold rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800">{{ __('common.login') }}</a>
                <a href="/register-tenant" class="block w-full text-center py-2.5 text-sm font-bold rounded-lg bg-primary hover:bg-primary/90 text-white shadow-lg shadow-primary/20">{{ __('common.get_started') }}</a>
            </div>
        </div>
    </div>
</header>

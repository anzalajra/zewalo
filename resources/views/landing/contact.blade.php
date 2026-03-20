<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>{{ __('landing.contact.page_title') }}</title>
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

<body class="bg-background-light dark:bg-background-dark font-display text-slate-900 dark:text-slate-100 min-h-screen">
    <!-- BEGIN: MainHeader -->
    @include('landing.partials.header')
    <!-- END: MainHeader -->

    <div class="relative flex h-auto min-h-screen w-full flex-col group/design-root overflow-x-hidden">
        <div class="layout-container flex h-full grow flex-col">
            <div class="px-6 md:px-20 lg:px-40 flex flex-1 justify-center py-12">
                <div class="layout-content-container flex flex-col max-w-[1200px] flex-1">
                    <!-- Page Heading Section -->
                    <div class="flex flex-wrap justify-between gap-6 p-4 mb-8">
                        <div class="flex min-w-72 flex-col gap-3">
                            <h1 class="text-slate-900 dark:text-white text-5xl font-black leading-tight tracking-tight">
                                {{ __('landing.contact.heading') }}</h1>
                            <p class="text-primary text-lg font-medium leading-normal">{{ __('landing.contact.subheading') }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 px-4">
                        <!-- Contact Form Section -->
                        <div class="lg:col-span-7 flex flex-col gap-6">
                            <div
                                class="bg-white dark:bg-slate-800/50 p-8 rounded-xl shadow-sm border border-primary/10">
                                <h2
                                    class="text-slate-900 dark:text-white text-2xl font-bold leading-tight tracking-tight pb-6">
                                    {{ __('landing.contact.send_message') }}</h2>
                                <form class="flex flex-col gap-5">
                                    <div class="flex flex-wrap items-end gap-4">
                                        <label class="flex flex-col min-w-40 flex-1">
                                            <p
                                                class="text-slate-700 dark:text-slate-300 text-sm font-semibold leading-normal pb-2">
                                                {{ __('landing.contact.name') }}</p>
                                            <input
                                                class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-slate-900 dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 h-14 placeholder:text-slate-400 p-[15px] text-base font-normal leading-normal transition-all"
                                                placeholder="{{ __('landing.contact.name_placeholder') }}" type="text" value="" />
                                        </label>
                                    </div>
                                    <div class="flex flex-wrap items-end gap-4">
                                        <label class="flex flex-col min-w-40 flex-1">
                                            <p
                                                class="text-slate-700 dark:text-slate-300 text-sm font-semibold leading-normal pb-2">
                                                {{ __('landing.contact.email') }}</p>
                                            <input
                                                class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-slate-900 dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 h-14 placeholder:text-slate-400 p-[15px] text-base font-normal leading-normal transition-all"
                                                placeholder="{{ __('landing.contact.email_placeholder') }}" type="email" value="" />
                                        </label>
                                    </div>
                                    <div class="flex flex-wrap items-end gap-4">
                                        <label class="flex flex-col min-w-40 flex-1">
                                            <p
                                                class="text-slate-700 dark:text-slate-300 text-sm font-semibold leading-normal pb-2">
                                                {{ __('landing.contact.message') }}</p>
                                            <textarea
                                                class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-slate-900 dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 min-h-[180px] placeholder:text-slate-400 p-[15px] text-base font-normal leading-normal transition-all"
                                                placeholder="{{ __('landing.contact.message_placeholder') }}"></textarea>
                                        </label>
                                    </div>
                                    <div class="pt-4">
                                        <button
                                            class="bg-primary hover:bg-primary/90 text-white font-bold py-4 px-8 rounded-lg transition-colors flex items-center justify-center gap-2 group">
                                            {{ __('landing.contact.submit') }}
                                            <span
                                                class="material-symbols-outlined text-xl group-hover:translate-x-1 transition-transform">send</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- Info & Details Section -->
                        <div class="lg:col-span-5 flex flex-col gap-8">
                            <!-- Office Address -->
                            <div class="flex flex-col gap-4">
                                <h3 class="text-slate-900 dark:text-white text-xl font-bold">{{ __('landing.contact.office_address') }}</h3>
                                <div
                                    class="flex items-start gap-4 p-4 rounded-xl bg-primary/5 border border-primary/10">
                                    <div
                                        class="flex h-12 w-12 items-center justify-center rounded-lg bg-primary text-white shrink-0">
                                        <span class="material-symbols-outlined">location_on</span>
                                    </div>
                                    <div class="flex flex-col gap-1">
                                        <p class="font-bold text-slate-900 dark:text-white">{{ __('landing.contact.headquarters') }}</p>
                                        <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                                            {!! __('landing.contact.address') !!}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!-- Contact Details -->
                            <div class="flex flex-col gap-4">
                                <h3 class="text-slate-900 dark:text-white text-xl font-bold">{{ __('landing.contact.direct_contact') }}</h3>
                                <div class="flex flex-col gap-3">
                                    <div
                                        class="flex items-center gap-4 p-4 rounded-xl bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700">
                                        <div
                                            class="flex h-10 w-10 items-center justify-center rounded-full bg-primary/20 text-primary">
                                            <span class="material-symbols-outlined text-xl">mail</span>
                                        </div>
                                        <div class="flex flex-col">
                                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                                                {{ __('landing.contact.email') }}</p>
                                            <a class="text-slate-900 dark:text-white font-medium hover:text-primary transition-colors"
                                                href="mailto:hello@zewalo.com">hello@zewalo.com</a>
                                        </div>
                                    </div>
                                    <div
                                        class="flex items-center gap-4 p-4 rounded-xl bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700">
                                        <div
                                            class="flex h-10 w-10 items-center justify-center rounded-full bg-primary/20 text-primary">
                                            <span class="material-symbols-outlined text-xl">call</span>
                                        </div>
                                        <div class="flex flex-col">
                                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                                                {{ __('landing.contact.phone') }}</p>
                                            <a class="text-slate-900 dark:text-white font-medium hover:text-primary transition-colors"
                                                href="tel:+1555000000">+1 (555) 123-4567</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Map Visual Placeholder -->
                            <div
                                class="w-full h-48 md:h-64 rounded-xl overflow-hidden relative border border-slate-200 dark:border-slate-700 grayscale hover:grayscale-0 transition-all duration-500">
                                <img class="w-full h-full object-cover"
                                    data-alt="Modern minimalist map of San Francisco area" data-location="San Francisco"
                                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuDHebk9qM_0sIo97Uu0eOZXhpKh_kbyMBPMIziXk2hIz_4p_0vg2IOlreOQe8xipdykU7whqeBPpzwExD1evMkHSKcPJEkXdZU1ZSpMk1QccPKZPX27Kdn_scLxJhJftafsLTsdAi6GPxtbMdpx0GR5hADo4ne6K7o0iTuTYsO4FwM1h1F96qcshUP5dg9HHURqZ5gENmN4N1vDLTyj_bXr7j4Cd84VPURLP57tPZVIHycH_4N6ggbUOsp4mgezzEQKmt_mScDD2CW4" />
                                <div class="absolute inset-0 bg-primary/10 pointer-events-none"></div>
                                <div
                                    class="absolute bottom-4 left-4 bg-white dark:bg-slate-900 px-3 py-1 rounded shadow-lg text-xs font-bold text-primary flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm">explore</span> {{ __('landing.contact.find_on_maps') }}
                                </div>
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
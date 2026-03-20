<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>{{ __('landing.about.page_title') }}</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<style>[x-cloak] { display: none !important; }</style>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
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

    <div class="relative flex h-auto min-h-screen w-full flex-col group/design-root overflow-x-hidden">
        <div class="layout-container flex h-full grow flex-col">
            <main class="flex flex-1 justify-center py-12 px-6 lg:px-40">
                <div class="layout-content-container flex flex-col max-w-[960px] flex-1">
                    <!-- Page Heading Section -->
                    <div class="flex flex-wrap justify-between gap-3 p-4">
                        <div class="flex min-w-72 flex-col gap-3">
                            <p
                                class="text-slate-900 dark:text-slate-100 text-5xl font-black leading-tight tracking-[-0.033em]">
                                {{ __('landing.about.heading') }}</p>
                            <p class="text-primary text-lg font-medium leading-normal uppercase tracking-wider">{{ __('landing.about.subheading') }}</p>
                        </div>
                    </div>
                    <!-- Hero Image / Visual Break -->
                    <div class="px-4 py-6">
                        <div class="w-full h-[400px] rounded-xl bg-slate-200 overflow-hidden relative">
                            <img alt="About Zewalo" class="w-full h-full object-cover"
                                data-alt="Modern minimal office workspace with clean desk"
                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuDwKTk-jUfoPEzCFJ1KoOmarW79Bnd9ySfamdy9K4Tx2H1_9Js8RevNVQ54KJQK6mcRzhGFn1M5lTXL0zLLwnpBHYY_5D9hLM270GVylL06Cm8YjG_MSTWf-TZt_gXwIueU0utGpfE37Gu6Jjefb9QE1GQcmwfLKzVUuir4UtDhjT_mQ9rON85B_qtxrDoHbAfoqYURKYh0jfu2AooMblIdAO826OGn4o9pokGcadb1vDHM2IvctZb2aed6r5gnOC2AC1mUjYQtfOMa" />
                            <div class="absolute inset-0 bg-gradient-to-r from-primary/20 to-transparent"></div>
                        </div>
                    </div>
                    <!-- Mission Statement -->
                    <div class="mt-8">
                        <h2
                            class="text-slate-900 dark:text-slate-100 tracking-tight text-[32px] font-bold leading-tight px-4 text-left pb-4 pt-5 border-l-4 border-primary ml-4">
                            {{ __('landing.about.mission_title') }}</h2>
                        <p
                            class="text-slate-700 dark:text-slate-300 text-lg font-normal leading-relaxed pb-3 pt-2 px-4 ml-4">
                            {{ __('landing.about.mission_text') }}
                        </p>
                    </div>
                    <!-- The Zewalo Story -->
                    <div
                        class="mt-12 bg-white dark:bg-slate-800/50 p-8 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800">
                        <h2
                            class="text-slate-900 dark:text-slate-100 text-[28px] font-bold leading-tight tracking-[-0.015em] pb-4">
                            {{ __('landing.about.story_title') }}</h2>
                        <p class="text-slate-700 dark:text-slate-300 text-base font-normal leading-relaxed mb-6">
                            {{ __('landing.about.story_paragraph_1') }}
                        </p>
                        <p class="text-slate-700 dark:text-slate-300 text-base font-normal leading-relaxed">
                            {{ __('landing.about.story_paragraph_2') }}
                        </p>
                    </div>
                    <!-- Core Values Section -->
                    <div class="mt-16">
                        <h2 class="text-slate-900 dark:text-slate-100 text-[28px] font-bold leading-tight px-4 mb-8">{{ __('landing.about.values_title') }}</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 px-4">
                            <!-- Efficiency -->
                            <div class="flex flex-col gap-4 p-6 bg-primary/5 rounded-xl border border-primary/10">
                                <div
                                    class="w-12 h-12 rounded-lg bg-primary flex items-center justify-center text-white">
                                    <span class="material-symbols-outlined text-3xl">bolt</span>
                                </div>
                                <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100">{{ __('landing.about.value_efficiency') }}</h3>
                                <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed">
                                    {{ __('landing.about.value_efficiency_desc') }}
                                </p>
                            </div>
                            <!-- Transparency -->
                            <div class="flex flex-col gap-4 p-6 bg-primary/5 rounded-xl border border-primary/10">
                                <div
                                    class="w-12 h-12 rounded-lg bg-primary flex items-center justify-center text-white">
                                    <span class="material-symbols-outlined text-3xl">visibility</span>
                                </div>
                                <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100">{{ __('landing.about.value_transparency') }}</h3>
                                <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed">
                                    {{ __('landing.about.value_transparency_desc') }}
                                </p>
                            </div>
                            <!-- Growth -->
                            <div class="flex flex-col gap-4 p-6 bg-primary/5 rounded-xl border border-primary/10">
                                <div
                                    class="w-12 h-12 rounded-lg bg-primary flex items-center justify-center text-white">
                                    <span class="material-symbols-outlined text-3xl">trending_up</span>
                                </div>
                                <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100">{{ __('landing.about.value_growth') }}</h3>
                                <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed">
                                    {{ __('landing.about.value_growth_desc') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Final CTA/Inspiration -->
                    <div class="mt-20 mb-10 px-4 text-center">
                        <div class="bg-primary px-8 py-12 rounded-2xl text-white">
                            <h2 class="text-3xl font-bold mb-4">{{ __('landing.about.cta_heading') }}</h2>
                            <p class="text-white/80 max-w-2xl mx-auto mb-8">{{ __('landing.about.cta_description') }}</p>
                            <button
                                class="bg-white text-primary font-bold py-3 px-8 rounded-full hover:bg-slate-100 transition-colors">
                                {{ __('landing.about.cta_button') }}
                            </button>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <!-- BEGIN: MainFooter -->
    @include('landing.partials.footer')
    <!-- END: MainFooter -->
</body>

</html>
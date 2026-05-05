<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>{{ $pageTitle }} — {{ \App\Services\CentralBrandingService::siteName() }}</title>
    <meta name="description" content="{{ $subtitle ?: ('Dokumentasi ' . \App\Services\CentralBrandingService::siteName()) }}">
    @if(\App\Services\CentralBrandingService::faviconUrl())
        <link rel="icon" href="{{ \App\Services\CentralBrandingService::faviconUrl() }}" type="image/png">
    @endif
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries,typography"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>[x-cloak] { display: none !important; }</style>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
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
        body { font-family: 'Inter', sans-serif; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }

        .doc-content { color: #334155; line-height: 1.75; }
        .doc-content h1, .doc-content h2, .doc-content h3, .doc-content h4 {
            color: #0f172a; font-weight: 700; margin-top: 2rem; margin-bottom: 0.75rem; line-height: 1.25;
        }
        .doc-content h1 { font-size: 2rem; }
        .doc-content h2 { font-size: 1.5rem; padding-bottom: 0.4rem; border-bottom: 1px solid #e2e8f0; }
        .doc-content h3 { font-size: 1.25rem; }
        .doc-content h4 { font-size: 1.05rem; }
        .doc-content p { margin: 0.85rem 0; }
        .doc-content a { color: #0d9488; text-decoration: underline; text-underline-offset: 3px; }
        .doc-content a:hover { color: #0f766e; }
        .doc-content ul, .doc-content ol { margin: 0.85rem 0; padding-left: 1.5rem; }
        .doc-content ul { list-style: disc; }
        .doc-content ol { list-style: decimal; }
        .doc-content li { margin: 0.3rem 0; }
        .doc-content code { background: #f1f5f9; color: #0f172a; padding: 0.15rem 0.4rem; border-radius: 0.3rem; font-size: 0.92em; }
        .doc-content pre { background: #0f172a; color: #f1f5f9; padding: 1rem; border-radius: 0.6rem; overflow-x: auto; margin: 1rem 0; }
        .doc-content pre code { background: transparent; color: inherit; padding: 0; font-size: 0.9rem; }
        .doc-content blockquote { border-left: 4px solid #14B8A6; padding: 0.5rem 1rem; color: #475569; background: #f0fdfa; margin: 1rem 0; border-radius: 0.4rem; }
        .doc-content table { width: 100%; border-collapse: collapse; margin: 1rem 0; font-size: 0.95rem; }
        .doc-content th, .doc-content td { border: 1px solid #e2e8f0; padding: 0.55rem 0.85rem; text-align: left; }
        .doc-content th { background: #f8fafc; font-weight: 600; }
        .doc-content img { max-width: 100%; height: auto; border-radius: 0.5rem; margin: 1rem 0; }
        .doc-content hr { border: 0; border-top: 1px solid #e2e8f0; margin: 2rem 0; }

        .dark .doc-content { color: #cbd5e1; }
        .dark .doc-content h1, .dark .doc-content h2, .dark .doc-content h3, .dark .doc-content h4 { color: #f1f5f9; }
        .dark .doc-content h2 { border-color: #1e293b; }
        .dark .doc-content code { background: #1e293b; color: #e2e8f0; }
        .dark .doc-content blockquote { background: rgba(20, 184, 166, 0.08); color: #cbd5e1; }
        .dark .doc-content th { background: #1e293b; }
        .dark .doc-content th, .dark .doc-content td { border-color: #334155; }
        .dark .doc-content hr { border-color: #1e293b; }

        html { scroll-behavior: smooth; }
        :target { scroll-margin-top: 6rem; }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 font-display">
    @include('landing.partials.header')

    <div class="relative flex h-auto min-h-screen w-full flex-col">
        <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Page Heading -->
            <header class="mb-10">
                <p class="text-primary text-sm font-semibold uppercase tracking-wider mb-3">
                    {{ \App\Services\CentralBrandingService::siteName() }}
                </p>
                <h1 class="text-4xl md:text-5xl font-black tracking-tight text-slate-900 dark:text-white">
                    {{ $pageTitle }}
                </h1>
                @if($subtitle)
                    <p class="mt-3 text-lg text-slate-600 dark:text-slate-400 max-w-3xl">{{ $subtitle }}</p>
                @endif
            </header>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                <!-- Sidebar TOC -->
                <aside class="lg:col-span-3 order-2 lg:order-1">
                    <div class="lg:sticky lg:top-24">
                        <div class="bg-white dark:bg-slate-800/50 rounded-xl border border-slate-200 dark:border-slate-800 p-5">
                            <h3 class="font-bold text-sm uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-4">
                                Daftar Isi
                            </h3>
                            @if(count($sections) > 0)
                                <nav class="flex flex-col gap-1">
                                    @foreach($sections as $section)
                                        @if(!empty($section['slug']))
                                            <a href="#{{ $section['slug'] }}"
                                               class="text-sm text-slate-700 dark:text-slate-300 hover:text-primary hover:bg-primary/5 px-3 py-2 rounded-lg transition-colors">
                                                {{ $section['title'] }}
                                            </a>
                                        @else
                                            <span class="text-sm text-slate-700 dark:text-slate-300 px-3 py-2">
                                                {{ $section['title'] }}
                                            </span>
                                        @endif
                                    @endforeach
                                </nav>
                            @else
                                <p class="text-sm text-slate-500 dark:text-slate-400">
                                    Belum ada bagian dokumentasi.
                                </p>
                            @endif

                            <div class="mt-5 pt-5 border-t border-slate-200 dark:border-slate-700">
                                <a href="{{ url('/changelog') }}"
                                   class="flex items-center gap-2 text-sm text-slate-700 dark:text-slate-300 hover:text-primary px-3 py-2 rounded-lg">
                                    <span class="material-symbols-outlined text-base">history</span>
                                    Lihat Changelog
                                </a>
                            </div>
                        </div>
                    </div>
                </aside>

                <!-- Content -->
                <article class="lg:col-span-9 order-1 lg:order-2">
                    <div class="bg-white dark:bg-slate-800/50 rounded-xl border border-slate-200 dark:border-slate-800 p-6 md:p-10">
                        @if($introHtml)
                            <div class="doc-content mb-10">
                                {!! $introHtml !!}
                            </div>
                        @endif

                        @if(count($sections) === 0 && empty($introHtml))
                            <div class="py-16 text-center">
                                <span class="material-symbols-outlined text-6xl text-slate-300 dark:text-slate-700">menu_book</span>
                                <h2 class="mt-4 text-xl font-bold text-slate-900 dark:text-white">
                                    Dokumentasi sedang disiapkan
                                </h2>
                                <p class="mt-2 text-slate-500 dark:text-slate-400">
                                    Konten akan segera tersedia di sini.
                                </p>
                            </div>
                        @endif

                        @foreach($sections as $section)
                            <section id="{{ $section['slug'] }}" class="@if(!$loop->first) mt-14 pt-10 border-t border-slate-200 dark:border-slate-700 @endif">
                                <h2 class="text-2xl md:text-3xl font-bold tracking-tight text-slate-900 dark:text-white mb-4 scroll-mt-24">
                                    @if(!empty($section['slug']))
                                        <a href="#{{ $section['slug'] }}" class="group inline-flex items-baseline gap-2">
                                            <span>{{ $section['title'] }}</span>
                                            <span class="material-symbols-outlined text-base text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100 transition-opacity">link</span>
                                        </a>
                                    @else
                                        {{ $section['title'] }}
                                    @endif
                                </h2>
                                <div class="doc-content">
                                    {!! $section['html'] !!}
                                </div>
                            </section>
                        @endforeach
                    </div>
                </article>
            </div>
        </main>
    </div>

    @include('landing.partials.footer')
</body>
</html>

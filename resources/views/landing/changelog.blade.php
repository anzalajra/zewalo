<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>{{ $pageTitle }} — {{ \App\Services\CentralBrandingService::siteName() }}</title>
    <meta name="description" content="{{ $subtitle }}">
    @if(\App\Services\CentralBrandingService::faviconUrl())
        <link rel="icon" href="{{ \App\Services\CentralBrandingService::faviconUrl() }}" type="image/png">
    @endif
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
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
                    fontFamily: { "display": ["Inter"] },
                    borderRadius: { "DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px" },
                },
            },
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 font-display">
    @include('landing.partials.header')

    @php
        // Tag classes for changelog item badges
        $tagClass = function (?string $tag) {
            $key = strtolower(trim((string) $tag));
            return match (true) {
                $key === 'new' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300',
                $key === 'bug fix' || $key === 'fix' => 'bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-300',
                $key === 'tweak' || $key === 'change' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300',
                $key === 'improvement' || $key === 'improve' => 'bg-violet-100 text-violet-800 dark:bg-violet-900/40 dark:text-violet-300',
                $key === 'security' => 'bg-orange-100 text-orange-800 dark:bg-orange-900/40 dark:text-orange-300',
                $key === 'deprecate' || $key === 'deprecated' => 'bg-slate-200 text-slate-700 dark:bg-slate-700 dark:text-slate-300',
                default => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300',
            };
        };
    @endphp

    <div class="relative flex h-auto min-h-screen w-full flex-col">
        <main class="flex-1 max-w-4xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Page Heading -->
            <header class="mb-10 text-center">
                <p class="text-primary text-sm font-semibold uppercase tracking-wider mb-3">
                    {{ \App\Services\CentralBrandingService::siteName() }}
                </p>
                <h1 class="text-4xl md:text-5xl font-black tracking-tight text-slate-900 dark:text-white">
                    {{ $pageTitle }}
                </h1>
                @if($subtitle)
                    <p class="mt-3 text-lg text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">{{ $subtitle }}</p>
                @endif
                <div class="mt-5">
                    <a href="{{ url('/documentation') }}"
                       class="inline-flex items-center gap-2 text-sm font-medium text-primary hover:text-teal-700">
                        <span class="material-symbols-outlined text-base">menu_book</span>
                        Buka Dokumentasi
                    </a>
                </div>
            </header>

            @if(count($entries) === 0)
                <div class="bg-white dark:bg-slate-800/50 rounded-xl border border-slate-200 dark:border-slate-800 p-12 text-center">
                    <span class="material-symbols-outlined text-6xl text-slate-300 dark:text-slate-700">history</span>
                    <h2 class="mt-4 text-xl font-bold text-slate-900 dark:text-white">Belum ada catatan rilis</h2>
                    <p class="mt-2 text-slate-500 dark:text-slate-400">
                        Riwayat perubahan akan tampil di sini setelah rilis pertama.
                    </p>
                </div>
            @else
                <!-- Timeline -->
                <div class="relative pl-6 md:pl-8 border-l-2 border-slate-200 dark:border-slate-700 space-y-10">
                    @foreach($entries as $entry)
                        <article class="relative">
                            <span class="absolute -left-[34px] md:-left-[42px] top-1.5 w-5 h-5 rounded-full bg-primary ring-4 ring-background-light dark:ring-background-dark"></span>

                            <div class="bg-white dark:bg-slate-800/50 rounded-xl border border-slate-200 dark:border-slate-800 p-6 md:p-8 shadow-sm">
                                <div class="flex flex-wrap items-baseline gap-x-4 gap-y-2 mb-5">
                                    <h2 class="text-2xl md:text-3xl font-bold tracking-tight text-slate-900 dark:text-white">
                                        v{{ ltrim($entry['version'], 'v') }}
                                    </h2>
                                    <time class="text-sm font-medium text-slate-500 dark:text-slate-400" datetime="{{ $entry['date'] }}">
                                        {{ \Illuminate\Support\Carbon::parse($entry['date'])->isoFormat('D MMMM YYYY') }}
                                    </time>
                                </div>

                                @if(count($entry['items']) > 0)
                                    <ul class="space-y-3">
                                        @foreach($entry['items'] as $item)
                                            <li class="flex items-start gap-3">
                                                @if(!empty($item['tag']))
                                                    <span class="inline-flex items-center justify-center text-[11px] font-semibold uppercase tracking-wider px-2.5 py-1 rounded-full whitespace-nowrap mt-0.5 {{ $tagClass($item['tag']) }}">
                                                        {{ $item['tag'] }}
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center justify-center text-[11px] font-semibold uppercase tracking-wider px-2.5 py-1 rounded-full whitespace-nowrap mt-0.5 bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300">
                                                        Update
                                                    </span>
                                                @endif
                                                <span class="text-slate-700 dark:text-slate-300 leading-relaxed">
                                                    {{ $item['text'] }}
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </main>
    </div>

    @include('landing.partials.footer')
</body>
</html>

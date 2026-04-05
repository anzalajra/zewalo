<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>404 — Halaman Tidak Ditemukan · Zewalo</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>[x-cloak] { display: none !important; }</style>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#14B8A6",
                        "background-light": "#ffffff",
                        "background-dark": "#f8fafc",
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.5rem",
                        "lg": "0.75rem",
                        "xl": "1rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
</head>
<body class="bg-white font-display text-slate-900 antialiased">

    {{-- Header --}}
    <header class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="{{ config('app.url') }}" class="flex items-center gap-2">
                    <div class="text-primary">
                        <svg class="size-8" fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                            <path d="M36.7273 44C33.9891 44 31.6043 39.8386 30.3636 33.69C29.123 39.8386 26.7382 44 24 44C21.2618 44 18.877 39.8386 17.6364 33.69C16.3957 39.8386 14.0109 44 11.2727 44C7.25611 44 4 35.0457 4 24C4 12.9543 7.25611 4 11.2727 4C14.0109 4 16.3957 8.16144 17.6364 14.31C18.877 8.16144 21.2618 4 24 4C26.7382 4 29.123 8.16144 30.3636 14.31C31.6043 8.16144 33.9891 4 36.7273 4C40.7439 4 44 12.9543 44 24C44 35.0457 40.7439 44 36.7273 44Z" fill="currentColor"/>
                        </svg>
                    </div>
                    <span class="text-xl font-bold tracking-tight">Zewalo</span>
                </a>
                <a href="{{ config('app.url') }}"
                   class="inline-flex items-center gap-2 bg-primary hover:bg-primary/90 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition-all shadow-lg shadow-primary/20">
                    <span class="material-symbols-outlined text-base leading-none">home</span>
                    Beranda
                </a>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="relative min-h-[calc(100vh-4rem-5rem)] flex items-center overflow-hidden">

        {{-- Background decorative blobs --}}
        <div class="absolute inset-0 -z-10 overflow-hidden pointer-events-none" aria-hidden="true">
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[900px] h-[500px] bg-primary/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-primary/5 rounded-full blur-3xl -translate-x-1/2"></div>
            <div class="absolute bottom-0 right-0 w-[400px] h-[400px] bg-slate-100 rounded-full blur-3xl translate-x-1/2"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 w-full">
            <div class="grid lg:grid-cols-2 gap-16 items-center">

                {{-- Left: Text content --}}
                <div class="text-center lg:text-left">

                    {{-- Badge --}}
                    <div class="inline-flex items-center gap-2 bg-primary/10 text-primary text-sm font-semibold px-4 py-1.5 rounded-full mb-6">
                        <span class="material-symbols-outlined text-base leading-none">error</span>
                        Error 404
                    </div>

                    {{-- Headline --}}
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight leading-[1.1] mb-6">
                        Toko ini<br>
                        <span class="text-primary">belum terdaftar</span><br>
                        di Zewalo
                    </h1>

                    {{-- Description --}}
                    <p class="text-lg text-slate-600 leading-relaxed mb-10 max-w-lg mx-auto lg:mx-0">
                        Subdomain yang Anda kunjungi tidak terhubung ke toko manapun.
                        Mungkin ada typo pada URL, atau toko ini belum dibuat.
                    </p>

                    {{-- CTA Buttons --}}
                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                        <a href="{{ config('app.url') }}"
                           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-primary hover:bg-primary/90 text-white text-base font-bold px-8 py-4 rounded-xl transition-all shadow-xl shadow-primary/25">
                            <span class="material-symbols-outlined text-lg leading-none">home</span>
                            Kembali ke Beranda
                        </a>
                        <a href="{{ config('app.url') }}/register-tenant"
                           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-base font-semibold px-8 py-4 rounded-xl transition-all">
                            <span class="material-symbols-outlined text-lg leading-none">storefront</span>
                            Buka Toko Gratis
                        </a>
                    </div>

                    {{-- Divider --}}
                    <div class="mt-12 pt-8 border-t border-slate-200">
                        <p class="text-sm text-slate-500 mb-4">Mungkin Anda sedang mencari ini?</p>
                        <div class="flex flex-wrap items-center justify-center lg:justify-start gap-3">
                            <a href="{{ config('app.url') }}/pricing"
                               class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-600 hover:text-primary bg-slate-50 hover:bg-primary/5 border border-slate-200 hover:border-primary/20 px-4 py-2 rounded-lg transition-all">
                                <span class="material-symbols-outlined text-sm leading-none">sell</span>
                                Harga & Paket
                            </a>
                            <a href="{{ config('app.url') }}/about-us"
                               class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-600 hover:text-primary bg-slate-50 hover:bg-primary/5 border border-slate-200 hover:border-primary/20 px-4 py-2 rounded-lg transition-all">
                                <span class="material-symbols-outlined text-sm leading-none">info</span>
                                Tentang Kami
                            </a>
                            <a href="{{ config('app.url') }}/contact"
                               class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-600 hover:text-primary bg-slate-50 hover:bg-primary/5 border border-slate-200 hover:border-primary/20 px-4 py-2 rounded-lg transition-all">
                                <span class="material-symbols-outlined text-sm leading-none">support_agent</span>
                                Hubungi Kami
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Right: Visual --}}
                <div class="hidden lg:flex items-center justify-center">
                    <div class="relative w-full max-w-md">

                        {{-- Large 404 backdrop number --}}
                        <div class="absolute inset-0 flex items-center justify-center select-none pointer-events-none" aria-hidden="true">
                            <span class="text-[14rem] font-black text-slate-100 leading-none tracking-tighter">404</span>
                        </div>

                        {{-- Central card --}}
                        <div class="relative bg-white border border-slate-200 rounded-3xl shadow-2xl p-10 text-center z-10">

                            {{-- Icon --}}
                            <div class="mx-auto mb-6 w-20 h-20 bg-primary/10 rounded-2xl flex items-center justify-center">
                                <span class="material-symbols-outlined text-5xl text-primary" style="font-variation-settings:'FILL' 0,'wght' 300;">search_off</span>
                            </div>

                            <h2 class="text-2xl font-black mb-2 text-slate-900">Toko tidak ditemukan</h2>
                            <p class="text-slate-500 text-sm mb-8">
                                Subdomain ini belum terhubung<br>ke toko apapun di Zewalo.
                            </p>

                            {{-- Decorative step hints --}}
                            <div class="space-y-3 text-left">
                                <div class="flex items-start gap-3 p-3 bg-slate-50 rounded-xl">
                                    <div class="flex-shrink-0 w-7 h-7 bg-primary/10 rounded-lg flex items-center justify-center">
                                        <span class="material-symbols-outlined text-sm text-primary">check</span>
                                    </div>
                                    <p class="text-sm text-slate-600">Periksa kembali ejaan subdomain</p>
                                </div>
                                <div class="flex items-start gap-3 p-3 bg-slate-50 rounded-xl">
                                    <div class="flex-shrink-0 w-7 h-7 bg-primary/10 rounded-lg flex items-center justify-center">
                                        <span class="material-symbols-outlined text-sm text-primary">check</span>
                                    </div>
                                    <p class="text-sm text-slate-600">Toko mungkin belum aktif atau telah dihapus</p>
                                </div>
                                <div class="flex items-start gap-3 p-3 bg-slate-50 rounded-xl">
                                    <div class="flex-shrink-0 w-7 h-7 bg-primary/10 rounded-lg flex items-center justify-center">
                                        <span class="material-symbols-outlined text-sm text-primary">check</span>
                                    </div>
                                    <p class="text-sm text-slate-600">Daftar gratis untuk membuat toko baru</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    {{-- Footer --}}
    <footer class="border-t border-slate-100 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2 text-slate-400">
                <div class="text-primary">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 48 48">
                        <path d="M36.7273 44C33.9891 44 31.6043 39.8386 30.3636 33.69C29.123 39.8386 26.7382 44 24 44C21.2618 44 18.877 39.8386 17.6364 33.69C16.3957 39.8386 14.0109 44 11.2727 44C7.25611 44 4 35.0457 4 24C4 12.9543 7.25611 4 11.2727 4C14.0109 4 16.3957 8.16144 17.6364 14.31C18.877 8.16144 21.2618 4 24 4C26.7382 4 29.123 8.16144 30.3636 14.31C31.6043 8.16144 33.9891 4 36.7273 4C40.7439 4 44 12.9543 44 24C44 35.0457 40.7439 44 36.7273 44Z"/>
                    </svg>
                </div>
                <span class="text-sm font-medium text-slate-500">&copy; {{ date('Y') }} Zewalo. Semua hak dilindungi.</span>
            </div>
            <p class="text-sm text-slate-400">
                Platform manajemen rental modern untuk bisnis Anda
            </p>
        </div>
    </footer>

</body>
</html>

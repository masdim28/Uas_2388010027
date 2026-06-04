<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Ade Afwa Boutique') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,900&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="font-sans antialiased bg-[#F1FBFD] text-gray-900">
        <div class="min-h-screen">
            
            {{-- 
                LOGIKA: Sembunyikan navigasi jika berada di halaman:
                1. Detail Produk (products/*)
                2. Semua Produk (products) 
            --}}
            @if(!Request::is('products*'))
                @include('layouts.navigation')
            @endif

            <main>
                {{ $slot }}
            </main>

            <footer class="bg-white/50 border-t border-indigo-50 py-12 mt-12">
                <div class="max-w-7xl mx-auto px-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8 justify-items-center md:justify-items-start">
                        
                        <!-- Logo & Brand -->
                        <div class="flex flex-col items-center md:items-start">
                            <img src="{{ asset('images/logo_adeafwa.png') }}" alt="Logo" class="h-12 mb-4">
                            <p class="text-sm text-gray-700 font-semibold tracking-wider uppercase" style="font-family: 'Playfair Display', serif;">Ade Afwa Boutique</p>
                            <p class="text-xs text-gray-500 mt-2 text-center md:text-left max-w-sm">Koleksi pakaian elegan dengan kualitas terbaik untuk menemani momen spesial Anda.</p>
                        </div>
                        
                        <!-- Store Information -->
                        <div class="flex flex-col items-center md:items-start">
                            <h4 class="text-[#CFB53B] font-bold uppercase tracking-widest text-xs mb-4">Informasi Toko</h4>
                            <ul class="text-xs text-gray-500 space-y-3">
                                <li class="flex items-start gap-3">
                                    <span class="text-base">📍</span>
                                    <span class="leading-relaxed text-left">Jl. Sutawinangun No.84, Sutawinangun, Kec. Kedawung, Kabupaten Cirebon, Jawa Barat 45153</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="text-base">📌</span>
                                    <span class="text-left">Koordinat: 7GMV+R6 Sutawinangun, Kabupaten Cirebon, Jawa Barat</span>
                                </li>
                                <li class="flex items-center gap-3">
                                    <span class="text-base">📞</span>
                                    <span>0877-2901-5880</span>
                                </li>
                                <li class="flex items-center gap-3">
                                    <span class="text-base">🕒</span>
                                    <span>Buka pukul 09.00</span>
                                </li>
                            </ul>
                        </div>

                    </div>

                    <div class="text-center pt-8 border-t border-gray-200">
                        <p class="text-gray-400 text-[10px] uppercase tracking-[0.3em]">© 2026 Ade Afwa Boutique. Dibuat dengan ❤️ oleh Kelompok 5 Informatika UINSSC.</p>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
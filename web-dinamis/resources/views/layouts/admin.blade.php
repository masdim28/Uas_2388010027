<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Ade Afwa Boutique</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,700&family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-serif { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="bg-[#F1FBFD] flex" x-data="{ sidebarOpen: false }">

    <!-- Overlay Mobile -->
    <div x-show="sidebarOpen" 
         style="display: none;" 
         class="fixed inset-0 bg-black/50 z-30 md:hidden" 
         @click="sidebarOpen = false" 
         x-transition.opacity></div>

    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
           class="fixed md:sticky md:top-0 inset-y-0 left-0 w-64 bg-[#F5F5E4] h-screen text-indigo-950 p-6 transform md:translate-x-0 transition-transform duration-300 shadow-[5px_0_15px_rgba(0,0,0,0.05)] border-r border-gray-200 z-40 overflow-y-auto">
        <div class="mb-10 text-center">
            <h2 class="text-2xl font-black tracking-[0.15em] italic font-serif text-[#CFB53B]">ADE AFWA</h2>
            <p class="text-[9px] text-gray-500 uppercase tracking-widest mt-1">Official Dashboard</p>
        </div>
        
        <nav class="space-y-2">
            <p class="text-[10px] font-black text-gray-400 uppercase px-4 tracking-[0.2em] mb-2">Menu Utama</p>
            
            <a href="{{ route('admin.dashboard') }}" 
               class="block py-3 px-4 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-white text-indigo-950 shadow-md border-l-4 border-[#CFB53B]' : 'text-gray-600 hover:bg-white/50 hover:text-indigo-950' }}">
               📊 Dashboard
            </a>

            <a href="{{ route('admin.products.index') }}" 
               class="block py-3 px-4 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.products.*') ? 'bg-white text-indigo-950 shadow-md border-l-4 border-[#CFB53B]' : 'text-gray-600 hover:bg-white/50 hover:text-indigo-950' }}">
               📦 Kelola Produk
            </a>

            <a href="{{ route('admin.categories.index') }}" 
               class="block py-3 px-4 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.categories.*') ? 'bg-white text-indigo-950 shadow-md border-l-4 border-[#CFB53B]' : 'text-gray-600 hover:bg-white/50 hover:text-indigo-950' }}">
               📂 Kelola Kategori
            </a>

            <a href="{{ route('admin.users.index') }}" 
               class="block py-3 px-4 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-white text-indigo-950 shadow-md border-l-4 border-[#CFB53B]' : 'text-gray-600 hover:bg-white/50 hover:text-indigo-950' }}">
               👥 Kelola User
            </a>

            <a href="{{ route('admin.payment-methods.index') }}" 
               class="block py-3 px-4 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.payment-methods.*') ? 'bg-white text-indigo-950 shadow-md border-l-4 border-[#CFB53B]' : 'text-gray-600 hover:bg-white/50 hover:text-indigo-950' }}">
               💳 Metode Pembayaran
            </a>
            
            <div class="pt-6 pb-2 border-t border-gray-200 mt-4">
                <p class="text-[10px] font-black text-gray-400 uppercase px-4 tracking-[0.2em]">Manajemen Pesanan</p>
            </div>
            
            <a href="{{ route('admin.orders.transaksi') }}" 
               class="group block py-3 px-4 rounded-xl transition-all {{ request()->routeIs('admin.orders.transaksi') ? 'bg-white text-indigo-950 border-l-4 border-[#CFB53B] shadow-md' : 'text-gray-600 hover:bg-white/50 hover:text-indigo-950' }}">
                <div class="flex items-center gap-3">
                    <span class="bg-indigo-100 group-hover:bg-indigo-200 w-6 h-6 flex items-center justify-center rounded-lg text-[10px] font-bold text-indigo-900">1</span>
                    <span class="text-sm font-semibold">Transaksi</span>
                </div>
            </a>

            <a href="{{ route('admin.orders.pesanan') }}" 
               class="group block py-3 px-4 rounded-xl transition-all {{ request()->routeIs('admin.orders.pesanan') ? 'bg-white text-indigo-950 border-l-4 border-[#CFB53B] shadow-md' : 'text-gray-600 hover:bg-white/50 hover:text-indigo-950' }}">
                <div class="flex items-center gap-3">
                    <span class="bg-indigo-100 group-hover:bg-indigo-200 w-6 h-6 flex items-center justify-center rounded-lg text-[10px] font-bold text-indigo-900">2</span>
                    <span class="text-sm font-semibold">Pesanan</span>
                </div>
            </a>

            <a href="{{ route('admin.orders.selesai') }}" 
               class="group block py-3 px-4 rounded-xl transition-all {{ request()->routeIs('admin.orders.selesai') ? 'bg-white text-indigo-950 border-l-4 border-[#CFB53B] shadow-md' : 'text-gray-600 hover:bg-white/50 hover:text-indigo-950' }}">
                <div class="flex items-center gap-3">
                    <span class="bg-indigo-100 group-hover:bg-indigo-200 w-6 h-6 flex items-center justify-center rounded-lg text-[10px] font-bold text-indigo-900">3</span>
                    <span class="text-sm font-semibold">Selesai</span>
                </div>
            </a>

            <div class="pt-10">
                <a href="/" target="_blank" class="group bg-white/60 p-4 rounded-2xl border border-gray-200 flex flex-col gap-2 hover:bg-white transition-all text-center shadow-sm">
                    <span class="text-[9px] text-gray-500 font-bold uppercase tracking-widest">Customer View</span>
                    <div class="flex items-center justify-center gap-2 text-indigo-950 text-xs font-bold italic">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#CFB53B]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Buka Website
                    </div>
                </a>
            </div>
        </nav>
    </aside>

    <div class="flex-1 flex flex-col min-w-0 min-h-screen relative">
        
        <header class="bg-[#F5F5E4] p-4 md:p-8 shadow-sm border-b border-gray-200 sticky top-0 z-10">
            <div class="flex justify-between items-center gap-4">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" class="md:hidden text-indigo-950 p-2 focus:outline-none hover:bg-indigo-950/5 rounded-lg transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <div>
                        <h1 class="text-xl md:text-3xl font-black text-indigo-950 italic font-serif tracking-tight uppercase line-clamp-1">{{ $header ?? 'Dashboard' }}</h1>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse shadow-[0_0_8px_rgba(52,211,153,0.5)]"></span>
                            <p class="text-gray-500 text-[10px] font-bold uppercase tracking-widest hidden sm:block">Sistem Manajemen Butik</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-6">
                    <div class="text-right border-r border-gray-300 pr-6 hidden sm:block">
                        <p class="text-sm font-bold text-indigo-950 tracking-wide">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-[#CFB53B] font-black uppercase tracking-widest">Administrator</p>
                    </div>
                    
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-6 py-2.5 rounded-xl text-xs font-black transition-all shadow-lg shadow-red-950/40 uppercase tracking-tighter active:scale-95">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </header>

       <main class="p-8 flex-grow">
            {{-- 
                GANTI {{ $slot }} menjadi @yield('content') 
                karena Anda menggunakan struktur @extends di halaman dashboard 
            --}}
            @yield('content') 
        </main>

        <footer class="p-8 pt-0">
            <p class="text-center text-gray-400 text-[10px] uppercase tracking-widest font-bold">
                &copy; 2026 ADE AFWA BOUTIQUE 
            </p>
        </footer>

    </div>

    {{-- 
        PENTING: Tambahkan @stack('scripts') di sini!
        Ini tempat menempelnya script Chart.js dari halaman dashboard 
    --}}
    @stack('scripts')
</body>
</html>
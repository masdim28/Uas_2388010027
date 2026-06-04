<x-app-layout>
    <div class="py-12 bg-[#F1FBFD] min-h-screen relative">
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-8 flex justify-start">
                <a href="/" class="group flex items-center gap-2 text-gray-400 hover:text-[#CFB53B] transition-all duration-300">
                    <div class="p-2 rounded-full bg-white shadow-sm group-hover:shadow-md transition-shadow">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                    </div>
                    <span class="text-[10px] font-bold uppercase tracking-[0.2em]">Beranda</span>
                </a>
            </div>

            <div class="text-center mb-16">
                <h2 class="text-[10px] font-bold uppercase tracking-[0.5em] text-[#CFB53B] mb-4">Koleksi Terbaru</h2>
                
                @if(isset($query) && $query)
                    <h1 class="text-3xl md:text-4xl font-serif text-gray-800 tracking-tight mb-2">
                        Hasil pencarian untuk: <span class="text-[#CFB53B]">"{{ $query }}"</span>
                    </h1>
                    <p class="text-xs text-gray-500">Ditemukan {{ $products->count() }} produk yang sesuai</p>
                @else
                    <h1 class="text-4xl md:text-5xl font-serif text-gray-800 tracking-tight">Semua Produk</h1>
                @endif

                <div class="mt-4 flex justify-center">
                    <div class="h-0.5 w-24 bg-[#CFB53B]"></div>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                @foreach($products as $p)
                <div class="group relative bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 border border-gray-100 flex flex-col h-full">
                    
                    <div class="relative overflow-hidden aspect-[3/4] bg-gray-50">
                        <img src="{{ asset('storage/' . $p->image) }}" 
                             alt="{{ $p->name }}" 
                             class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                        
                        <div class="absolute top-4 left-4 bg-white/80 backdrop-blur-md px-3 py-1 rounded-full shadow-sm">
                            <span class="text-[9px] font-bold uppercase tracking-widest text-[#CFB53B]">Eksklusif</span>
                        </div>

                        <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex items-center justify-center">
                            <a href="/products/{{ $p->id }}" class="bg-white text-gray-900 px-6 py-2.5 rounded-sm text-xs font-bold uppercase tracking-widest hover:bg-[#CFB53B] hover:text-white transition-colors shadow-lg">
                                Lihat Detail
                            </a>
                        </div>
                    </div>

                    <div class="p-6 flex-grow flex flex-col text-center bg-white">
                        <h3 class="text-gray-800 font-serif text-lg mb-2 capitalize group-hover:text-[#CFB53B] transition-colors">
                            {{ $p->name }}
                        </h3>
                        @php
                            $minP = $p->variants->min('price');
                            $maxP = $p->variants->max('price');
                        @endphp
                        @if($minP !== null && $minP != $maxP)
                            <p class="text-[#CFB53B] font-medium tracking-wide">
                                <span class="text-[10px] text-gray-400 font-normal">Mulai </span>Rp {{ number_format($minP, 0, ',', '.') }}
                            </p>
                        @else
                            <p class="text-[#CFB53B] font-medium tracking-wide">
                                Rp {{ number_format($minP ?? $p->price, 0, ',', '.') }}
                            </p>
                        @endif
                        
                        <div class="mt-4 pt-4 border-t border-gray-50 flex flex-wrap justify-center gap-1">
                            @foreach($p->categories as $category)
                                <span class="text-[8px] uppercase tracking-widest bg-[#F1FBFD] text-indigo-400 px-2 py-1 rounded font-bold">
                                    {{ $category->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            @if($products->isEmpty())
            <div class="text-center py-20 bg-white/50 rounded-3xl border border-dashed border-gray-200">
                <p class="text-gray-400 italic font-serif">Maaf, saat ini koleksi yang Anda cari tidak ditemukan.</p>
                <a href="{{ route('products.index') }}" class="inline-block mt-4 text-[#CFB53B] underline text-xs">
                    Tampilkan Semua Produk
                </a>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>
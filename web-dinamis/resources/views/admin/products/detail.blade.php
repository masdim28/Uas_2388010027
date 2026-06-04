@extends('layouts.admin')

@section('content')
    <div class="max-w-7xl mx-auto">
        {{-- Header Section --}}
        <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <a href="{{ route('admin.products.index') }}" class="group flex items-center gap-2 text-indigo-400 hover:text-indigo-600 transition-colors font-black text-[10px] uppercase tracking-[0.2em] mb-3">
                    <div class="p-1.5 bg-white rounded-lg shadow-sm group-hover:-translate-x-1 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </div>
                    Kembali ke Katalog
                </a>
                <h1 class="text-4xl font-black text-indigo-950 tracking-tighter uppercase italic">Detail Informasi Produk</h1>
            </div>

            <a href="{{ route('admin.products.edit', $product->id) }}" class="bg-[#CFB53B] hover:bg-[#b89f33] text-indigo-950 px-10 py-4 rounded-2xl font-black shadow-lg transition transform hover:-translate-y-1 uppercase text-xs tracking-widest flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Data Koleksi
            </a>
        </div>

        {{-- Main Detail Card --}}
        <div class="bg-white rounded-[3rem] shadow-[0_30px_80px_rgba(0,0,0,0.04)] border border-white overflow-hidden mb-10">
            <div class="flex flex-col lg:flex-row">

                {{-- Product Visuals --}}
                <div class="lg:w-1/2 p-8 md:p-12 bg-[#F1FBFD]/50 border-r border-gray-50">
                    <div class="sticky top-10">
                        <div class="mb-10 group relative">
                            <div class="absolute inset-0 bg-[#CFB53B]/10 blur-3xl rounded-full scale-75 group-hover:scale-100 transition-transform duration-700"></div>
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}"
                                     id="mainImage"
                                     class="relative w-full rounded-[2.5rem] shadow-2xl border-[12px] border-white object-cover aspect-square transition-all duration-500 z-10"
                                     alt="{{ $product->name }}">
                            @else
                                <div id="mainImage" class="relative w-full rounded-[2.5rem] shadow-2xl border-[12px] border-white aspect-square bg-gray-100 flex items-center justify-center z-10">
                                    <span class="text-gray-400 text-sm italic">Tidak ada foto</span>
                                </div>
                            @endif
                        </div>

                        {{-- Thumbnail Gallery: Foto utama + foto per varian --}}
                        <div class="flex flex-wrap gap-4 justify-center relative z-20">
                            {{-- Foto-foto utama produk --}}
                            @foreach($product->images as $img)
                                <img src="{{ asset('storage/' . $img->image_path) }}"
                                     onclick="changeImage('{{ asset('storage/' . $img->image_path) }}')"
                                     class="w-20 h-20 rounded-2xl cursor-pointer hover:scale-110 hover:ring-4 hover:ring-[#CFB53B] transition-all border-4 border-white shadow-xl object-cover bg-white"
                                     title="Foto Utama">
                            @endforeach

                            {{-- Foto per varian (sebagai tambahan setelah foto utama) --}}
                            @foreach($product->variants as $variant)
                                @if($variant->image)
                                    <div class="relative">
                                        <img src="{{ asset('storage/' . $variant->image) }}"
                                             onclick="changeImage('{{ asset('storage/' . $variant->image) }}')"
                                             class="w-20 h-20 rounded-2xl cursor-pointer hover:scale-110 hover:ring-4 hover:ring-indigo-400 transition-all border-4 border-white shadow-xl object-cover bg-white"
                                             title="Foto Varian: {{ $variant->color }} - {{ $variant->size }}">
                                        <span class="absolute -bottom-1 -right-1 bg-indigo-500 text-white text-[7px] font-black px-1.5 py-0.5 rounded-full uppercase">{{ $variant->size }}</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <p class="text-center text-[9px] text-indigo-300 mt-8 font-black uppercase tracking-[0.3em]">Interactive Preview • Click to Swap</p>
                    </div>
                </div>

                {{-- Product Info --}}
                <div class="lg:w-1/2 p-8 md:p-16">
                    <div class="space-y-10">
                        {{-- Title & ID --}}
                        <div>
                            <span class="text-indigo-400 font-black text-[10px] uppercase tracking-[0.4em] mb-2 block">Premium Collection</span>
                            <h2 class="text-5xl font-black text-indigo-950 uppercase italic tracking-tighter leading-none">{{ $product->name }}</h2>
                            <p class="text-indigo-300 text-xs mt-3 font-bold uppercase tracking-widest">SKU: #ADE-{{ $product->id }}</p>
                        </div>

                        {{-- Aggregate Info Card --}}
                        <div class="grid grid-cols-3 gap-4 p-6 bg-indigo-50/30 rounded-[2rem] border border-indigo-50/50">
                            <div>
                                <label class="text-[9px] font-black text-indigo-300 uppercase tracking-[0.2em] mb-2 block">Mulai Dari</label>
                                <p class="text-xl font-black text-[#CFB53B] font-serif italic">Rp {{ number_format($product->variants->min('price') ?? $product->price, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <label class="text-[9px] font-black text-indigo-300 uppercase tracking-[0.2em] mb-2 block">Total Stok</label>
                                <p class="text-xl font-black text-indigo-950 italic">{{ $product->variants->sum('stock') }} <span class="text-[10px] font-bold text-indigo-300 uppercase">Unit</span></p>
                            </div>
                            <div>
                                <label class="text-[9px] font-black text-indigo-300 uppercase tracking-[0.2em] mb-2 block">Varian</label>
                                <p class="text-xl font-black text-indigo-950">{{ $product->variants->count() }} <span class="text-[10px] font-bold text-indigo-300 uppercase">Tipe</span></p>
                            </div>
                        </div>

                        {{-- Categories & Status --}}
                        <div class="flex flex-col md:flex-row gap-8">
                            <div class="flex-1">
                                <label class="text-[9px] font-black text-indigo-300 uppercase tracking-[0.2em] mb-4 block">Categories</label>
                                <div class="flex flex-wrap gap-2">
                                    @forelse($product->categories as $category)
                                        <span class="px-4 py-2 bg-white text-indigo-600 text-[10px] rounded-xl font-black border border-indigo-100 shadow-sm uppercase tracking-widest">
                                            {{ $category->name }}
                                        </span>
                                    @empty
                                        <span class="text-gray-400 italic text-xs uppercase">Uncategorized</span>
                                    @endforelse
                                </div>
                            </div>
                            <div>
                                <label class="text-[9px] font-black text-indigo-300 uppercase tracking-[0.2em] mb-4 block">Store Status</label>
                                <span class="inline-flex items-center px-6 py-3 rounded-2xl font-black uppercase text-[10px] tracking-[0.15em] {{ $product->status == 'ready' ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-200' : 'bg-red-500 text-white shadow-lg shadow-red-200' }}">
                                    {{ $product->status == 'ready' ? 'Ready for Sale' : 'Sold Out' }}
                                </span>
                            </div>
                        </div>

                        {{-- Tabel Rincian Varian --}}
                        <div class="border-t border-gray-100 pt-8">
                            <label class="text-[9px] font-black text-indigo-300 uppercase tracking-[0.2em] mb-5 block">Rincian Per Varian</label>
                            <div class="overflow-hidden rounded-2xl border border-indigo-50">
                                <div class="overflow-x-auto">
                                    <table class="w-full text-left border-collapse min-w-[600px]">
                                        <thead class="bg-indigo-50/50">
                                            <tr>
                                                <th class="px-4 py-3 text-[9px] font-black text-indigo-400 uppercase tracking-widest">Varian</th>
                                                <th class="px-4 py-3 text-[9px] font-black text-indigo-400 uppercase tracking-widest">Harga</th>
                                                <th class="px-4 py-3 text-[9px] font-black text-indigo-400 uppercase tracking-widest">Berat</th>
                                                <th class="px-4 py-3 text-[9px] font-black text-indigo-400 uppercase tracking-widest text-center">Stok</th>
                                                <th class="px-4 py-3 text-[9px] font-black text-indigo-400 uppercase tracking-widest text-center">Status</th>
                                                <th class="px-4 py-3 text-[9px] font-black text-indigo-400 uppercase tracking-widest text-center">Foto</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-indigo-50">
                                            @forelse($product->variants as $variant)
                                                <tr class="hover:bg-[#F1FBFD]/50 transition-colors">
                                                    <td class="px-4 py-4">
                                                        <div class="flex flex-col">
                                                            <span class="text-xs font-black text-indigo-950 uppercase italic">{{ $variant->color }}</span>
                                                            <span class="text-[10px] font-bold text-indigo-400 uppercase">{{ $variant->size }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-4">
                                                        <span class="text-xs font-black text-[#CFB53B]">Rp {{ number_format($variant->price, 0, ',', '.') }}</span>
                                                    </td>
                                                    <td class="px-4 py-4">
                                                        <span class="text-xs font-bold text-indigo-800">{{ $variant->weight }}g</span>
                                                    </td>
                                                    <td class="px-4 py-4 text-center">
                                                        <span class="inline-flex items-center justify-center min-w-10 h-8 px-3 rounded-xl font-black text-xs {{ $variant->stock < 5 ? 'bg-red-50 text-red-600' : 'bg-indigo-50 text-indigo-600' }}">
                                                            {{ $variant->stock }}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-4 text-center">
                                                        <span class="inline-flex items-center px-3 py-1.5 rounded-xl font-black uppercase text-[9px] tracking-widest {{ $variant->status == 'ready' ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-500' }}">
                                                            {{ $variant->status == 'ready' ? '🟢 Ready' : '🔴 Sold Out' }}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-4 text-center">
                                                        @if($variant->image)
                                                            <img src="{{ asset('storage/' . $variant->image) }}"
                                                                 class="w-12 h-12 object-cover rounded-xl border-2 border-indigo-50 shadow-sm mx-auto cursor-pointer hover:scale-110 transition-transform"
                                                                 onclick="changeImage('{{ asset('storage/' . $variant->image) }}')"
                                                                 title="Klik untuk lihat">
                                                        @else
                                                            <span class="text-[10px] text-gray-300 italic">-</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="px-4 py-6 text-center text-xs italic text-gray-400">Tidak ada rincian varian.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="pt-6 border-t border-gray-100">
                            <label class="text-[9px] font-black text-indigo-300 uppercase tracking-[0.2em] mb-4 block">Product Essence</label>
                            <div class="relative">
                                <span class="absolute -top-4 -left-2 text-6xl text-indigo-50 font-serif">"</span>
                                <p class="relative z-10 text-indigo-900/70 leading-relaxed text-lg font-medium italic">
                                    {{ $product->description ?: 'Keanggunan dalam setiap jahitan. Koleksi eksklusif ini dirancang khusus untuk kenyamanan dan estetika terbaik.' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <p class="text-center mt-12 text-[10px] font-black text-indigo-200 uppercase tracking-[0.5em]">DAMandiri</p>
    </div>

    <script>
        function changeImage(path) {
            const mainImg = document.getElementById('mainImage');
            mainImg.style.opacity = '0';
            mainImg.style.transform = 'scale(0.95)';
            setTimeout(() => {
                mainImg.src = path;
                mainImg.style.opacity = '1';
                mainImg.style.transform = 'scale(1)';
            }, 300);
        }
    </script>
@endsection
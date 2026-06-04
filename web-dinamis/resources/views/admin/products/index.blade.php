@extends('layouts.admin')

@section('content')
    {{-- Header Content --}}
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="h-1 w-10 bg-[#CFB53B] rounded-full shadow-[0_0_10px_rgba(207,181,59,0.3)]"></span>
                <p class="text-indigo-400 text-xs font-black uppercase tracking-[0.2em]">Katalog Eksklusif</p>
            </div>
            <h1 class="text-4xl font-black text-indigo-950 tracking-tighter uppercase italic">Daftar Produk</h1>
            <p class="text-indigo-300 text-sm font-medium italic mt-1">
                Kelola stok dan koleksi terbaik <span class="text-[#CFB53B] font-bold">Ade Afwa Boutique</span> di sini.
            </p>
        </div>

        <a href="{{ route('admin.products.create') }}" 
           class="bg-[#CFB53B] hover:bg-[#b89f33] text-indigo-950 px-8 py-4 rounded-2xl font-black shadow-[0_10px_20px_rgba(207,181,59,0.2)] transition-all transform hover:-translate-y-1 flex items-center gap-3 text-xs uppercase tracking-widest">
            <div class="bg-indigo-950/10 p-1 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M12 4v16m8-8H4" />
                </svg>
            </div>
            Tambah Produk
        </a>
    </div>

    {{-- Alert Success --}}
    @if(session('success'))
        <div class="mb-8 p-5 bg-emerald-500/10 text-emerald-600 rounded-3xl border border-emerald-500/20 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="bg-emerald-500 text-white p-2 rounded-xl shadow-lg shadow-emerald-500/30">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div>
                    <p class="font-black text-sm uppercase tracking-tight">Berhasil!</p>
                    <p class="text-xs opacity-80 italic">{{ session('success') }}</p>
                </div>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-600/50 hover:text-emerald-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    {{-- Table Card --}}
    <div class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.05)] overflow-hidden border border-gray-100/50">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50/50 border-b border-gray-100">
                    <tr>
                        <th class="p-8 text-[10px] font-black text-indigo-900/40 uppercase tracking-[0.2em]">Koleksi</th>
                        <th class="p-8 text-[10px] font-black text-indigo-900/40 uppercase tracking-[0.2em]">Detail & Kategori</th>
                        <th class="p-8 text-[10px] font-black text-indigo-900/40 uppercase tracking-[0.2em]">Keterangan Varian</th>
                        <th class="p-8 text-[10px] font-black text-indigo-900/40 uppercase tracking-[0.2em] text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($products as $p)
                    <tr class="hover:bg-indigo-50/30 transition-all duration-300 group">
                        {{-- Kolom Foto --}}
                        <td class="p-8 w-32">
                            <div class="relative inline-block">
                                <img src="{{ asset('storage/' . $p->image) }}" class="w-24 h-24 rounded-[1.5rem] object-cover shadow-xl group-hover:scale-105 transition-transform duration-500">
                                @php $totalStock = $p->variants->sum('stock'); @endphp
                                @if($totalStock < 5 && $totalStock > 0)
                                    <span class="absolute -top-3 -right-3 bg-orange-500 text-white text-[9px] font-black px-3 py-1.5 rounded-full uppercase shadow-lg border-2 border-white animate-pulse">Low Stock</span>
                                @elseif($totalStock <= 0)
                                    <span class="absolute -top-3 -right-3 bg-red-600 text-white text-[9px] font-black px-3 py-1.5 rounded-full uppercase shadow-lg border-2 border-white">Empty</span>
                                @endif
                            </div>
                        </td>

                        {{-- Kolom Detail & Kategori --}}
                        <td class="p-8 align-top">
                            <span class="font-black text-gray-900 block text-xl tracking-tighter group-hover:text-indigo-600 transition-colors leading-tight">{{ $p->name }}</span>
                            <span class="text-[10px] text-indigo-300 font-bold tracking-[0.1em] mt-1 block uppercase mb-3">ID: #PROD-{{ $p->id }}</span>
                            
                            <div class="flex flex-wrap gap-1.5">
                                @forelse($p->categories as $category)
                                    <span class="px-3 py-1 bg-indigo-50 text-indigo-600 text-[9px] rounded-lg font-black uppercase border border-indigo-100 shadow-sm">
                                        {{ $category->name }}
                                    </span>
                                @empty
                                    <span class="text-[10px] text-gray-400 italic font-medium">Tanpa Kategori</span>
                                @endforelse
                            </div>
                        </td>

                        {{-- Kolom Keterangan Varian (Nama, Stok, Status, Harga) --}}
                        <td class="p-8">
                            <div class="bg-gray-50/50 rounded-[1.5rem] p-5 border border-gray-100 shadow-inner min-w-[280px]">
                                <div class="space-y-4">
                                    @forelse($p->variants as $variant)
                                        <div class="flex items-center justify-between gap-6 pb-3 border-b border-dashed border-gray-200 last:border-0 last:pb-0">
                                            <div class="flex flex-col">
                                                {{-- NAMA VARIAN (Mengambil dari kolom color & size) --}}
                                                <div class="flex items-center gap-2 mb-1.5">
                                                    <span class="w-1.5 h-1.5 bg-[#CFB53B] rounded-full"></span>
                                                    <span class="text-xs font-black text-indigo-950 uppercase tracking-widest">
                                                        {{ $variant->color }} {{ $variant->size ? '('.$variant->size.')' : '' }}
                                                    </span>
                                                </div>
                                                
                                                <div class="flex items-center gap-2">
                                                    {{-- LOGIC STATUS PERBAIKAN (Mengecek 'sold_out' dan stok 0) --}}
                                                    @php 
                                                        $statusDB = strtolower($variant->status);
                                                        $isSoldOut = ($statusDB == 'sold_out' || $statusDB == 'sold out' || $variant->stock <= 0);
                                                    @endphp

                                                    @if(!$isSoldOut)
                                                        <span class="px-2 py-0.5 bg-emerald-50 text-emerald-600 text-[8px] font-black rounded-md uppercase border border-emerald-100">
                                                            Ready
                                                        </span>
                                                        <span class="text-[10px] text-indigo-400 font-bold">
                                                            Stok: <span class="text-indigo-900">{{ $variant->stock }}</span>
                                                        </span>
                                                    @else
                                                        <span class="px-2 py-0.5 bg-red-50 text-red-600 text-[8px] font-black rounded-md uppercase border border-red-100">
                                                            Sold Out
                                                        </span>
                                                        <span class="text-[10px] text-red-400 font-bold italic">
                                                            Stok: {{ $variant->stock }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            {{-- HARGA --}}
                                            <div class="text-right">
                                                <p class="text-[8px] text-gray-400 font-bold uppercase">Price</p>
                                                <span class="font-black text-[#CFB53B] text-sm font-serif italic">
                                                    Rp {{ number_format($variant->price, 0, ',', '.') }}
                                                </span>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-2">
                                            <p class="text-[10px] text-gray-400 font-bold italic uppercase">Varian Kosong</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </td>

                        {{-- Kolom Aksi --}}
                        <td class="p-8">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('admin.products.show', $p->id) }}" class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition shadow-sm" title="Detail">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                <a href="{{ route('admin.products.edit', $p->id) }}" class="p-2.5 bg-amber-50 text-amber-600 rounded-xl hover:bg-amber-500 hover:text-white transition shadow-sm" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <form action="{{ route('admin.products.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2.5 bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition shadow-sm" title="Hapus">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@extends('layouts.admin')

@section('content')
    <div class="max-w-7xl mx-auto">
        {{-- Header & Success Indicator --}}
        <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-4xl font-black text-indigo-950 tracking-tighter uppercase italic leading-none">Pesanan Selesai</h1>
                <p class="text-indigo-400 font-medium italic mt-2">
                    Daftar seluruh transaksi yang telah berhasil dikirim dan diterima oleh pelanggan.
                </p>
            </div>
            
            <div class="bg-emerald-50 px-8 py-4 rounded-[1.5rem] border border-emerald-100 shadow-[0_10px_30px_rgba(16,185,129,0.05)] flex items-center gap-4 align-middle self-start md:self-auto">
                <div class="bg-emerald-500 text-white w-8 h-8 rounded-full flex items-center justify-center text-xs shadow-lg shadow-emerald-200 font-black">
                    ✓
                </div>
                <span class="text-[10px] font-black text-emerald-700 uppercase tracking-[0.2em]">
                    Status: <span class="opacity-75">Completed</span>
                </span>
            </div>
        </div>

        {{-- Filter System Card --}}
        <div class="bg-white rounded-[2rem] p-6 mb-8 border border-gray-100 shadow-[0_15px_40px_rgba(0,0,0,0.01)]">
            <form method="GET" action="{{ request()->url() }}" class="flex flex-col lg:flex-row items-end lg:items-center justify-between gap-6">
                <div class="flex flex-wrap items-center gap-4 w-full lg:w-auto">
                    {{-- Jenis Filter --}}
                    <div class="flex flex-col gap-1.5 shadow-sm rounded-xl p-1 bg-indigo-50/50 border border-indigo-100/50">
                        <div class="flex gap-1">
                            <button type="button" onclick="switchFilter('day')" id="btn-filter-day" 
                                class="px-5 py-2 rounded-lg text-[10px] font-black uppercase tracking-wider transition-all duration-300">
                                📅 Harian
                            </button>
                            <button type="button" onclick="switchFilter('month')" id="btn-filter-month" 
                                class="px-5 py-2 rounded-lg text-[10px] font-black uppercase tracking-wider transition-all duration-300">
                                🗓️ Bulanan
                            </button>
                        </div>
                        <input type="hidden" name="filter_type" id="filter_type" value="{{ request('filter_type', 'day') }}">
                    </div>

                    {{-- Input Tanggal (Harian) --}}
                    <div id="wrapper-date-input" class="flex flex-col gap-1">
                        <span class="text-[9px] text-indigo-400 font-black uppercase tracking-wider pl-1">Pilih Tanggal</span>
                        <input type="date" name="date" value="{{ request('date', date('Y-m-d')) }}" 
                            class="bg-[#F1FBFD] border border-indigo-50 rounded-xl px-4 py-2 text-[11px] font-bold text-indigo-950 focus:ring-1 focus:ring-[#CFB53B] outline-none shadow-inner">
                    </div>

                    {{-- Input Bulan (Bulanan) --}}
                    <div id="wrapper-month-input" class="flex flex-col gap-1 hidden">
                        <span class="text-[9px] text-indigo-400 font-black uppercase tracking-wider pl-1">Pilih Bulan</span>
                        <input type="month" name="month" value="{{ request('month', date('Y-m')) }}" 
                            class="bg-[#F1FBFD] border border-indigo-50 rounded-xl px-4 py-2 text-[11px] font-bold text-indigo-950 focus:ring-1 focus:ring-[#CFB53B] outline-none shadow-inner">
                    </div>

                    {{-- Filter Status --}}
                    <div class="flex flex-col gap-1">
                        <span class="text-[9px] text-indigo-400 font-black uppercase tracking-wider pl-1">Status Pesanan</span>
                        <select name="status" class="bg-[#F1FBFD] border border-indigo-50 rounded-xl px-4 py-2 text-[11px] font-bold text-indigo-950 focus:ring-1 focus:ring-[#CFB53B] outline-none shadow-inner">
                            <option value="">Semua Status</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                </div>

                {{-- Aksi Tombol Filter --}}
                <div class="flex items-center gap-2 w-full lg:w-auto justify-end">
                    @if(request()->has('filter_type'))
                        <a href="{{ route(request()->route()->getName()) }}" 
                            class="bg-gray-100 text-gray-500 hover:bg-gray-200 px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                            Reset
                        </a>
                    @endif
                    <button type="submit" 
                        class="bg-indigo-950 text-white hover:bg-indigo-900 px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-md active:scale-95 flex items-center gap-2">
                        🔍 Terapkan Filter
                    </button>
                </div>
            </form>
        </div>

        {{-- Table Archive Card --}}
        <div class="bg-white rounded-[2.5rem] shadow-[0_40px_80px_rgba(0,0,0,0.02)] border border-white overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-indigo-50/30 text-indigo-900 uppercase text-[10px] font-black tracking-[0.25em]">
                            <th class="px-8 py-6 align-middle">ID Order</th>
                            <th class="px-8 py-6 align-middle">Pelanggan & Alamat</th>
                            <th class="px-8 py-6 align-middle">Item Koleksi</th>
                            <th class="px-8 py-6 align-middle">Total Tagihan</th>
                            <th class="px-8 py-6 align-middle">Informasi Resi</th>
                            <th class="px-8 py-6 text-center align-middle">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($orders as $order)
                        <tr class="hover:bg-emerald-50/10 transition-colors group">
                            {{-- ID Order --}}
                            <td class="px-8 py-6 align-middle">
                                <div class="flex flex-col">
                                    <span class="font-black text-indigo-950 tracking-tighter text-lg">#{{ $order->id }}</span>
                                    <p class="text-[9px] text-indigo-300 font-bold mt-1 uppercase">{{ $order->created_at->format('d M Y') }}</p>
                                    <p class="text-[9px] text-gray-400 font-medium">{{ $order->created_at->format('H:i') }} WIB</p>
                                </div>
                            </td>

                            {{-- Pelanggan & Alamat --}}
                            <td class="px-8 py-6 align-top">
                                <div class="flex flex-col gap-1.5 py-1">
                                    <span class="font-black text-indigo-950 uppercase tracking-tighter text-sm">
                                        {{ $order->recipient_name ?? ($order->user->name ?? 'User Anonim') }}
                                    </span>
                                    <span class="text-[10px] text-indigo-400 font-bold">
                                        Pemesan: {{ $order->user->name ?? '-' }} ({{ $order->user->email ?? '-' }})
                                    </span>
                                    <span class="inline-flex items-center text-[10px] text-indigo-500 font-black">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1 text-[#CFB53B]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                        {{ $order->phone ?? ($order->user->phone ?? '-') }}
                                    </span>
                                    <span class="text-[10px] text-gray-500 font-medium leading-relaxed max-w-[220px] break-words mt-0.5">
                                        📍 {{ $order->address_details ?? '-' }}
                                    </span>
                                </div>
                            </td>

                            {{-- Item Koleksi --}}
                            <td class="px-8 py-6 align-middle">
                                <div class="space-y-2 my-1">
                                    @foreach($order->items as $item)
                                        <div class="bg-indigo-50/30 p-2.5 rounded-xl border border-indigo-100/30 flex flex-col gap-0.5 max-w-[240px]">
                                            <span class="text-[11px] font-bold text-indigo-950 uppercase italic">
                                                {{ $item->product->name ?? 'Produk' }}
                                            </span>
                                            @if($item->variant)
                                                <span class="text-[9px] text-[#CFB53B] font-black uppercase tracking-wider">
                                                    Varian: {{ $item->variant->name ?? ($item->variant->color . ' ' . $item->variant->size) }}
                                                </span>
                                            @endif
                                            <div class="flex justify-between items-center text-[10px] text-gray-400 mt-1 font-semibold gap-4">
                                                <span>{{ $item->qty }}x @ Rp{{ number_format($item->price, 0, ',', '.') }}</span>
                                                <span class="font-black text-indigo-900">Rp{{ number_format($item->price * $item->qty, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </td>

                            {{-- Total Tagihan --}}
                            <td class="px-8 py-6 align-middle">
                                <div class="flex flex-col gap-1 min-w-[150px]">
                                    <div class="text-[10px] text-gray-400 font-bold uppercase tracking-wider flex justify-between gap-4">
                                        <span>Subtotal:</span>
                                        <span class="text-gray-700 font-bold">Rp {{ number_format($order->total_price - ($order->shipping_cost ?? 0), 0, ',', '.') }}</span>
                                    </div>
                                    @if(($order->shipping_cost ?? 0) > 0)
                                        <div class="text-[10px] text-gray-400 font-bold uppercase tracking-wider flex justify-between gap-4">
                                            <span>Ongkir:</span>
                                            <span class="text-gray-700 font-bold">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                                        </div>
                                    @endif
                                    <div class="border-t border-dashed border-gray-200 my-1"></div>
                                    <div class="flex flex-col items-start md:items-end">
                                        <span class="text-[9px] text-indigo-400 font-black uppercase tracking-[0.15em] mb-1 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-100">
                                            💳 {{ strtoupper($order->payment_method) }}
                                        </span>
                                        <span class="text-lg font-black text-[#CFB53B] tracking-tighter">
                                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            {{-- Informasi Resi --}}
                            <td class="px-8 py-6 align-middle">
                                <div class="inline-flex flex-col sm:flex-row items-start sm:items-center gap-1.5 bg-indigo-50/50 px-4 py-2.5 rounded-xl border border-indigo-100/30 shadow-inner group-hover:bg-white transition-colors min-w-[165px]">
                                    <span class="text-[9px] text-indigo-400 font-black tracking-widest uppercase">No. Resi:</span>
                                    <span class="font-mono text-xs font-black text-indigo-950 tracking-wide">
                                        {{ $order->resi ?? 'N/A' }}
                                    </span>
                                </div>
                            </td>

                            {{-- Status Badge --}}
                            <td class="px-8 py-6 text-center align-middle">
                                <div class="flex justify-center min-w-[100px]">
                                    @if($order->status_shipping == 'cancelled')
                                        <span class="inline-block px-4 py-2 bg-red-50 text-red-600 border border-red-100 rounded-xl text-[9px] font-black uppercase tracking-widest shadow-sm">
                                            Batal
                                        </span>
                                    @else
                                        <span class="inline-block px-4 py-2 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-xl text-[9px] font-black uppercase tracking-widest shadow-sm">
                                            Delivered
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-10 py-32 text-center align-middle">
                                <div class="flex flex-col items-center">
                                    <div class="w-24 h-24 bg-indigo-50 rounded-full flex items-center justify-center mb-6">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-black text-indigo-950 uppercase italic tracking-tighter">Tidak Ada Rekaman</h3>
                                    <p class="text-indigo-300 font-bold uppercase tracking-widest text-[10px] mt-2">
                                        Tidak ditemukan transaksi selesai pada filter periode ini.
                                    </p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Footer Brand --}}
        <footer class="mt-16 text-center">
            <div class="inline-block p-1 bg-gradient-to-r from-transparent via-indigo-100 to-transparent w-full h-px mb-6"></div>
            <p class="text-[9px] text-indigo-200 font-black uppercase tracking-[0.6em] italic">
                &copy; 2026 ADE AFWA BOUTIQUE 
            </p>
        </footer>
    </div>

    {{-- Script Engine Filter Saklar Otomatis --}}
    <script>
        function switchFilter(type) {
            const inputDay = document.getElementById('wrapper-date-input');
            const inputMonth = document.getElementById('wrapper-month-input');
            const hiddenType = document.getElementById('filter_type');
            
            const btnDay = document.getElementById('btn-filter-day');
            const btnMonth = document.getElementById('btn-filter-month');

            hiddenType.value = type;

            if (type === 'day') {
                inputDay.classList.remove('hidden');
                inputMonth.classList.add('hidden');
                
                // Desain Aktif Harian
                btnDay.className = "px-5 py-2 rounded-lg text-[10px] font-black uppercase tracking-wider transition-all duration-300 bg-indigo-950 text-white shadow-sm";
                btnMonth.className = "px-5 py-2 rounded-lg text-[10px] font-black uppercase tracking-wider transition-all duration-300 text-indigo-400 hover:text-indigo-950";
            } else {
                inputDay.classList.add('hidden');
                inputMonth.classList.remove('hidden');
                
                // Desain Aktif Bulanan
                btnMonth.className = "px-5 py-2 rounded-lg text-[10px] font-black uppercase tracking-wider transition-all duration-300 bg-indigo-950 text-white shadow-sm";
                btnDay.className = "px-5 py-2 rounded-lg text-[10px] font-black uppercase tracking-wider transition-all duration-300 text-indigo-400 hover:text-indigo-950";
            }
        }

        // Jalankan inisialisasi status filter saat halaman pertama kali dibuka
        document.addEventListener("DOMContentLoaded", function() {
            const currentFilter = "{{ request('filter_type', 'day') }}";
            switchFilter(currentFilter);
        });
    </script>
@endsection
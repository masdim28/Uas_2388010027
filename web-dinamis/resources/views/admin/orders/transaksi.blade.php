@extends('layouts.admin')

@section('content')
    <div class="max-w-7xl mx-auto">
        {{-- Header & Warning Indicator --}}
        <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-4xl font-black text-indigo-950 tracking-tighter uppercase italic leading-none">Transaksi Pending</h1>
                <p class="text-indigo-400 font-medium italic mt-2">
                    Segera verifikasi bukti pembayaran agar pesanan koleksi butik bisa diproses ke tahap pengemasan.
                </p>
            </div>
            
            <div class="flex items-center gap-4 bg-white px-8 py-4 rounded-[1.5rem] border border-amber-100 shadow-[0_10px_30px_rgba(251,191,36,0.05)] self-start md:self-auto">
                <div class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500"></span>
                </div>
                <span class="text-[10px] font-black text-amber-700 uppercase tracking-[0.2em]">
                    Status: <span class="text-amber-600">Menunggu Verifikasi</span>
                </span>
            </div>
        </div>

        {{-- Transactions Table Card --}}
        <div class="bg-white rounded-[2.5rem] shadow-[0_40px_80px_rgba(0,0,0,0.02)] border border-white overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-indigo-50/30 text-indigo-900 uppercase text-[10px] font-black tracking-[0.25em]">
                            <th class="px-8 py-6 align-middle">Order ID</th>
                            <th class="px-8 py-6 align-middle">Pelanggan & Alamat</th>
                            <th class="px-8 py-6 align-middle">Item Koleksi</th>
                            <th class="px-8 py-6 align-middle">Total Tagihan</th>
                            <th class="px-10 py-6 text-center align-middle">Verifikasi Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($orders as $order)
                        <tr class="hover:bg-[#F1FBFD]/40 transition-colors group">
                            {{-- Order ID --}}
                            <td class="px-8 py-6 align-middle">
                                <div class="flex flex-col">
                                    <span class="font-black text-indigo-950 tracking-tighter text-lg group-hover:text-amber-600 transition-colors">#{{ $order->id }}</span>
                                    <p class="text-[9px] text-indigo-300 font-bold uppercase tracking-widest mt-1 italic">UID-{{ str_pad($order->user_id, 4, '0', STR_PAD_LEFT) }}</p>
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
                                <div class="flex flex-col gap-1 min-w-[160px]">
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
                                            A/N QRIS {{ strtoupper($order->payment_method) }}
                                        </span>
                                        <span class="text-lg font-black text-[#CFB53B] tracking-tighter">
                                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            {{-- Verifikasi Pembayaran --}}
                            <td class="px-10 py-6 align-middle">
                                <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                                    <form action="{{ route('admin.orders.konfirmasi', $order->id) }}" method="POST" class="w-full sm:w-auto">
                                        @csrf
                                        <button class="w-full bg-indigo-950 text-white px-5 py-3 rounded-xl text-[9px] font-black uppercase tracking-[0.15em] hover:bg-[#CFB53B] hover:text-indigo-950 transition-all active:scale-95 shadow-lg shadow-indigo-900/10 whitespace-nowrap">
                                            Konfirmasi Bayar
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.orders.batal', $order->id) }}" method="POST" target="_blank" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan transaksi ini?')" class="w-full sm:w-auto">
                                        @csrf 
                                        @method('DELETE')
                                        <button class="w-full bg-white text-red-500 border border-red-200 px-5 py-3 rounded-xl text-[9px] font-black uppercase tracking-[0.15em] hover:bg-red-50 hover:text-red-700 hover:border-red-300 transition-all active:scale-95 shadow-sm whitespace-nowrap">
                                            Batalkan
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-10 py-32 text-center align-middle">
                                <div class="flex flex-col items-center">
                                    <div class="text-5xl mb-6 opacity-10 font-black italic tracking-tighter text-indigo-950">NO INCOMING</div>
                                    <h3 class="text-lg font-black text-indigo-900 uppercase italic">Arus Kas Bersih</h3>
                                    <p class="text-indigo-300 font-bold uppercase tracking-widest text-[10px] mt-2">Tidak ada transaksi tertunda yang perlu diverifikasi.</p>
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
            <div class="inline-block p-1 bg-gradient-to-r from-transparent via-indigo-50 to-transparent w-full h-px mb-6"></div>
            <p class="text-[9px] text-indigo-200 font-black uppercase tracking-[0.6em] italic">
                &copy; 2026 ADE AFWA BOUTIQUE 
            </p>
        </footer>
    </div>
@endsection
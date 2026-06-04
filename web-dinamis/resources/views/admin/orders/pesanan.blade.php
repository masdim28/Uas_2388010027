@extends('layouts.admin')

@section('content')
    <div class="max-w-7xl mx-auto">
        {{-- Header & Status Indicator --}}
        <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-4xl font-black text-indigo-950 tracking-tighter uppercase italic leading-none">Pesanan Diproses</h1>
                <p class="text-indigo-400 font-medium italic mt-2">
                    Segera input nomor resi untuk pesanan yang sudah siap dikirim ke pelanggan.
                </p>
            </div>
            
            <div class="flex items-center gap-4 bg-white px-8 py-4 rounded-[1.5rem] border border-blue-50 shadow-[0_10px_30px_rgba(0,0,0,0.02)] self-start md:self-auto">
                <div class="relative flex">
                    <div class="bg-blue-500 h-3 w-3 rounded-full animate-ping absolute opacity-75"></div>
                    <div class="bg-blue-600 h-3 w-3 rounded-full relative"></div>
                </div>
                <span class="text-[10px] font-black text-indigo-900 uppercase tracking-[0.2em]">
                    Logistik: <span class="text-blue-600">Siap Kirim</span>
                </span>
            </div>
        </div>

        {{-- Main Table Card --}}
        <div class="bg-white rounded-[2.5rem] shadow-[0_40px_80px_rgba(0,0,0,0.03)] border border-white overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-indigo-50/30 text-indigo-900 uppercase text-[10px] font-black tracking-[0.25em]">
                            <th class="px-8 py-6 align-middle">ID Order</th>
                            <th class="px-8 py-6 align-middle">Pelanggan & Alamat</th>
                            <th class="px-8 py-6 align-middle">Item Koleksi</th>
                            <th class="px-8 py-6 align-middle">Total Tagihan</th>
                            <th class="px-8 py-6 text-center align-middle">Status</th>
                            <th class="px-8 py-6 align-middle">Kelola Pengiriman</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($orders as $order)
                        <tr class="hover:bg-[#F1FBFD]/40 transition-colors group">
                            {{-- ID Order --}}
                            <td class="px-8 py-6 align-middle">
                                <div class="flex flex-col">
                                    <span class="font-black text-indigo-950 tracking-tighter text-lg group-hover:text-amber-600 transition-colors">#{{ $order->id }}</span>
                                    <p class="text-[9px] text-indigo-300 font-bold mt-1 uppercase">{{ $order->created_at->format('d M Y') }}</p>
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

                            {{-- Status Badge --}}
                            <td class="px-8 py-6 text-center align-middle">
                                <span class="inline-flex items-center px-4 py-2 {{ $order->status_shipping == 'processing' ? 'bg-amber-50 text-amber-600 border-amber-100' : 'bg-blue-50 text-blue-600 border-blue-100' }} border rounded-xl text-[9px] font-black uppercase tracking-widest shadow-sm">
                                    {{ $order->status_shipping }}
                                </span>
                            </td>

                            {{-- Kelola Pengiriman --}}
                            <td class="px-8 py-6 align-middle">
                                <div class="flex flex-col gap-3 min-w-[180px]">
                                    <a href="{{ route('admin.orders.print', $order->id) }}" target="_blank" class="text-center bg-[#CFB53B] text-white px-4 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-yellow-600 transition-all shadow-sm flex items-center justify-center gap-1.5 active:scale-95">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                        Print Info
                                    </a>

                                    @if($order->status_shipping == 'processing')
                                        {{-- PERBAIKAN: Form sekarang diproses via AJAX (id ditambahkan untuk hook js) --}}
                                        <form id="form-resi-{{ $order->id }}" action="{{ route('admin.orders.resi', $order->id) }}" method="POST" 
                                              onsubmit="prosesKirimDanWa(event, '{{ $order->phone ?? ($order->user->phone ?? '') }}', '{{ $order->recipient_name ?? ($order->user->name ?? '') }}', '{{ $order->id }}')" 
                                              class="flex flex-col gap-2">
                                            @csrf
                                            <input type="text" id="resi-{{ $order->id }}" name="resi" placeholder="Masukkan Nomor Resi" 
                                                class="w-full bg-[#F1FBFD] border border-indigo-100 rounded-xl px-3 py-2.5 text-[10px] font-bold text-indigo-950 focus:ring-1 focus:ring-[#CFB53B] outline-none shadow-inner placeholder:text-indigo-200 placeholder:italic" 
                                                required>
                                            <button type="submit" 
                                                class="bg-indigo-950 text-white px-4 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-indigo-900 transition-all shadow-md active:scale-95">
                                                Kirim Pesanan
                                            </button>
                                        </form>
                                    @elseif($order->status_shipping == 'shipped')
                                        <div class="bg-blue-50 border border-blue-100 p-2.5 rounded-xl text-center">
                                            <p class="text-[8px] text-blue-400 uppercase font-bold mb-0.5">Nomor Resi</p>
                                            <p class="text-xs text-blue-700 font-black tracking-widest">{{ $order->resi }}</p>
                                        </div>
                                        <p class="text-[8px] text-center text-gray-400 italic leading-tight">Menunggu pembeli<br>menerima pesanan</p>
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
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-black text-indigo-900 uppercase italic">Logistik Bersih</h3>
                                    <p class="text-indigo-300 font-bold uppercase tracking-widest text-[10px] mt-2">Semua pesanan saat ini sudah dikirim ke pelanggan.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Footer --}}
        <footer class="mt-16 text-center">
            <div class="inline-block p-1 bg-gradient-to-r from-transparent via-indigo-100 to-transparent w-full h-px mb-6"></div>
            <p class="text-[9px] text-indigo-200 font-black uppercase tracking-[0.6em] italic">
                &copy; 2026 ADE AFWA BOUTIQUE 
            </p>
        </footer>
    </div>

    {{-- Script AJAX & Otomatis Buka WhatsApp --}}
    <script>
        function prosesKirimDanWa(event, phone, nama, orderId) {
            // 1. Hentikan submit form normal bawaan HTML agar tab awal tidak redirect/berubah
            event.preventDefault();

            const form = document.getElementById('form-resi-' + orderId);
            const resiInput = document.getElementById('resi-' + orderId).value;
            const formData = new FormData(form);

            // 2. Bersihkan dan format nomor HP pelanggan untuk WA
            let formattedPhone = phone.replace(/[^0-9]/g, '');
            if (formattedPhone.startsWith('0')) {
                formattedPhone = '62' + formattedPhone.slice(1);
            }

            // 3. Susun isi template pesan teks WA
            const pesan = `Halo Kak ${nama},\n\nPesanan koleksi dari *ADE AFWA BOUTIQUE* dengan *ID Order #${orderId}* telah selesai kami kemas dan diserahkan ke kurir logistik.\n\nBerikut merupakan nomor resi pengiriman Kakak:\n📦 *${resiInput}*\n\nTerima kasih telah berbelanja di butik kami, ditunggu kedatangan koleksi selanjutnya! ✨`;
            const waUrl = `https://api.whatsapp.com/send?phone=${formattedPhone}&text=${encodeURIComponent(pesan)}`;

            // 4. Buka WhatsApp di TAB BARU terlebih dahulu
            window.open(waUrl, '_blank');

            // 5. Kirim data form resi ke server Laravel via background (AJAX Fetch)
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                // Setelah server berhasil menyimpan resi, muat ulang halaman admin utama
                window.location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
                // Fallback jika koneksi gagal, tetap paksa refresh halaman admin
                window.location.reload();
            });
        }
    </script>
@endsection
<div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 mb-6">
    <div class="flex justify-between items-center border-b pb-4 mb-4">
        <div>
            <p class="text-xs text-gray-500">Tanggal Pesanan</p>
            <p class="font-semibold text-sm">{{ $order->created_at->format('d M Y, H:i') }}</p>
        </div>
        <div class="text-right">
            @php
                $payClass = 'bg-yellow-100 text-yellow-700';
                $payText = 'BELUM DIBAYAR';
                if ($order->status_payment == 'paid') {
                    $payClass = 'bg-green-100 text-green-700';
                    $payText = 'SUDAH DIBAYAR';
                } elseif ($order->status_payment == 'cancelled' || $order->status_shipping == 'cancelled') {
                    $payClass = 'bg-red-100 text-red-700';
                    $payText = 'DIBATALKAN';
                }
            @endphp
            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $payClass }}">
                {{ $payText }}
            </span>
            @php
                $shipClass = 'bg-gray-100 text-gray-700';
                $shipText = 'PENDING';
                if ($order->status_shipping == 'completed') {
                    $shipClass = 'bg-green-100 text-green-700';
                    $shipText = 'SELESAI';
                } elseif ($order->status_shipping == 'shipped') {
                    $shipClass = 'bg-blue-100 text-blue-700';
                    $shipText = 'DALAM PENGIRIMAN';
                } elseif ($order->status_shipping == 'processing' || ($order->status_payment == 'paid' && $order->status_shipping == 'pending')) {
                    $shipClass = 'bg-orange-100 text-orange-700';
                    $shipText = 'SEDANG DIKEMAS';
                } elseif ($order->status_shipping == 'cancelled' || $order->status_payment == 'cancelled') {
                    $shipClass = 'bg-red-100 text-red-700';
                    $shipText = 'DIBATALKAN';
                }
            @endphp
            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $shipClass }} ml-2">
                {{ $shipText }}
            </span>
        </div>
    </div>

    <div class="space-y-4">
        @foreach($order->items as $item)
            <div class="flex justify-between items-center text-sm">
                <div class="flex items-center space-x-4">
                    @if($item->product && $item->product->image)
                        <img src="{{ asset('storage/' . $item->product->image) }}" class="w-16 h-16 object-cover rounded shadow-sm">
                    @else
                        <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center text-xs text-gray-400">No Img</div>
                    @endif
                    <div>
                        <p class="font-semibold text-gray-800">{{ $item->product ? $item->product->name : 'Produk Dihapus' }}</p>
                        <p class="text-xs text-gray-500">{{ $item->qty }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                    </div>
                </div>
                <p class="font-bold text-gray-800">
                    Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}
                </p>
            </div>
        @endforeach
    </div>

    <div class="border-t pt-4 mt-4 flex justify-between items-center">
        <div>
            <p class="text-xs text-gray-500 mb-1">Kurir: <span class="font-semibold text-gray-800">{{ strtoupper($order->courier ?? '-') }}</span></p>
            <p class="text-xs text-gray-500 mb-1">Metode Pembayaran: <span class="font-semibold text-gray-800">{{ $order->payment_method ?? '-' }}</span></p>
            @if($order->resi && $order->status_shipping == 'shipped')
                <p class="text-sm text-blue-700 bg-blue-50 border border-blue-100 px-3 py-1.5 rounded-md inline-block mt-2 shadow-sm">
                    No Resi: <span class="font-bold tracking-wider">{{ $order->resi }}</span>
                </p>
            @endif
        </div>
        <div class="text-right flex flex-col items-end">
            <p class="text-xs text-gray-500 mb-1">Total Pesanan</p>
            <p class="text-lg font-bold text-[#CFB53B]">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
            
            @if($order->status_payment == 'pending')
                <a href="{{ route('orders.payment', $order->id) }}" class="inline-block mt-3 bg-[#CFB53B] text-white px-5 py-2 rounded-lg text-xs font-bold shadow-md hover:bg-yellow-600 transition">Bayar Sekarang</a>
            @elseif($order->status_shipping == 'shipped')
                <form action="{{ route('orders.receive', $order->id) }}" method="POST" class="mt-3" onsubmit="return confirm('Apakah Anda yakin sudah menerima pesanan ini?');">
                    @csrf
                    <button type="submit" class="bg-green-600 text-white px-5 py-2 rounded-lg text-xs font-bold shadow-md hover:bg-green-700 transition flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Pesanan Diterima
                    </button>
                </form>
                <p class="text-[10px] text-gray-400 mt-1 max-w-[200px] text-right leading-tight">Otomatis selesai dalam 10 hari sejak dikirim.</p>
            @endif
        </div>
    </div>
</div>

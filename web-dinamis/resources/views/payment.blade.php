<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - Ade Afwa Boutique</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #FDF9F0; }
        .font-serif-ade { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="text-gray-900 min-h-screen flex flex-col justify-between">

    <nav class="bg-[#FAF8F5] shadow-sm border-b border-gray-100 py-4">
        <div class="max-w-6xl mx-auto px-4 flex justify-between items-center">
            <a href="{{ route('home') }}">
                <img src="{{ asset('images/logo_adeafwa.png') }}" alt="Ade Afwa Boutique" class="h-10 object-contain">
            </a>
            <span class="text-[10px] font-bold tracking-[0.2em] text-[#CFB53B] uppercase">Pembayaran</span>
        </div>
    </nav>

    <main class="max-w-3xl mx-auto py-10 px-4 w-full flex-grow">
        <h2 class="font-serif-ade text-3xl font-bold text-gray-800 mb-8 text-center tracking-wider">DETAIL PEMBAYARAN</h2>

        <div class="bg-white rounded-xl shadow-md p-8 border border-gray-100 text-center">
            <p class="text-sm text-gray-500 mb-2">Total Tagihan Anda</p>
            <p class="text-4xl font-bold text-[#CFB53B] mb-8">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>

            <div class="border-t border-gray-200 pt-6 mb-6 text-left">
                <h3 class="font-bold text-gray-800 mb-4">Instruksi Pembayaran</h3>
                
                @if($order->paymentMethod)
                    <p class="text-sm text-gray-600 mb-4">Metode Terpilih: <span class="font-bold">{{ $order->paymentMethod->name }}</span></p>

                    @if($order->paymentMethod->type == 'bank_transfer' || $order->paymentMethod->type == 'ewallet')
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 inline-block text-left w-full">
                            <p class="text-xs text-gray-500 mb-1">Nomor Rekening / Tujuan</p>
                            <p class="text-lg font-bold text-gray-800 tracking-wider">{{ $order->paymentMethod->account_number }}</p>
                            <p class="text-sm text-gray-600 mt-1">Atas Nama: <span class="font-bold">{{ $order->paymentMethod->account_name }}</span></p>
                        </div>
                    @elseif($order->paymentMethod->type == 'qris')
                        <div class="flex flex-col items-center">
                            @if($order->paymentMethod->qr_code)
                                <img src="{{ asset('storage/' . $order->paymentMethod->qr_code) }}" alt="QRIS" class="max-w-[250px] border p-2 rounded shadow-sm mb-2">
                                <p class="text-xs text-gray-500">Scan barcode di atas menggunakan aplikasi E-Wallet atau M-Banking Anda.</p>
                            @else
                                <p class="text-sm text-red-500">Barcode QRIS tidak tersedia.</p>
                            @endif
                        </div>
                    @elseif($order->paymentMethod->type == 'cod')
                        <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200 text-yellow-800 text-sm">
                            Anda memilih metode Cash on Delivery (Bayar di Tempat). Silakan siapkan uang tunai sejumlah total tagihan saat kurir tiba. Namun, Anda tetap perlu mengonfirmasi pesanan via WhatsApp.
                        </div>
                    @endif
                @else
                    <p class="text-sm text-gray-600">Metode Terpilih: <span class="font-bold">{{ $order->payment_method }}</span></p>
                    <p class="text-xs text-gray-500 mt-2">Detail instruksi tidak ditemukan. Silakan hubungi admin.</p>
                @endif
            </div>

            @php
                $message = "Halo Admin, saya sudah melakukan pembayaran untuk pesanan saya dengan detail berikut:%0A%0A";
                $message .= "Nama: " . $order->recipient_name . "%0A";
                $message .= "No HP: " . $order->phone . "%0A";
                $message .= "Alamat: " . $order->address_details . "%0A%0A";
                $message .= "*Rincian Pesanan:*%0A";
                foreach ($order->items as $item) {
                    $message .= "- " . ($item->product ? $item->product->name : 'Produk') . " (" . $item->qty . "x)%0A";
                }
                $message .= "%0AKurir: " . $order->courier;
                $message .= "%0AOngkir: Rp " . number_format($order->shipping_cost, 0, ',', '.');
                $message .= "%0A*Total Pembayaran: Rp " . number_format($order->total_price, 0, ',', '.') . "*";
                $message .= "%0AMetode Pembayaran: " . ($order->paymentMethod ? $order->paymentMethod->name : $order->payment_method);
                $message .= "%0A%0ABerikut saya lampirkan bukti pembayarannya.";

                $phone = "+6287862331538";
                $waLink = "https://wa.me/$phone?text=$message";
            @endphp

            <div class="mt-8 space-y-4">
                <a href="{{ $waLink }}" target="_blank" class="block w-full bg-green-500 text-white py-4 rounded-xl font-bold text-sm hover:bg-green-600 transition shadow-lg flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                    Konfirmasi Pembayaran via WhatsApp
                </a>
                
                <a href="{{ route('orders.index') }}" class="block w-full border-2 border-gray-200 text-gray-600 py-4 rounded-xl font-bold text-sm hover:bg-gray-50 transition">
                    Nanti Saja (Kembali ke Daftar Pesanan)
                </a>
            </div>
            <p class="text-xs text-gray-400 mt-6">*Klik tombol di atas setelah Anda mentransfer dana untuk memproses pesanan lebih cepat.</p>
        </div>

    </main>

    <footer class="bg-[#FAF8F5] border-t border-gray-100 py-12 mt-auto">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8 justify-items-center md:justify-items-start">
                
                <!-- Logo & Brand -->
                <div class="flex flex-col items-center md:items-start">
                    <img src="{{ asset('images/logo_adeafwa.png') }}" alt="Logo" class="h-12 mb-4">
                    <p class="text-sm text-gray-700 font-serif-ade font-bold tracking-wider uppercase">Ade Afwa Boutique</p>
                    <p class="text-xs text-gray-500 mt-2 text-center md:text-left max-w-sm">Cantik Sederhana Elegan</p>
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
                            <span>+6287862331538</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="text-base">🕒</span>
                            <span>Buka pukul 09.00</span>
                        </li>
                    </ul>
                </div>

            </div>

        </div>
    </footer>

</body>
</html>

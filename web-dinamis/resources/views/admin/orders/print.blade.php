<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Resi #{{ $order->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body {
                width: 100mm; /* A6 width or thermal width */
                margin: 0;
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
        body {
            font-family: 'Courier New', Courier, monospace; /* Standard receipt font */
            color: #000;
            background: #fff;
            max-width: 100mm;
            margin: 0 auto;
            padding: 10px;
        }
        .border-dashed-black {
            border-bottom: 2px dashed #000;
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print mb-4 text-center">
        <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded font-bold text-sm">Cetak Ulang</button>
        <button onclick="window.close()" class="bg-gray-600 text-white px-4 py-2 rounded font-bold text-sm ml-2">Tutup</button>
    </div>

    <div class="border-2 border-black p-3">
        <!-- HEADER -->
        <div class="text-center border-dashed-black pb-3 mb-3">
            <h1 class="text-xl font-black uppercase tracking-widest">ADE AFWA BOUTIQUE</h1>
            <p class="text-[10px] font-bold mt-1">Order ID: #{{ $order->id }}</p>
            <p class="text-[10px] font-bold">Tgl: {{ $order->created_at->format('d/m/Y H:i') }}</p>
        </div>

        <!-- COURIER INFO -->
        <div class="border-dashed-black pb-3 mb-3">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-[10px]">Kurir:</p>
                    <p class="text-lg font-black uppercase">{{ $order->courier ?? '-' }}</p>
                </div>
                <div class="text-right">
                    <p class="text-[10px]">No Resi:</p>
                    <p class="text-sm font-bold">{{ $order->resi ?? 'Belum ada resi' }}</p>
                </div>
            </div>
        </div>

        <!-- PENERIMA -->
        <div class="border-dashed-black pb-3 mb-3">
            <p class="text-[10px] font-bold mb-1">Penerima:</p>
            <p class="text-sm font-black uppercase">{{ $order->recipient_name }}</p>
            <p class="text-xs font-bold">{{ $order->phone }}</p>
            <p class="text-[10px] mt-1">{{ $order->address_details }}</p>
        </div>

        <!-- PENGIRIM -->
        <div class="border-dashed-black pb-3 mb-3">
            <p class="text-[10px] font-bold mb-1">Pengirim:</p>
            <p class="text-xs font-black uppercase">Ade Afwa Boutique</p>
            <p class="text-[10px]">+6287862331538</p>
        </div>

        <!-- DETAIL ITEM -->
        <div>
            <p class="text-[10px] font-bold mb-1">Detail Barang:</p>
            <table class="w-full text-[10px]">
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td class="pb-1 align-top pr-2">-</td>
                        <td class="pb-1 align-top w-full">{{ $item->product ? $item->product->name : 'Produk' }}</td>
                        <td class="pb-1 align-top font-bold whitespace-nowrap">{{ $item->qty }}x</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>

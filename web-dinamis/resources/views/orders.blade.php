<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pesanan - Ade Afwa Boutique</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #FDF9F0; }
        .font-serif-ade { font-family: 'Playfair Display', serif; }
        .tab-active { border-bottom: 2px solid #CFB53B; color: #CFB53B; font-weight: 600; }
        .tab-inactive { border-bottom: 2px solid transparent; color: #6B7280; font-weight: 500; }
        .tab-inactive:hover { color: #CFB53B; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="text-gray-900 min-h-screen flex flex-col justify-between">

    <nav class="bg-[#FAF8F5] shadow-sm border-b border-gray-100 py-4">
        <div class="max-w-6xl mx-auto px-4 flex justify-between items-center">
            <a href="{{ route('home') }}">
                <img src="{{ asset('images/logo_adeafwa.png') }}" alt="Ade Afwa Boutique" class="h-10 object-contain">
            </a>
            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" class="text-sm font-semibold text-gray-600 hover:text-[#CFB53B]">Beranda</a>
                <span class="text-[10px] font-bold tracking-[0.2em] text-[#CFB53B] uppercase">Daftar Pesanan</span>
            </div>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto py-10 px-4 w-full flex-grow">
        <h2 class="font-serif-ade text-3xl font-bold text-gray-800 mb-8 text-center tracking-wider">PESANAN SAYA</h2>

        <!-- TABS -->
        <div class="flex border-b border-gray-200 mb-6 overflow-x-auto no-scrollbar">
            <button onclick="switchTab('unpaid')" id="tab-unpaid" class="tab-active py-3 px-6 whitespace-nowrap outline-none transition-colors text-sm">
                Belum Dibayar ({{ $unpaidOrders->count() }})
            </button>
            <button onclick="switchTab('processing')" id="tab-processing" class="tab-inactive py-3 px-6 whitespace-nowrap outline-none transition-colors text-sm">
                Sedang Dikemas ({{ $processingOrders->count() }})
            </button>
            <button onclick="switchTab('shipping')" id="tab-shipping" class="tab-inactive py-3 px-6 whitespace-nowrap outline-none transition-colors text-sm">
                Dalam Pengiriman ({{ $shippedOrders->count() }})
            </button>
            <button onclick="switchTab('completed')" id="tab-completed" class="tab-inactive py-3 px-6 whitespace-nowrap outline-none transition-colors text-sm">
                Pesanan Selesai ({{ $completedOrders->count() }})
            </button>
        </div>

        <!-- TAB CONTENT: UNPAID -->
        <div id="content-unpaid" class="tab-content block">
            @if($unpaidOrders->isEmpty())
                <div class="bg-white rounded-xl shadow-md p-8 text-center border border-gray-100">
                    <p class="text-gray-500 mb-4 text-sm">Tidak ada pesanan yang belum dibayar.</p>
                </div>
            @else
                @foreach($unpaidOrders as $order)
                    <x-order-card :order="$order" />
                @endforeach
            @endif
        </div>

        <!-- TAB CONTENT: PROCESSING -->
        <div id="content-processing" class="tab-content hidden">
            @if($processingOrders->isEmpty())
                <div class="bg-white rounded-xl shadow-md p-8 text-center border border-gray-100">
                    <p class="text-gray-500 mb-4 text-sm">Tidak ada pesanan yang sedang dikemas.</p>
                </div>
            @else
                @foreach($processingOrders as $order)
                    <x-order-card :order="$order" />
                @endforeach
            @endif
        </div>

        <!-- TAB CONTENT: SHIPPING -->
        <div id="content-shipping" class="tab-content hidden">
            @if($shippedOrders->isEmpty())
                <div class="bg-white rounded-xl shadow-md p-8 text-center border border-gray-100">
                    <p class="text-gray-500 mb-4 text-sm">Tidak ada pesanan dalam pengiriman.</p>
                </div>
            @else
                @foreach($shippedOrders as $order)
                    <x-order-card :order="$order" />
                @endforeach
            @endif
        </div>

        <!-- TAB CONTENT: COMPLETED -->
        <div id="content-completed" class="tab-content hidden">
            @if($completedOrders->isEmpty())
                <div class="bg-white rounded-xl shadow-md p-8 text-center border border-gray-100">
                    <p class="text-gray-500 mb-4 text-sm">Tidak ada pesanan yang selesai.</p>
                </div>
            @else
                @foreach($completedOrders as $order)
                    <x-order-card :order="$order" />
                @endforeach
            @endif
        </div>

    </main>

    <footer class="bg-[#FAF8F5] border-t border-gray-100 py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8 justify-items-center md:justify-items-start">
                
                <!-- Logo & Brand -->
                <div class="flex flex-col items-center md:items-start">
                    <img src="{{ asset('images/logo_adeafwa.png') }}" alt="Logo" class="h-12 mb-4">
                    <p class="text-sm text-gray-700 font-serif-ade font-bold tracking-wider uppercase">Ade Afwa Boutique</p>
                    <p class="text-xs text-gray-500 mt-2 text-center md:text-left max-w-sm">Koleksi pakaian elegan dengan kualitas terbaik untuk menemani momen spesial Anda.</p>
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
                            <span>0877-2901-5880</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="text-base">🕒</span>
                            <span>Buka pukul 09.00</span>
                        </li>
                    </ul>
                </div>

            </div>

            <div class="text-center pt-8 border-t border-gray-200">
                <p class="text-gray-400 text-[10px] uppercase tracking-[0.3em]">© 2026 Ade Afwa Boutique. Dibuat dengan ❤️ oleh Kelompok 5 Informatika UINSSC.</p>
            </div>
        </div>
    </footer>

    <script>
        function switchTab(tab) {
            // Hide all contents
            document.querySelectorAll('.tab-content').forEach(el => {
                el.classList.add('hidden');
                el.classList.remove('block');
            });
            
            // Reset tab styles
            const tabs = ['unpaid', 'processing', 'shipping', 'completed'];
            tabs.forEach(t => {
                document.getElementById('tab-' + t).className = 'tab-inactive py-3 px-6 whitespace-nowrap outline-none transition-colors text-sm';
            });
            
            // Show selected content
            document.getElementById('content-' + tab).classList.remove('hidden');
            document.getElementById('content-' + tab).classList.add('block');
            
            // Set active style for clicked tab
            document.getElementById('tab-' + tab).className = 'tab-active py-3 px-6 whitespace-nowrap outline-none transition-colors text-sm';
        }
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Ade Afwa Boutique</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;500;600&display=swap"
          rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #FDF9F0;
        }

        .font-serif-ade {
            font-family: 'Playfair Display', serif;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
    </style>
</head>

<body class="text-gray-900 min-h-screen flex flex-col justify-between">

    <!-- NAVBAR -->
    <nav class="bg-[#FAF8F5] shadow-sm border-b border-gray-100 py-4">
        <div class="max-w-6xl mx-auto px-4 flex justify-between items-center">

            <a href="{{ route('home') }}">
                <img src="{{ asset('images/logo_adeafwa.png') }}"
                     alt="Ade Afwa Boutique"
                     class="h-10 object-contain">
            </a>

            <span class="text-[10px] font-bold tracking-[0.2em] text-[#CFB53B] uppercase">
                Area Checkout
            </span>

        </div>
    </nav>

    <!-- CONTENT -->
    <main class="max-w-6xl mx-auto py-10 px-4 w-full">

        <h2 class="font-serif-ade text-3xl font-bold text-gray-800 mb-8 text-center tracking-wider">
            KONFIRMASI PESANAN
        </h2>

        <form action="{{ route('checkout.process') }}" method="POST">
            @csrf

            @foreach($selectedIds as $id)
                <input type="hidden" name="selected_items[]" value="{{ $id }}">
            @endforeach

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- LEFT -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- ALAMAT -->
                    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">

                        <h3 class="font-serif-ade text-lg text-[#CFB53B] uppercase tracking-widest mb-4 border-b pb-2">
                            Alamat Pengiriman
                        </h3>

                        <div class="space-y-4">

                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">
                                    Nama Penerima
                                </label>

                                <input type="text"
                                       name="recipient_name"
                                       required
                                       placeholder="Contoh: Dimas Adriansah"
                                       class="w-full p-3 border border-gray-300 rounded-lg text-xs outline-none focus:ring-2 focus:ring-[#CFB53B]">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">
                                    Nomor HP
                                </label>

                                <input type="text"
                                       name="phone"
                                       required
                                       placeholder="08xxxxxxxxxx"
                                       class="w-full p-3 border border-gray-300 rounded-lg text-xs outline-none focus:ring-2 focus:ring-[#CFB53B]">
                            </div>

                            <!-- SEARCH DESTINATION -->
                            <div>

                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">
                                    Cari Kecamatan / Kota
                                </label>

                                <div class="flex gap-2">
                                    <input type="text"
                                           id="search-destination"
                                           placeholder="Contoh: Bandung, Sukabumi..."
                                           class="flex-1 p-3 border border-gray-300 rounded-lg text-xs outline-none focus:ring-2 focus:ring-[#CFB53B]">

                                    <button type="button"
                                            id="btn-cari-lokasi"
                                            class="px-4 py-2 bg-[#CFB53B] text-white text-xs font-bold rounded-lg hover:bg-yellow-600 transition-all duration-200 flex items-center gap-1 whitespace-nowrap shadow-md">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M16.65 10.5A6.15 6.15 0 1110.5 4.35 6.15 6.15 0 0116.65 10.5z"/>
                                        </svg>
                                        Cari Lokasi
                                    </button>
                                </div>

                                <!-- Loading indicator -->
                                <div id="search-loading" class="hidden mt-2 text-xs text-gray-400 flex items-center gap-2">
                                    <svg class="animate-spin h-4 w-4 text-[#CFB53B]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                    </svg>
                                    Mencari lokasi...
                                </div>

                                <div id="destination-results"
                                     class="border rounded-lg mt-2 bg-white max-h-48 overflow-y-auto shadow-lg hidden">
                                </div>

                                <input type="hidden"
                                       id="destination-id"
                                       name="destination">

                                <!-- Lokasi terpilih badge -->
                                <div id="selected-location-badge" class="hidden mt-2 flex items-center gap-2 bg-green-50 border border-green-200 rounded-lg px-3 py-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span id="selected-location-text" class="text-xs text-green-700 font-medium"></span>
                                </div>
                            </div>

                            <!-- DETAIL ALAMAT MANUAL (RT/RW, KELURAHAN, KECAMATAN, KABUPATEN) -->
                            <div class="bg-gray-50/50 p-4 rounded-xl border border-gray-200/50 space-y-4">
                                <p class="text-xs font-bold text-[#CFB53B] uppercase tracking-wider">
                                    Detail Alamat Rumah (RT/RW & Jalan)
                                </p>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">
                                            Nama Jalan & No. Rumah / Patokan <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text"
                                               id="input_jalan"
                                               required
                                               placeholder="Contoh: Jl. Kenanga No. 12"
                                               class="w-full p-3 border border-gray-300 rounded-lg text-xs outline-none focus:ring-2 focus:ring-[#CFB53B] bg-white">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">
                                            RT / RW <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text"
                                               id="input_rtrw"
                                               required
                                               placeholder="Contoh: RT 03 / RW 04"
                                               class="w-full p-3 border border-gray-300 rounded-lg text-xs outline-none focus:ring-2 focus:ring-[#CFB53B] bg-white">
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">
                                            Kelurahan / Desa <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text"
                                               id="input_kelurahan"
                                               required
                                               placeholder="Contoh: Lemahwungkuk"
                                               class="w-full p-3 border border-gray-300 rounded-lg text-xs outline-none focus:ring-2 focus:ring-[#CFB53B] bg-white">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">
                                            Kecamatan <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text"
                                               id="input_kecamatan"
                                               required
                                               placeholder="Contoh: Harjamukti"
                                               class="w-full p-3 border border-gray-300 rounded-lg text-xs outline-none focus:ring-2 focus:ring-[#CFB53B] bg-white">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">
                                            Kabupaten / Kota <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text"
                                               id="input_kabupaten"
                                               required
                                               placeholder="Contoh: Cirebon"
                                               class="w-full p-3 border border-gray-300 rounded-lg text-xs outline-none focus:ring-2 focus:ring-[#CFB53B] bg-white">
                                    </div>
                                </div>
                            </div>

                            <!-- ALAMAT LENGKAP PREVIEW -->
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">
                                    Pratinjau Alamat Lengkap
                                </label>
                                <textarea id="address-details"
                                          name="address_details"
                                          rows="3"
                                          readonly
                                          required
                                          class="w-full p-3 border border-gray-200 rounded-lg text-xs outline-none bg-gray-50 text-gray-500 cursor-not-allowed"
                                          placeholder="Akan terisi otomatis berdasarkan isian detail di atas..."></textarea>
                            </div>

                        </div>
                    </div>

                    <!-- PAYMENT -->
                    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">

                        <h3 class="font-serif-ade text-lg text-[#CFB53B] uppercase tracking-widest mb-4 border-b pb-2">
                            Metode Pembayaran
                        </h3>

                        @if($paymentMethods->isEmpty())
                            <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 text-xs px-4 py-3 rounded-lg">
                                ⚠️ Belum ada metode pembayaran yang tersedia. Silakan hubungi admin.
                            </div>
                        @else
                        <select name="payment_method_id"
                                class="w-full p-3 border border-gray-300 rounded-lg bg-gray-50 text-xs outline-none focus:ring-[#CFB53B]" required>
                            
                            <option value="">-- Pilih Metode Pembayaran --</option>
                            @foreach($paymentMethods as $method)
                                <option value="{{ $method->id }}">
                                    {{ $method->name }} 
                                    @if($method->type == 'bank_transfer') (Transfer Bank)
                                    @elseif($method->type == 'qris') (QRIS)
                                    @elseif($method->type == 'ewallet') (E-Wallet)
                                    @else (COD) @endif
                                </option>
                            @endforeach

                        </select>
                        @endif

                    </div>
                </div>

                <!-- RIGHT -->
                <div class="space-y-6">

                    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">

                        <h3 class="font-serif-ade text-lg text-[#CFB53B] uppercase tracking-widest mb-4 border-b pb-2">
                            Rincian Barang
                        </h3>

                        <!-- ITEM -->
                        <div class="max-h-48 overflow-y-auto no-scrollbar border-b pb-4 mb-6 space-y-4">

                            @foreach($items as $item)

                                <div class="flex justify-between items-center text-xs">

                                    <div class="flex items-center space-x-3">

                                        <img src="{{ asset('storage/' . $item->product->image) }}"
                                             class="w-10 h-10 object-cover rounded">

                                        <div>

                                            <p class="font-semibold">
                                                {{ $item->product->name }}
                                            </p>

                                            <p class="text-[10px] text-gray-500">
                                                {{ $item->qty }} x
                                                Rp {{ number_format($item->variant ? $item->variant->price : $item->product->price, 0, ',', '.') }}
                                            </p>

                                        </div>

                                    </div>

                                    <p class="font-bold">
                                        Rp {{ number_format(($item->variant ? $item->variant->price : $item->product->price) * $item->qty, 0, ',', '.') }}
                                    </p>

                                </div>

                            @endforeach

                        </div>

                        <!-- KURIR -->
                        <div class="mb-4">

                            <label class="block text-xs font-bold text-gray-700 mb-2">
                                Pilih Kurir
                            </label>

                            <select id="courier-select"
                                    class="w-full p-3 border border-gray-200 rounded-lg bg-gray-50 text-xs">

                                <option value="">
                                    -- Pilih Kurir --
                                </option>

                                <option value="jne">JNE</option>
                                <option value="pos">POS Indonesia</option>

                            </select>

                        </div>

                        <!-- SERVICE -->
                        <div class="mb-6">

                            <div class="flex items-center justify-between mb-2">
                                <label class="block text-xs font-bold text-gray-700">
                                    Layanan Pengiriman
                                </label>
                                <button type="button"
                                        id="btn-hitung-ongkir"
                                        class="px-3 py-1.5 bg-[#CFB53B] text-white text-[10px] font-bold rounded-lg hover:bg-yellow-600 transition-all duration-200 flex items-center gap-1 shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M4 19h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    Hitung Ongkir
                                </button>
                            </div>

                            <select id="service-select"
                                    name="courier_name"
                                    class="w-full p-3 border border-gray-200 rounded-lg bg-gray-50 text-xs">

                                <option value="">
                                    -- Pilih Layanan --
                                </option>

                            </select>

                        </div>

                        <!-- HIDDEN -->
                        <input type="hidden"
                               name="shipping_cost"
                               id="shipping-cost">

                        <!-- TOTAL -->
                        <div class="space-y-2 pt-4 border-t text-xs">

                            <div class="flex justify-between text-gray-500">
                                <span>Subtotal Produk</span>

                                <span>
                                    Rp {{ number_format($total, 0, ',', '.') }}
                                </span>
                            </div>

                            <div class="flex justify-between text-gray-500">
                                <span>Biaya Pengiriman</span>

                                <span id="display-ongkir">
                                    Rp 0
                                </span>
                            </div>

                            <div class="flex justify-between items-center pt-4 mt-2 border-t-2 font-bold text-sm">

                                <span>Total Akhir</span>

                                <span class="text-lg text-[#CFB53B]"
                                      id="grand-total">

                                    Rp {{ number_format($total, 0, ',', '.') }}

                                </span>

                            </div>

                        </div>

                        <!-- BUTTON -->
                        <button type="submit"
                                class="w-full mt-8 bg-[#D4AF37] text-white py-4 rounded-xl font-bold text-sm hover:bg-yellow-700 transition">

                            Checkout Sekarang

                        </button>

                    </div>
                </div>

            </div>

        </form>

    </main>

    <!-- FOOTER -->
    <footer class="bg-[#FAF8F5] border-t border-gray-100 py-12 mt-12">
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

    <!-- SCRIPTS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>

        // Concatenate address inputs to details text area
        function combineAddressDetails() {
            const jalan = document.getElementById('input_jalan').value.trim();
            const rtrw = document.getElementById('input_rtrw').value.trim();
            const kelurahan = document.getElementById('input_kelurahan').value.trim();
            const kecamatan = document.getElementById('input_kecamatan').value.trim();
            const kabupaten = document.getElementById('input_kabupaten').value.trim();
            
            let parts = [];
            if (jalan) parts.push(jalan);
            if (rtrw) parts.push(rtrw);
            if (kelurahan) parts.push('Kel. ' + kelurahan);
            if (kecamatan) parts.push('Kec. ' + kecamatan);
            if (kabupaten) parts.push(kabupaten);
            
            document.getElementById('address-details').value = parts.join(', ');
        }

        // Add event listeners to input fields
        ['input_jalan', 'input_rtrw', 'input_kelurahan', 'input_kecamatan', 'input_kabupaten'].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.addEventListener('input', combineAddressDetails);
            }
        });


        // TOTAL
        const subtotal = {{ $total }};


        // SEARCH DESTINATION — dipanggil saat tombol "Cari Lokasi" diklik
        $('#btn-cari-lokasi').on('click', function () {

            let search = $('#search-destination').val().trim();

            if (search.length < 3) {
                alert('Masukkan minimal 3 karakter untuk mencari lokasi.');
                return;
            }

            // Tampilkan loading, sembunyikan hasil sebelumnya
            $('#search-loading').removeClass('hidden').addClass('flex');
            $('#destination-results').addClass('hidden').html('');
            $('#selected-location-badge').addClass('hidden');
            $('#destination-id').val('');

            $.ajax({

                url: '/api/search-destination',
                type: 'GET',
                data: { search: search },

                success: function(res) {

                    console.log('Search Destination Response:', JSON.stringify(res, null, 2));

                    $('#search-loading').addClass('hidden').removeClass('flex');

                    // Komerce mengembalikan: { meta: {...}, data: [ {id, label, subdistrict_name, ...} ] }
                    let destinations = [];
                    if (res.data && Array.isArray(res.data)) {
                        destinations = res.data;
                    }

                    if (destinations.length > 0) {

                        let html = '';
                        destinations.forEach(item => {
                            // Gunakan field `label` dari Komerce yang sudah lengkap
                            let id    = item.id ?? '';
                            let label = item.label ?? [item.subdistrict_name, item.district_name, item.city_name, item.province_name].filter(Boolean).join(', ');

                            html += `
                                <div class="destination-item cursor-pointer px-4 py-3 text-xs hover:bg-[#FDF9F0] border-b border-gray-100 flex items-center gap-2 transition"
                                     data-id="${id}"
                                     data-label="${label}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-[#CFB53B] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span>${label}</span>
                                </div>
                            `;
                        });

                        $('#destination-results').html(html).removeClass('hidden');

                    } else {
                        $('#destination-results')
                            .html('<div class="px-4 py-3 text-xs text-gray-400 text-center">❌ Lokasi tidak ditemukan. Coba kata kunci lain.</div>')
                            .removeClass('hidden');
                    }
                },

                error: function(xhr) {
                    $('#search-loading').addClass('hidden').removeClass('flex');
                    console.error('Error search destination:', xhr.responseText);
                    $('#destination-results')
                        .html('<div class="px-4 py-3 text-xs text-red-400 text-center">⚠️ Gagal mencari lokasi. Coba lagi.</div>')
                        .removeClass('hidden');
                }
            });
        });

        // Tekan ENTER di input juga memicu pencarian
        $('#search-destination').on('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                $('#btn-cari-lokasi').trigger('click');
            }
        });


        // PILIH DESTINATION
        $(document).on('click', '.destination-item', function () {

            let id         = $(this).data('id');
            let label      = $(this).data('label');
            let citySearch = $(this).data('city') || label;

            $('#destination-id').val(id);
            console.log('DESTINATION TERPILIH:', id, label);

            // Update input & tutup dropdown
            $('#search-destination').val(label);
            $('#destination-results').addClass('hidden').html('');

            // Tampilkan badge lokasi terpilih
            $('#selected-location-text').text('📍 ' + label);
            $('#selected-location-badge').removeClass('hidden').addClass('flex');

            // Reset layanan sebelumnya
            $('#service-select').html('<option value="">-- Pilih Layanan --</option>');
            $('#shipping-cost').val('');
            $('#display-ongkir').text('Rp 0');
            // Jika kurir sudah dipilih, langsung hitung ongkir
            if ($('#courier-select').val()) {
                setTimeout(() => hitungOngkir(), 200);
            }
        });


        // CHECK ONGKIR — fungsi terpisah agar bisa dipanggil ulang
        function hitungOngkir() {

            let courier     = $('#courier-select').val();
            let destination = $('#destination-id').val();
            let weight      = {{ $totalWeight }};

            if (!courier) {
                alert('Pilih kurir terlebih dahulu.');
                return;
            }
            if (!destination) {
                alert('Pilih alamat tujuan pengiriman terlebih dahulu.');
                return;
            }

            // Loading state
            $('#service-select').html('<option value="">⏳ Menghitung ongkir...</option>');
            $('#btn-hitung-ongkir').prop('disabled', true).text('Menghitung...');

            console.log('[Ongkir] Kirim ke API:', { courier, destination, weight });

            $.ajax({
                url: '/api/check-ongkir',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    destination: destination,
                    courier: courier,
                    weight: weight
                },

                success: function(res) {

                    console.log('[Ongkir] Response API:', JSON.stringify(res, null, 2));
                    $('#btn-hitung-ongkir').prop('disabled', false).text('Hitung Ongkir');

                    // Komerce: { meta: {...}, data: [{name, code, service, description, cost, etd}] }
                    let services = [];
                    if (res.data && Array.isArray(res.data)) {
                        services = res.data;
                    }

                    if (services.length > 0) {

                        let html = '<option value="">-- Pilih Layanan --</option>';

                        services.forEach(item => {
                            // Field: name, code, service, description, cost, etd
                            let name  = item.name  ?? '';
                            let svc   = item.service ?? '';
                            let desc  = item.description ?? '';
                            let cost  = item.cost ?? 0;
                            let etd   = item.etd ? (' - ETD ' + item.etd + ' hari') : '';
                            let label = name + ' (' + svc + ' - ' + desc + ')' + etd + ' — Rp ' + cost.toLocaleString('id-ID');

                            html += `<option value="${svc}" data-cost="${cost}">${label}</option>`;
                        });

                        $('#service-select').html(html);

                    } else {
                        let errMsg = (res.meta && res.meta.message) ? res.meta.message : 'Layanan tidak tersedia.';
                        $('#service-select').html('<option value="">❌ ' + errMsg + '</option>');
                    }
                },

                error: function(xhr) {
                    console.error('[Ongkir] Error:', xhr.status, xhr.responseText);
                    $('#btn-hitung-ongkir').prop('disabled', false).text('Hitung Ongkir');
                    $('#service-select').html('<option value="">⚠️ Gagal menghitung ongkir.</option>');
                    alert('Gagal menghitung ongkir. Periksa koneksi internet Anda.');
                }
            });
        }

        // Trigger saat kurir diganti
        $('#courier-select').on('change', function () {
            // Reset layanan & ongkir dulu
            $('#service-select').html('<option value="">-- Pilih Layanan --</option>');
            $('#shipping-cost').val('');
            $('#display-ongkir').text('Rp 0');
            $('#grand-total').text('Rp ' + subtotal.toLocaleString('id-ID'));

            if ($('#destination-id').val()) {
                hitungOngkir();
            }
        });

        // Tombol manual "Hitung Ongkir"
        $('#btn-hitung-ongkir').on('click', function () {
            hitungOngkir();
        });


        // PILIH SERVICE
        $('#service-select').on('change', function () {

            // Pakai .attr() bukan .data() agar baca langsung dari atribut HTML
            // (jQuery .data() meng-cache nilai lama untuk elemen yang di-inject dinamis)
            let costStr = $(this).find(':selected').attr('data-cost');
            let cost    = parseInt(costStr) || 0;

            let grandTotal = subtotal + cost;

            $('#shipping-cost').val(cost);

            $('#display-ongkir').text(
                'Rp ' + cost.toLocaleString('id-ID')
            );

            $('#grand-total').text(
                'Rp ' + grandTotal.toLocaleString('id-ID')
            );

            console.log('[Service] cost:', cost, '| grand total:', grandTotal);
        });

    </script>

</body>
</html>
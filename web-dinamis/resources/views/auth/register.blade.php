<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Ade Afwa Boutique</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* CSS Tambahan untuk kustomisasi lebih detail */
        .bg-ade-afwa { background-color: #FDF9F0; } /* Warna krem lembut khas Ade Afwa */
        .text-ade-afwa-gold { color: #CFB53B; } /* Warna emas untuk teks khusus */
        .font-serif-ade { font-family: 'Playfair Display', serif; } /* Font serif untuk kesan elegan */
        
        /* Gaya input agar konsisten dan menarik */
        .ade-input {
            width: 100%;
            padding: 0.75rem 1rem;
            background-color: #F4F0E8; /* Sedikit lebih gelap dari bg untuk kontras */
            border-radius: 0.5rem;
            outline: none;
            transition: all 0.2s;
        }
        .ade-input:focus {
            background-color: #FFFFFF;
            box-shadow: 0 0 0 2px rgba(207, 181, 59, 0.5); /* Ring emas saat fokus */
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body class="bg-ade-afwa text-gray-900 font-sans antialiased relative min-h-screen">

    <header class="p-4 border-b border-gray-100 bg-white shadow-sm flex items-center justify-between">
        <a href="/" class="text-sm text-ade-afwa-gold hover:text-gray-900 transition font-medium">← Kembali ke Toko</a>
        
        <div class="flex items-center space-x-2">
    <span class="text-xl font-medium">Register</span>
    <img src="{{ asset('images/logo_adeafwa.png') }}" alt="Logo Ade Afwa" class="h-10 w-auto">
</div>
    </header>

    <div class="max-w-4xl mx-auto px-4 py-16">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-serif-ade font-bold text-ade-afwa-gold mb-3">Buat Akun Baru</h1>
            <p class="text-gray-600">Silakan lengkapi informasi di bawah ini untuk bergabung dengan **Ade Afwa Boutique**.</p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden p-8 md:p-12">
            
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
                    <ul class="list-disc list-inside text-sm text-red-600 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    
                    <div class="space-y-6">
                        <h2 class="text-lg font-semibold text-gray-800 border-b pb-2">Data Pribadi</h2>
                        
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap</label>
                            <input type="text" id="name" name="name" required value="{{ old('name') }}" placeholder="Contoh: Siti Aisyah" class="ade-input">
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1.5">No. HP / WhatsApp (Aktif)</label>
                            <input type="tel" id="phone" name="phone" required value="{{ old('phone', '+62') }}" oninput="let v=this.value.replace(/[^0-9+]/g,''); if(v.startsWith('0')) v='+62'+v.substring(1); if(!v.startsWith('+62')) v='+62'; this.value=v;" placeholder="Contoh: +628123456789" pattern="^\+62[0-9]{8,13}$" title="Nomor telepon harus diawali dengan +62" class="ade-input">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2.5">Jenis Kelamin</label>
                            <div class="flex items-center space-x-8">
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="radio" name="gender" value="female" required {{ old('gender') === 'female' ? 'checked' : '' }} class="w-5 h-5 text-ade-afwa-gold border-gray-300 focus:ring-ade-afwa-gold">
                                    <span class="text-gray-800">Perempuan</span>
                                </label>
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="radio" name="gender" value="male" {{ old('gender') === 'male' ? 'checked' : '' }} class="w-5 h-5 text-ade-afwa-gold border-gray-300 focus:ring-ade-afwa-gold">
                                    <span class="text-gray-800">Laki-laki</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <h2 class="text-lg font-semibold text-gray-800 border-b pb-2">Alamat & Kredensial</h2>
                        
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1.5">Alamat Lengkap Pengiriman</label>
                            <textarea id="address" name="address" required rows="4" placeholder="Nama Jalan, No Rumah, RT/RW, Kec/Kab" class="ade-input">{{ old('address') }}</textarea>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Alamat Email</label>
                            <input type="email" id="email" name="email" required value="{{ old('email') }}" placeholder="Contoh: sitiaisyah@email.com" class="ade-input">
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                            <input type="password" id="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter" class="ade-input">
                        </div>
                        
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">Konfirmasi Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password Anda" class="ade-input">
                        </div>
                    </div>
                </div>

                {{-- Checkbox Syarat & Ketentuan di bawah Form --}}
                <div class="mt-8 flex flex-col items-center justify-center p-4 bg-[#FDF9F0]/60 rounded-xl border border-[#CFB53B]/10">
                    <label class="inline-flex items-start space-x-3 cursor-pointer text-left max-w-lg">
                        <input type="checkbox" id="agree-checkbox-main" class="mt-1 w-5 h-5 rounded text-ade-afwa-gold border-gray-300 focus:ring-ade-afwa-gold">
                        <span class="text-sm text-gray-700 select-none">
                            Saya telah membaca dan menyetujui 
                            <button type="button" onclick="openPolicyModal()" class="text-ade-afwa-gold hover:underline font-bold focus:outline-none">
                                Syarat & Ketentuan serta Kebijakan Privasi
                            </button> 
                            yang berlaku.
                        </span>
                    </label>
                </div>

                <div class="mt-8 text-center space-y-5">
                    <button type="submit" id="register-submit-btn" disabled class="w-full md:w-auto md:px-12 bg-gray-300 text-gray-500 py-4 rounded-xl font-bold text-lg transition flex justify-center items-center gap-2 cursor-not-allowed">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd" />
                        </svg>
                        <span>Daftar Akun Sekarang</span>
                    </button>
                    
                    <p class="text-sm text-gray-600">
                        Sudah punya akun? 
                        <a href="{{ route('login') }}" class="font-medium text-ade-afwa-gold hover:underline">Masuk di sini</a>
                    </p>
                </div>
            </form>
        </div>

        <footer class="mt-16 text-center text-sm text-gray-500">
            &copy; {{ date('Y') }} Ade Afwa Boutique. Semua hak dilindungi.
        </footer>
    </div>

    {{-- Modal Syarat & Ketentuan serta Kebijakan Privasi --}}
    <div id="policyModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>
        <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-6">
            <div class="relative transform overflow-hidden rounded-[2rem] bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-3xl border border-white">
                
                {{-- Header --}}
                <div class="bg-ade-afwa px-8 py-6 border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-serif-ade font-bold text-ade-afwa-gold uppercase tracking-tight">Kebijakan Butik</h3>
                        <p class="text-[10px] text-gray-500 font-medium uppercase tracking-widest mt-1">Syarat & Ketentuan dan Kebijakan Privasi</p>
                    </div>
                    <button type="button" onclick="closePolicyModal()" class="text-gray-400 hover:text-gray-900 hover:bg-gray-100 p-2 rounded-xl transition duration-200 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Body (Scrollable) --}}
                <div class="bg-white px-8 py-6 max-h-[60vh] overflow-y-auto">
                    <!-- Syarat & Ketentuan -->
                    <div class="mb-10">
                        <h2 class="text-xl font-bold text-gray-900 border-b pb-2 mb-4 font-serif-ade text-ade-afwa-gold">Syarat & Ketentuan - Ade Afwa Boutique</h2>
                        <p class="text-sm text-gray-600 mb-4 leading-relaxed">
                            Selamat datang di Ade Afwa Boutique. Terima kasih telah memercayakan kebutuhan fashion Anda kepada kami. Dengan mengakses dan berbelanja di website kami, Anda dianggap telah membaca, memahami, dan menyetujui seluruh aturan main yang berlaku di bawah ini:
                        </p>
                        
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-800 uppercase tracking-wider">1. Ketentuan Umum</h3>
                                <ul class="list-disc list-inside text-xs text-gray-600 space-y-1 mt-1 leading-relaxed">
                                    <li>Ade Afwa Boutique adalah platform e-commerce yang bergerak di bidang fashion dan penjualan busana berkualitas.</li>
                                    <li>Kami berhak mengubah, menambah, atau memperbarui Syarat & Ketentuan ini sewaktu-waktu tanpa pemberitahuan terlebih dahulu.</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-sm font-semibold text-gray-800 uppercase tracking-wider">2. Akun Pengguna</h3>
                                <ul class="list-disc list-inside text-xs text-gray-600 space-y-1 mt-1 leading-relaxed">
                                    <li>Anda wajib memberikan informasi data diri yang benar, akurat, dan lengkap saat melakukan pendaftaran akun.</li>
                                    <li>Anda bertanggung jawab penuh untuk menjaga kerahasiaan kata sandi (password) akun Anda pribadi.</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-sm font-semibold text-gray-800 uppercase tracking-wider">3. Pemesanan dan Pembayaran</h3>
                                <ul class="list-disc list-inside text-xs text-gray-600 space-y-1 mt-1 leading-relaxed">
                                    <li>Produk yang sudah dipesan akan diproses setelah pembayaran dikonfirmasi oleh sistem <i>payment gateway</i> resmi kami.</li>
                                    <li>Pembeli wajib melakukan pembayaran sesuai dengan nominal total yang tertera pada halaman <i>checkout</i>.</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-sm font-semibold text-gray-800 uppercase tracking-wider">4. Pengiriman Barang</h3>
                                <ul class="list-disc list-inside text-xs text-gray-600 space-y-1 mt-1 leading-relaxed">
                                    <li>Pengiriman barang akan dilakukan menggunakan jasa ekspedisi rekanan kami setelah pesanan selesai dikemas.</li>
                                    <li>Estimasi waktu pengiriman bergantung pada wilayah tujuan dan kebijakan pihak ekspedisi. Ade Afwa Boutique tidak bertanggung jawab atas keterlambatan yang murni disebabkan oleh pihak kurir.</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-sm font-semibold text-gray-800 uppercase tracking-wider">5. Kebijakan Pengembalian (Refund & Return)</h3>
                                <ul class="list-disc list-inside text-xs text-gray-600 space-y-1 mt-1 leading-relaxed">
                                    <li>Segala bentuk komplain, cacat produksi, atau ketidaksesuaian ukuran wajib menyertakan <b>video unboxing</b> tanpa terputus.</li>
                                    <li>Karena sistem kami belum sepenuhnya otomatis, proses pengajuan pengembalian barang atau dana (refund) dilakukan secara <b>manual melalui Customer Service WhatsApp resmi kami</b>. Ketentuan lengkap dapat dilihat pada halaman Kebijakan Pengembalian.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Kebijakan Privasi -->
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-gray-900 border-b pb-2 mb-4 font-serif-ade text-ade-afwa-gold">Kebijakan Privasi - Ade Afwa Boutique</h2>
                        <p class="text-sm text-gray-600 mb-4 leading-relaxed">
                            Di Ade Afwa Boutique, privasi dan keamanan data pengunjung serta pelanggan adalah prioritas utama kami. Kebijakan Privasi ini menjelaskan bagaimana kami mengumpulkan, menggunakan, dan melindungi data pribadi Anda.
                        </p>
                        
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-800 uppercase tracking-wider">1. Data yang Kami Kumpulkan</h3>
                                <p class="text-xs text-gray-600 mt-1 leading-relaxed">
                                    Kami mengumpulkan informasi yang Anda berikan secara sukarela saat mendaftar akun atau melakukan transaksi, meliputi:
                                </p>
                                <ul class="list-disc list-inside text-xs text-gray-600 space-y-1 mt-1 leading-relaxed">
                                    <li>Nama Lengkap</li>
                                    <li>Alamat Pengiriman dan Alamat Penagihan</li>
                                    <li>Nomor Telepon/WhatsApp</li>
                                    <li>Alamat Email</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-sm font-semibold text-gray-800 uppercase tracking-wider">2. Penggunaan Data Pengguna (Pure untuk Internal)</h3>
                                <p class="text-xs text-gray-600 mt-1 leading-relaxed">
                                    Kami menegaskan bahwa seluruh data pribadi yang Anda berikan <b>pure (murni) hanya digunakan untuk kepentingan operasional Ade Afwa Boutique</b>. Kami menggunakan data Anda untuk:
                                </p>
                                <ul class="list-disc list-inside text-xs text-gray-600 space-y-1 mt-1 leading-relaxed">
                                    <li>Memproses transaksi pembelian Anda.</li>
                                    <li>Mengatur proses pengiriman barang yang Anda pesan agar sampai ke alamat tujuan dengan tepat.</li>
                                    <li>Memberikan informasi terkait status pesanan atau kendala pengiriman melalui email atau WhatsApp.</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-sm font-semibold text-gray-800 uppercase tracking-wider">3. Keamanan Data</h3>
                                <ul class="list-disc list-inside text-xs text-gray-600 space-y-1 mt-1 leading-relaxed">
                                    <li>Kami berkomitmen penuh untuk menjaga keamanan data pribadi Anda. Data Anda disimpan secara aman di dalam sistem kami dengan enkripsi standar industri untuk mencegah akses ilegal, kehilangan, atau penyalahgunaan dari pihak luar.</li>
                                    <li><b>Kami menjamin tidak akan pernah menjual, menyewakan, menukar, atau menyebarluaskan data pribadi Anda kepada pihak ketiga mana pun</b> di luar kepentingan pengiriman barang (seperti pihak kurir/ekspedisi).</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-sm font-semibold text-gray-800 uppercase tracking-wider">4. Persetujuan Anda</h3>
                                <p class="text-xs text-gray-600 mt-1 leading-relaxed">
                                    Dengan menggunakan website kami dan mencentang kotak persetujuan pada saat pendaftaran, Anda secara sadar menyetujui pengumpulan dan penggunaan data Anda sesuai dengan ketentuan Kebijakan Privasi ini.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Checkbox Syarat & Ketentuan di Bagian Paling Bawah Scroll Modal --}}
                    <div class="mt-10 p-5 bg-[#FDF9F0]/80 rounded-2xl border border-[#CFB53B]/20">
                        <label class="inline-flex items-start space-x-3 cursor-pointer text-left">
                            <input type="checkbox" id="agree-checkbox-modal" class="mt-1 w-5 h-5 rounded text-ade-afwa-gold border-gray-300 focus:ring-ade-afwa-gold">
                            <span class="text-sm text-gray-700 font-bold select-none leading-relaxed">
                                Saya telah membaca dan menyetujui Syarat & Ketentuan serta Kebijakan Privasi yang berlaku.
                            </span>
                        </label>
                    </div>
                </div>

                {{-- Footer Modal --}}
                <div class="bg-gray-50 px-8 py-4 flex justify-end">
                    <button type="button" onclick="closePolicyModal()" class="bg-ade-afwa-gold hover:bg-yellow-600 text-white px-8 py-3 rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-200">
                        Tutup & Simpan Persetujuan
                    </button>
                </div>

            </div>
        </div>
    </div>

    <script>
        // Modal Handlers
        function openPolicyModal() {
            document.getElementById('policyModal').classList.remove('hidden');
        }

        function closePolicyModal() {
            document.getElementById('policyModal').classList.add('hidden');
        }

        // Synchronization & Submit button control
        document.addEventListener('DOMContentLoaded', function() {
            const agreeMain = document.getElementById('agree-checkbox-main');
            const agreeModal = document.getElementById('agree-checkbox-modal');
            const registerBtn = document.getElementById('register-submit-btn');

            function updateAgreement(checked) {
                agreeMain.checked = checked;
                agreeModal.checked = checked;
                
                if (checked) {
                    registerBtn.removeAttribute('disabled');
                    registerBtn.className = "w-full md:w-auto md:px-12 bg-ade-afwa-gold text-gray-900 py-4 rounded-xl font-bold text-lg hover:bg-yellow-600 hover:text-white transition shadow-lg flex justify-center items-center gap-2 cursor-pointer";
                } else {
                    registerBtn.setAttribute('disabled', 'true');
                    registerBtn.className = "w-full md:w-auto md:px-12 bg-gray-300 text-gray-500 py-4 rounded-xl font-bold text-lg transition flex justify-center items-center gap-2 cursor-not-allowed";
                }
            }

            agreeMain.addEventListener('change', function(e) {
                updateAgreement(e.target.checked);
            });

            agreeModal.addEventListener('change', function(e) {
                updateAgreement(e.target.checked);
            });
        });
    </script>
</body>
</html>
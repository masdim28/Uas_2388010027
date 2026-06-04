<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya | Ade Afwa Boutique</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .font-serif-ade { font-family: 'Playfair Display', serif; }
        .bg-ade-krem { background-color: #FDF9F0; }
        .text-ade-gold { color: #CFB53B; }
        .bg-ade-gold { background-color: #CFB53B; }
        .modal-transition { transition: all 0.3s ease-in-out; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900">

    <nav class="bg-ade-krem py-6 border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 flex justify-between items-center">
            <a href="/" class="text-ade-gold flex items-center gap-2 transition hover:scale-105">
                <span class="font-bold text-sm uppercase tracking-widest">← Beranda</span>
            </a>
            <h2 class="font-serif-ade text-ade-gold text-2xl tracking-tight">Ade Afwa Boutique</h2>
            <div class="w-20"></div> </div>
    </nav>

    <main class="max-w-4xl mx-auto py-12 px-4">
        @if(session('status'))
            <div class="mb-8 p-4 bg-green-500 text-white rounded-2xl text-center font-semibold shadow-lg shadow-green-200 flex items-center justify-center gap-2 animate-bounce">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ session('status') }}
            </div>
        @endif

        <div class="bg-white rounded-[2rem] shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
            <div class="bg-ade-gold p-10 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                <div class="flex flex-col md:flex-row items-center gap-8 relative z-10">
                    <div class="h-32 w-32 bg-white/20 rounded-full flex items-center justify-center text-5xl font-bold backdrop-blur-md border-4 border-white/30 shadow-2xl">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="text-center md:text-left flex-1">
                        <h1 class="text-4xl font-serif-ade mb-1">{{ Auth::user()->name }}</h1>
                        <p class="text-white/80 font-light tracking-wide italic">Pelanggan Setia Ade Afwa</p>
                        <button onclick="openModal()" class="mt-6 bg-white text-ade-gold hover:bg-ade-krem px-8 py-2.5 rounded-full text-xs font-bold transition-all shadow-lg active:scale-95 uppercase tracking-tighter">
                            Ubah Detail Profil
                        </button>
                    </div>
                </div>
            </div>

            <div class="p-8 md:p-14">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                    <div class="space-y-1 group">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Nama Lengkap</p>
                        <p class="text-gray-800 text-lg border-b border-gray-100 pb-2 group-hover:border-ade-gold transition-colors">{{ Auth::user()->name }}</p>
                    </div>

                    <div class="space-y-1 group">
    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Alamat Email</p>
    <p class="text-gray-800 text-lg border-b border-gray-100 pb-2 group-hover:border-ade-gold transition-colors">{{ Auth::user()->email }}</p>
    
    @if (Auth::user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! Auth::user()->hasVerifiedEmail())
        <div class="mt-2 flex flex-col gap-2">
            <div class="text-[11px] flex items-center gap-1.5 text-red-500 font-bold uppercase tracking-tighter">
                <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                Email Belum Diverifikasi
            </div>
            
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="text-[10px] bg-ade-gold/10 text-ade-gold hover:bg-ade-gold hover:text-white border border-ade-gold/20 px-3 py-1 rounded-full transition-all font-bold uppercase tracking-widest">
                    Verifikasi Sekarang
                </button>
            </form>
        </div>
    @else
        <div class="mt-2 text-[11px] flex items-center gap-1.5 text-green-600 font-bold uppercase tracking-tighter">
            <span class="w-2 h-2 bg-green-500 rounded-full"></span>
            Email Terverifikasi
        </div>
    @endif
</div>

                    <div class="space-y-1 group">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Nomor WhatsApp</p>
                        <p class="text-gray-800 text-lg border-b border-gray-100 pb-2 group-hover:border-ade-gold transition-colors">
                            {{ Auth::user()->phone ?? 'Belum ditambahkan' }}
                        </p>
                    </div>

                    <div class="space-y-1 group">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Jenis Kelamin</p>
                        <p class="text-gray-800 text-lg border-b border-gray-100 pb-2 group-hover:border-ade-gold transition-colors">
                            {{ Auth::user()->gender ?? 'Belum diatur' }}
                        </p>
                    </div>

                    <div class="space-y-1 md:col-span-2 group">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Alamat Pengiriman</p>
                        <div class="text-gray-600 text-md bg-gray-50 p-5 rounded-2xl border border-dashed border-gray-200 leading-relaxed mt-2 group-hover:bg-ade-krem/30 transition-colors">
                            {{ Auth::user()->address ?? 'Anda belum menambahkan alamat pengiriman.' }}
                        </div>
                    </div>
                </div>

                <div class="mt-16 space-y-5">
                    <div class="flex flex-col md:flex-row gap-5">
                        <button onclick="openPasswordModal()" class="flex-1 bg-gray-900 hover:bg-black text-white px-8 py-5 rounded-[1.5rem] font-bold transition-all shadow-xl shadow-gray-200 active:scale-95 text-sm uppercase tracking-widest">
                            Ganti Password
                        </button>
                        
                        <form method="POST" action="{{ route('logout') }}" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full bg-red-50 hover:bg-red-100 text-red-500 px-8 py-5 rounded-[1.5rem] font-bold transition-all active:scale-95 text-sm uppercase tracking-widest">
                                Keluar Akun
                            </button>
                        </form>
                    </div>
                    
                    <a href="/orders" class="block w-full border-2 border-ade-gold text-ade-gold hover:bg-ade-gold hover:text-white px-8 py-5 rounded-[1.5rem] font-bold transition-all text-center text-sm uppercase tracking-[0.2em]">
                        Lihat Riwayat Pesanan Saya
                    </a>
                </div>
            </div>
        </div>
    </main>

    <div id="editModal" class="fixed inset-0 z-[110] hidden flex items-center justify-center bg-black/60 backdrop-blur-sm p-4 modal-transition">
        <div class="bg-white w-full max-w-md rounded-[2.5rem] overflow-hidden shadow-2xl transform">
            <div class="bg-ade-gold p-7 text-white flex justify-between items-center">
                <h3 class="font-serif-ade text-2xl">Edit Profil</h3>
                <button onclick="closeModal()" class="text-3xl leading-none hover:rotate-90 transition-transform">&times;</button>
            </div>
            
            <form action="{{ route('profile.update') }}" method="POST" class="p-10 space-y-6">
                @csrf
                @method('PATCH')
                <input type="hidden" name="email" value="{{ Auth::user()->email }}">

                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ Auth::user()->name }}" class="w-full border-b-2 border-gray-100 py-2 outline-none focus:border-ade-gold transition-colors font-medium text-gray-700" required>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Nomor WhatsApp</label>
                    <input type="text" name="phone" value="{{ Auth::user()->phone }}" class="w-full border-b-2 border-gray-100 py-2 outline-none focus:border-ade-gold transition-colors font-medium text-gray-700">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Jenis Kelamin</label>
                    <div class="flex gap-6 mt-2">
                        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                            <input type="radio" name="gender" value="Laki-laki" class="accent-ade-gold" {{ Auth::user()->gender == 'Laki-laki' ? 'checked' : '' }}> Laki-laki
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                            <input type="radio" name="gender" value="Perempuan" class="accent-ade-gold" {{ Auth::user()->gender == 'Perempuan' ? 'checked' : '' }}> Perempuan
                        </label>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Alamat Lengkap</label>
                    <textarea name="address" rows="3" class="w-full border-2 border-gray-100 rounded-2xl p-4 mt-2 outline-none focus:border-ade-gold transition-colors text-gray-700">{{ Auth::user()->address }}</textarea>
                </div>

                <button type="submit" class="w-full bg-ade-gold text-white py-5 rounded-2xl font-bold hover:bg-yellow-700 transition shadow-lg shadow-yellow-600/20 active:scale-95">
                    Simpan Perubahan
                </button>
            </form>
        </div>
    </div>

    <div id="passwordModal" class="fixed inset-0 z-[110] hidden flex items-center justify-center bg-black/60 backdrop-blur-sm p-4 modal-transition">
        <div class="bg-white w-full max-w-md rounded-[2.5rem] overflow-hidden shadow-2xl">
            <div class="bg-gray-900 p-7 text-white flex justify-between items-center">
                <h3 class="font-serif-ade text-2xl">Keamanan Akun</h3>
                <button onclick="closePasswordModal()" class="text-3xl leading-none hover:rotate-90 transition-transform">&times;</button>
            </div>
            
            <form action="{{ route('password.update') }}" method="POST" class="p-10 space-y-6">
                @csrf
                @method('put')
                
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Password Saat Ini</label>
                    <input type="password" name="current_password" class="w-full border-b-2 border-gray-100 py-2 outline-none focus:border-ade-gold transition-colors" required>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Password Baru</label>
                    <input type="password" name="password" class="w-full border-b-2 border-gray-100 py-2 outline-none focus:border-ade-gold transition-colors" required>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="w-full border-b-2 border-gray-100 py-2 outline-none focus:border-ade-gold transition-colors" required>
                </div>

                <button type="submit" class="w-full bg-gray-900 text-white py-5 rounded-2xl font-bold hover:bg-black transition shadow-xl active:scale-95 uppercase tracking-widest text-xs">
                    Perbarui Kata Sandi
                </button>
            </form>
        </div>
    </div>

    <script>
        function openModal() { 
            document.getElementById('editModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden'; 
        }
        function closeModal() { 
            document.getElementById('editModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function openPasswordModal() { 
            document.getElementById('passwordModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        function closePasswordModal() { 
            document.getElementById('passwordModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        window.onclick = function(event) {
            let editModal = document.getElementById('editModal');
            let passModal = document.getElementById('passwordModal');
            if (event.target == editModal) closeModal();
            if (event.target == passModal) closePasswordModal();
        }
    </script>
</body>
</html>

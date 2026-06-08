@extends('layouts.admin')

@section('content')
    <div class="mb-10">
        <a href="{{ route('admin.users.index') }}" class="text-indigo-400 hover:text-[#CFB53B] flex items-center gap-2 mb-4 font-bold text-sm transition-colors w-max">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Kembali ke Daftar Pelanggan
        </a>
        <h1 class="text-4xl font-black text-indigo-950 tracking-tighter uppercase italic">Tambah Pelanggan Baru</h1>
        <p class="text-indigo-400 font-medium italic mt-1">
            Tambahkan pelanggan baru ke sistem <span class="text-[#CFB53B] font-black underline">Ade Afwa Boutique</span>.
        </p>
    </div>

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-500/10 text-red-600 rounded-2xl border border-red-500/20 font-bold text-sm italic">
            <ul>
                @foreach($errors->all() as $error)
                    <li>🚨 {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-[2.5rem] shadow-[0_30px_60px_rgba(0,0,0,0.02)] border border-white p-8 md:p-12">
        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6 max-w-2xl">
            @csrf
            
            <div class="space-y-2">
                <label for="name" class="block text-sm font-black text-indigo-950 uppercase tracking-wider">Nama Lengkap</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                       class="w-full bg-[#F1FBFD]/50 px-6 py-4 rounded-2xl border border-indigo-50 focus:ring-2 focus:ring-[#CFB53B] focus:border-transparent outline-none transition-all text-sm font-bold text-indigo-950 placeholder-indigo-200"
                       placeholder="Masukkan nama lengkap">
            </div>

            <div class="space-y-2">
                <label for="email" class="block text-sm font-black text-indigo-950 uppercase tracking-wider">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                       class="w-full bg-[#F1FBFD]/50 px-6 py-4 rounded-2xl border border-indigo-50 focus:ring-2 focus:ring-[#CFB53B] focus:border-transparent outline-none transition-all text-sm font-bold text-indigo-950 placeholder-indigo-200"
                       placeholder="contoh@email.com">
            </div>

            <div class="space-y-2">
                <label for="phone" class="block text-sm font-black text-indigo-950 uppercase tracking-wider">Nomor WhatsApp</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                       class="w-full bg-[#F1FBFD]/50 px-6 py-4 rounded-2xl border border-indigo-50 focus:ring-2 focus:ring-[#CFB53B] focus:border-transparent outline-none transition-all text-sm font-bold text-indigo-950 placeholder-indigo-200"
                       placeholder="Contoh: +6281234567890">
                <p class="text-[10px] text-indigo-300 font-bold italic">*Opsional, gunakan format angka</p>
            </div>

            <div class="space-y-2">
                <label for="password" class="block text-sm font-black text-indigo-950 uppercase tracking-wider">Password</label>
                <input type="password" id="password" name="password" required minlength="8"
                       class="w-full bg-[#F1FBFD]/50 px-6 py-4 rounded-2xl border border-indigo-50 focus:ring-2 focus:ring-[#CFB53B] focus:border-transparent outline-none transition-all text-sm font-bold text-indigo-950 placeholder-indigo-200"
                       placeholder="Minimal 8 karakter">
            </div>

            <div class="pt-6">
                <button type="submit" 
                        class="w-full md:w-auto bg-[#CFB53B] hover:bg-[#b89f33] text-indigo-950 px-8 py-4 rounded-2xl font-black shadow-sm transition-all transform hover:-translate-y-1 flex items-center justify-center gap-3 text-sm uppercase tracking-widest">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Simpan Pelanggan
                </button>
            </div>
        </form>
    </div>
@endsection

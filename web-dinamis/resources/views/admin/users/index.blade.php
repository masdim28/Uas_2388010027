@extends('layouts.admin')

@section('content')
    {{-- Header Content --}}
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-4xl font-black text-indigo-950 tracking-tighter uppercase italic">Kelola Pelanggan</h1>
            <p class="text-indigo-400 font-medium italic mt-1">
                Daftar pelanggan <span class="text-[#CFB53B] font-black underline">Ade Afwa Boutique</span>.
            </p>
        </div>
        
        <div class="flex flex-wrap gap-4">
            {{-- Search Bar --}}
            <form action="{{ route('admin.users.index') }}" method="GET" class="relative group">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari nama atau email..." 
                       class="bg-white pl-12 pr-6 py-4 rounded-2xl border border-indigo-50 shadow-sm focus:ring-2 focus:ring-[#CFB53B] focus:border-transparent outline-none w-64 transition-all group-hover:shadow-md text-sm font-bold text-indigo-950">
                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-indigo-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </form>

            {{-- Tombol Tambah User --}}
            <a href="{{ route('admin.users.create') }}" 
               class="bg-[#CFB53B] hover:bg-[#b89f33] text-indigo-950 px-6 py-4 rounded-2xl font-black shadow-sm transition-all transform hover:-translate-y-1 flex items-center gap-3 text-xs uppercase tracking-widest">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Tambah User
            </a>

            <div class="bg-white px-6 py-4 rounded-[1.5rem] border border-indigo-50 shadow-sm flex items-center gap-4">
                <div class="bg-[#F1FBFD] p-2 rounded-xl text-xl">👥</div>
                <div>
                    <p class="text-[10px] font-black text-indigo-300 uppercase tracking-widest leading-none">Total</p>
                    <p class="text-lg font-black text-indigo-950">{{ $users->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Alert Success --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-500/10 text-emerald-600 rounded-2xl border border-emerald-500/20 font-bold text-sm italic">
            ✨ {{ session('success') }}
        </div>
    @endif

    {{-- Alert Error / Tidak Ditemukan --}}
    @if(session('error'))
        <div class="mb-6 p-4 bg-rose-500/10 text-rose-600 rounded-2xl border border-rose-500/20 font-bold text-sm italic">
            ⚠️ {{ session('error') }}
        </div>
    @endif

    {{-- Table Card --}}
    <div class="bg-white rounded-[2.5rem] shadow-[0_30px_60px_rgba(0,0,0,0.02)] border border-white overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-indigo-50/50 text-indigo-900 uppercase text-[10px] font-black tracking-[0.2em]">
                        <th class="px-8 py-6 text-center">Peringkat</th>
                        <th class="px-8 py-6">Profil Pelanggan</th>
                        <th class="px-8 py-6">Informasi Kontak</th>
                        <th class="px-8 py-6 text-center">Checkout</th>
                        <th class="px-8 py-6">Status Akun</th>
                        <th class="px-8 py-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($users as $index => $user)
                    @php
                        // Cek apakah data ini merupakan hasil pencarian yang cocok (fleksibel case-insensitive)
                        $searchKeyword = strtolower(request('search'));
                        $isSearched = request('search') && (
                            str_contains(strtolower($user->name), $searchKeyword) || 
                            str_contains(strtolower($user->email), $searchKeyword)
                        );
                    @endphp
                    
                    {{-- Jika cocok saat dicari, background bar berganti menjadi gelap dan diberi border samping emas --}}
                    <tr class="{{ $isSearched ? 'bg-indigo-950/10 border-l-4 border-[#CFB53B]' : 'hover:bg-[#F1FBFD]/50' }} transition-all group">
                        {{-- Peringkat --}}
                        <td class="px-8 py-6 text-center">
                            @if($index == 0)
                                <span class="text-2xl">🥇</span>
                            @elseif($index == 1)
                                <span class="text-2xl">🥈</span>
                            @else
                                <span class="text-indigo-200 font-black italic group-hover:text-indigo-400 {{ $isSearched ? 'text-indigo-950 font-black' : '' }}">
                                    #{{ $index + 1 }}
                                </span>
                            @endif
                        </td>

                        {{-- Profil --}}
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-500 font-black text-xs uppercase shadow-inner">
                                    {{ substr($user->name, 0, 2) }}
                                </div>
                                <div>
                                    <p class="font-black text-indigo-950 uppercase tracking-tighter">{{ $user->name }}</p>
                                    <p class="text-[9px] text-indigo-300 font-bold uppercase tracking-widest">Sejak {{ $user->created_at->format('M Y') }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Kontak & WA --}}
                        <td class="px-8 py-6">
                            <div class="flex flex-col gap-1">
                                <span class="text-xs font-bold text-indigo-900">{{ $user->email }}</span>
                                <span class="text-[10px] text-gray-400 font-medium">{{ $user->phone ?? 'No WA tidak ada' }}</span>
                            </div>
                        </td>

                        {{-- Checkout --}}
                        <td class="px-8 py-6 text-center">
                            <span class="bg-white border group-hover:border-[#CFB53B] text-indigo-950 px-4 py-1.5 rounded-xl font-black text-[10px] transition-all shadow-sm">
                                {{ $user->total_checkout ?? 0 }} <span class="text-[#CFB53B] italic">Items</span>
                            </span>
                        </td>

                        {{-- Status Akun --}}
                        <td class="px-8 py-6">
                            @if($user->is_blocked)
                                <span class="px-3 py-1 bg-red-50 text-red-600 text-[9px] font-black rounded-lg uppercase border border-red-100">Blocked</span>
                            @else
                                <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[9px] font-black rounded-lg uppercase border border-emerald-100">Active</span>
                            @endif
                        </td>

                        {{-- Aksi --}}
                        <td class="px-8 py-6">
                            <div class="flex justify-center items-center gap-2">
                                {{-- Chat WhatsApp --}}
                                @if($user->phone)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $user->phone) }}" target="_blank" 
                                       class="p-2 bg-emerald-50 text-emerald-600 rounded-xl hover:bg-emerald-600 hover:text-white transition shadow-sm" title="Chat WA">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                        </svg>
                                    </a>
                                @endif

                                {{-- Blokir/Unblokir --}}
                                <form action="{{ route('admin.users.toggle-block', $user->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="p-2 {{ $user->is_blocked ? 'bg-indigo-50 text-indigo-600 hover:bg-indigo-600' : 'bg-orange-50 text-orange-600 hover:bg-orange-600' }} rounded-xl hover:text-white transition shadow-sm" title="{{ $user->is_blocked ? 'Buka Blokir' : 'Blokir User' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636" />
                                        </svg>
                                    </button>
                                </form>

                                {{-- Hapus --}}
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus akun ini secara permanen?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition shadow-sm" title="Hapus User">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-20 text-center">
                            <p class="text-indigo-300 font-black italic uppercase tracking-widest">User tidak ditemukan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
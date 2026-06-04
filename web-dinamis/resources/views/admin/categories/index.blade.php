@extends('layouts.admin')

@section('content')
    <div class="max-w-5xl mx-auto">

        {{-- Header --}}
        <div class="mb-10">
            <h1 class="text-4xl font-black text-indigo-950 tracking-tighter uppercase italic">Kelola Kategori</h1>
            <p class="text-indigo-400 font-medium italic mt-1">Tambah, edit, atau hapus sub-kategori untuk setiap kategori
                utama butik.</p>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mb-8 bg-emerald-50 border-l-4 border-emerald-500 p-5 rounded-2xl shadow-sm flex items-center gap-3">
                <span class="text-emerald-500 text-lg">✅</span>
                <p class="text-sm font-bold text-emerald-800">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-8 bg-red-50 border-l-4 border-red-500 p-5 rounded-2xl shadow-sm flex items-center gap-3">
                <span class="text-red-500 text-lg">⚠️</span>
                <p class="text-sm font-bold text-red-800">{{ session('error') }}</p>
            </div>
        @endif

        {{-- Category Cards --}}
        <div class="space-y-6">
            @foreach($categories as $parent)
                <div class="bg-white rounded-[2rem] shadow-[0_20px_50px_rgba(0,0,0,0.03)] border border-white overflow-hidden">

                    {{-- Category Header --}}
                    <div class="bg-[#F5F5E4] px-8 py-5 flex items-center justify-between border-b border-indigo-50">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-indigo-950 rounded-xl flex items-center justify-center shadow-lg">
                                <span class="text-white font-black text-sm">{{ substr($parent->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <h3 class="text-indigo-950 font-black uppercase tracking-wider text-sm">{{ $parent->name }}</h3>
                                <p class="text-indigo-500 text-[10px] font-bold uppercase tracking-widest">
                                    {{ $parent->children->count() }} Sub-Kategori
                                </p>
                            </div>
                        </div>

                        @if($parent->children->count() > 0)
                            <span
                                class="bg-indigo-950 text-white px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest">
                                {{ $parent->children->sum('products_count') }} Produk
                            </span>
                        @endif
                    </div>

                    {{-- Sub-Categories List --}}
                    <div class="p-6">
                        @if($parent->children->count() > 0)
                            <div class="space-y-3 mb-6">
                                @foreach($parent->children as $child)
                                    <div
                                        class="group flex items-center justify-between bg-[#F1FBFD]/50 hover:bg-[#F1FBFD] p-4 rounded-2xl border border-indigo-50 transition-all">

                                        {{-- Display Mode --}}
                                        <div class="flex items-center gap-3" id="display-{{ $child->id }}">
                                            <div class="w-2 h-2 rounded-full bg-[#CFB53B]"></div>
                                            <span
                                                class="text-sm font-bold text-indigo-950 uppercase tracking-tight">{{ $child->name }}</span>
                                            @if($child->products_count > 0)
                                                <span
                                                    class="bg-indigo-100 text-indigo-600 px-2 py-0.5 rounded-lg text-[9px] font-black uppercase">
                                                    {{ $child->products_count }} produk
                                                </span>
                                            @endif
                                        </div>

                                        {{-- Edit Mode (hidden by default) --}}
                                        <form action="{{ route('admin.categories.update', $child->id) }}" method="POST"
                                            class="hidden items-center gap-2 flex-1" id="edit-form-{{ $child->id }}">
                                            @csrf
                                            @method('PUT')
                                            <input type="text" name="name" value="{{ $child->name }}"
                                                class="flex-1 px-4 py-2.5 rounded-xl bg-white border border-indigo-200 text-sm font-bold text-indigo-950 focus:ring-2 focus:ring-[#CFB53B] outline-none">
                                            <button type="submit"
                                                class="bg-[#CFB53B] text-indigo-950 px-4 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-wider hover:bg-yellow-500 transition-all">
                                                Simpan
                                            </button>
                                            <button type="button" onclick="cancelEdit({{ $child->id }})"
                                                class="bg-gray-100 text-gray-500 px-4 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-wider hover:bg-gray-200 transition-all">
                                                Batal
                                            </button>
                                        </form>

                                        {{-- Action Buttons --}}
                                        <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity"
                                            id="actions-{{ $child->id }}">
                                            <button type="button" onclick="startEdit({{ $child->id }})"
                                                class="bg-indigo-50 text-indigo-600 px-3 py-2 rounded-xl text-[9px] font-black uppercase tracking-wider hover:bg-indigo-100 transition-all flex items-center gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Edit
                                            </button>
                                            <form action="{{ route('admin.categories.destroy', $child->id) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus sub-kategori \'{{ $child->name }}\'?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="bg-red-50 text-red-500 px-3 py-2 rounded-xl text-[9px] font-black uppercase tracking-wider hover:bg-red-100 transition-all flex items-center gap-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-6 mb-4">
                                <p class="text-indigo-200 text-xs font-bold uppercase tracking-widest italic">Belum ada sub-kategori
                                </p>
                            </div>
                        @endif

                        {{-- Add New Sub-Category Form --}}
                        <form action="{{ route('admin.categories.store') }}" method="POST" class="flex items-center gap-3">
                            @csrf
                            <input type="hidden" name="parent_id" value="{{ $parent->id }}">
                            <div class="flex-1 relative">
                                <input type="text" name="name" required placeholder="Ketik nama sub-kategori baru..."
                                    class="w-full px-5 py-3.5 rounded-2xl bg-gray-50 border-2 border-dashed border-indigo-100 text-sm font-bold text-indigo-950 focus:ring-2 focus:ring-[#CFB53B] focus:border-transparent outline-none placeholder:text-indigo-200 placeholder:italic transition-all">
                            </div>
                            <button type="submit"
                                class="bg-indigo-950 text-white px-6 py-3.5 rounded-2xl text-[10px] font-black uppercase tracking-[0.15em] hover:bg-[#CFB53B] hover:text-indigo-950 transition-all active:scale-95 shadow-lg shadow-indigo-100/50 flex items-center gap-2 whitespace-nowrap">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah Sub
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <p class="text-center mt-10 text-[10px] font-black text-indigo-200 uppercase tracking-[0.5em]">DAMandiri Project •
            Informatics 2388010027</p>
    </div>

    <script>
        function startEdit(id) {
            document.getElementById('display-' + id).classList.add('hidden');
            document.getElementById('actions-' + id).classList.add('hidden');
            document.getElementById('edit-form-' + id).classList.remove('hidden');
            document.getElementById('edit-form-' + id).classList.add('flex');
            document.getElementById('edit-form-' + id).querySelector('input[name="name"]').focus();
        }

        function cancelEdit(id) {
            document.getElementById('display-' + id).classList.remove('hidden');
            document.getElementById('actions-' + id).classList.remove('hidden');
            document.getElementById('edit-form-' + id).classList.add('hidden');
            document.getElementById('edit-form-' + id).classList.remove('flex');
        }
    </script>
@endsection
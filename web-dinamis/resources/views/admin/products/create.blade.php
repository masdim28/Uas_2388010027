@extends('layouts.admin')

@section('content')
    {{-- TomSelect CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <style>
        .ts-control { border-radius: 1.25rem !important; padding: 1rem 1.5rem !important; border: none !important; background-color: #F1FBFD !important; box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.03) !important; }
        .ts-control .item { background-color: #CFB53B !important; color: #1e1b4b !important; border-radius: 0.75rem !important; font-weight: 800; font-size: 10px; text-transform: uppercase; padding: 4px 12px !important; }
        .ts-dropdown { border-radius: 1.25rem !important; border: 1px solid #e0e7ff !important; box-shadow: 0 20px 40px -5px rgba(0, 0, 0, 0.05) !important; margin-top: 8px !important; }
        .ts-dropdown .active { background-color: #F1FBFD !important; color: #4338ca !important; }
        .variant-card { transition: all 0.3s ease; animation: fadeInDown 0.3s ease; }
        @keyframes fadeInDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        .variant-img-preview { width: 60px; height: 60px; object-fit: cover; border-radius: 0.75rem; border: 2px solid #e0e7ff; display: none; }
    </style>

    <div class="max-w-4xl mx-auto">
        {{-- Header --}}
        <div class="mb-10">
            <h1 class="text-4xl font-black text-indigo-950 tracking-tighter uppercase italic">Tambah Produk Baru</h1>
            <p class="text-indigo-400 font-medium italic mt-1">Lengkapi detail di bawah untuk menambah katalog eksklusif butik.</p>
        </div>

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-6 rounded-2xl mb-8 shadow-sm">
                <div class="flex items-center gap-3 mb-2">
                    <span class="text-red-500 font-black text-lg">⚠️</span>
                    <p class="font-black text-red-800 uppercase tracking-widest text-xs">Gagal Menyimpan Data:</p>
                </div>
                <ul class="list-disc list-inside text-xs text-red-600/80 font-medium space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form Card --}}
        <div class="bg-white rounded-[2.5rem] shadow-[0_30px_60px_rgba(0,0,0,0.02)] p-10 border border-white">
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf

                {{-- Product Name --}}
                <div>
                    <label class="block text-[10px] font-black text-indigo-300 uppercase tracking-[0.2em] mb-3 ml-2">Nama Koleksi Eksklusif</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           placeholder="Contoh: Gamis Silk Premium"
                           class="w-full px-6 py-5 rounded-2xl bg-[#F1FBFD]/50 border-none focus:ring-2 focus:ring-[#CFB53B] outline-none text-indigo-950 font-bold shadow-inner transition-all" required>
                </div>

                {{-- Categories --}}
                <div>
                    <label class="block text-[10px] font-black text-indigo-300 uppercase tracking-[0.2em] mb-3 ml-2">Kategori (Maks. 3)</label>
                    <select id="category-select" name="category_ids[]" multiple placeholder="Pilih kategori..." class="w-full shadow-inner" required>
                        @foreach($categories as $parent)
                            <option value="{{ $parent->id }}" {{ (collect(old('category_ids'))->contains($parent->id)) ? 'selected' : '' }}>
                                📂 {{ strtoupper($parent->name) }}
                            </option>
                            @foreach($parent->children as $child)
                                <option value="{{ $child->id }}" {{ (collect(old('category_ids'))->contains($child->id)) ? 'selected' : '' }}>
                                    └─ {{ $child->name }}
                                </option>
                            @endforeach
                        @endforeach
                    </select>
                </div>

                {{-- VARIANT SECTION --}}
                <div class="p-8 bg-gray-50 rounded-[2rem] border border-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <label class="block text-[10px] font-black text-indigo-400 uppercase tracking-[0.2em]">Manajemen Varian Produk</label>
                            <p class="text-[9px] text-indigo-300 mt-1">Setiap varian memiliki harga, berat, stok, status, dan foto sendiri.</p>
                        </div>
                        <button type="button" onclick="addVariantCard()" class="bg-[#CFB53B] text-indigo-950 px-5 py-2.5 rounded-xl font-black text-[9px] uppercase tracking-wider hover:bg-indigo-950 hover:text-white transition-all">
                            + Tambah Varian
                        </button>
                    </div>

                    <div id="variant-container" class="space-y-4">
                        {{-- Default first variant card --}}
                        <div class="variant-card p-6 bg-white rounded-2xl shadow-sm border border-gray-100">
                            <div class="flex justify-between items-center mb-5">
                                <span class="text-[10px] font-black text-indigo-400 uppercase tracking-widest">Varian #1 <span class="text-[#CFB53B]">● Wajib</span></span>
                            </div>
                            {{-- Row 1: Identitas Varian --}}
                            <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-4">
                                <div>
                                    <label class="text-[8px] font-black text-indigo-300 uppercase tracking-wider mb-1.5 block ml-1">Warna</label>
                                    <input type="text" name="variants[0][color]" placeholder="Merah" class="w-full px-3 py-3 rounded-xl bg-gray-50 border-none text-xs font-bold focus:ring-2 focus:ring-[#CFB53B] outline-none">
                                </div>
                                <div>
                                    <label class="text-[8px] font-black text-indigo-300 uppercase tracking-wider mb-1.5 block ml-1">Ukuran</label>
                                    <input type="text" name="variants[0][size]" placeholder="XL" class="w-full px-3 py-3 rounded-xl bg-gray-50 border-none text-xs font-bold focus:ring-2 focus:ring-[#CFB53B] outline-none">
                                </div>
                                <div>
                                    <label class="text-[8px] font-black text-indigo-300 uppercase tracking-wider mb-1.5 block ml-1">Harga (IDR)</label>
                                    <input type="number" name="variants[0][price]" placeholder="250000" class="w-full px-3 py-3 rounded-xl bg-gray-50 border-none text-xs font-black text-[#CFB53B] focus:ring-2 focus:ring-[#CFB53B] outline-none" required>
                                </div>
                                <div>
                                    <label class="text-[8px] font-black text-indigo-300 uppercase tracking-wider mb-1.5 block ml-1">Berat (Gram)</label>
                                    <input type="number" name="variants[0][weight]" placeholder="500" value="500" class="w-full px-3 py-3 rounded-xl bg-gray-50 border-none text-xs font-bold focus:ring-2 focus:ring-[#CFB53B] outline-none" required>
                                </div>
                                <div>
                                    <label class="text-[8px] font-black text-indigo-300 uppercase tracking-wider mb-1.5 block ml-1">Stok</label>
                                    <input type="number" name="variants[0][stock]" placeholder="10" class="w-full px-3 py-3 rounded-xl bg-gray-50 border-none text-xs font-bold focus:ring-2 focus:ring-[#CFB53B] outline-none" required>
                                </div>
                            </div>
                            {{-- Row 2: Status & Foto --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="text-[8px] font-black text-indigo-300 uppercase tracking-wider mb-1.5 block ml-1">Status Stok</label>
                                    <select name="variants[0][status]" class="w-full px-3 py-3 rounded-xl bg-gray-50 border-none text-xs font-black uppercase tracking-widest focus:ring-2 focus:ring-[#CFB53B] outline-none appearance-none cursor-pointer">
                                        <option value="ready">🟢 Ready Stock</option>
                                        <option value="sold_out">🔴 Sold Out</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-[8px] font-black text-indigo-300 uppercase tracking-wider mb-1.5 block ml-1">Foto Varian (opsional)</label>
                                    <div class="flex items-center gap-3">
                                        <img class="variant-img-preview" id="preview-0" alt="preview">
                                        <input type="file" name="variants[0][image]" accept="image/*"
                                               onchange="previewVariantImg(this, 'preview-0')"
                                               class="block w-full text-[10px] text-indigo-300 file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[9px] file:font-black file:bg-[#CFB53B]/10 file:text-[#CFB53B] hover:file:bg-[#CFB53B]/20 cursor-pointer">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Foto Utama Produk --}}
                <div>
                    <label class="block text-[10px] font-black text-indigo-300 uppercase tracking-[0.2em] mb-3 ml-2">Foto Utama Produk (1–5 Foto)</label>
                    <p class="text-[9px] text-indigo-200 mb-4 ml-2 italic">Foto ini tampil sebagai galeri utama produk. Foto per-varian bersifat tambahan.</p>
                    <div class="p-10 bg-[#F1FBFD]/30 rounded-[2.5rem] border-2 border-dashed border-indigo-100 flex flex-col items-center group hover:border-[#CFB53B] transition-colors">
                        <div class="mb-4 p-4 bg-white rounded-full shadow-sm text-indigo-200 group-hover:text-[#CFB53B] transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <input type="file" name="images[]" multiple accept="image/*"
                               class="block w-full text-xs text-indigo-300 file:mr-4 file:py-2 file:px-6 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-[#CFB53B] file:text-indigo-950 hover:file:bg-[#b89f33] cursor-pointer">
                        <p class="mt-4 text-[9px] text-indigo-300 italic font-medium uppercase tracking-widest">Tahan 'Ctrl' untuk memilih banyak foto sekaligus</p>
                    </div>
                </div>

                {{-- Description --}}
                <div>
                    <label class="block text-[10px] font-black text-indigo-300 uppercase tracking-[0.2em] mb-3 ml-2">Deskripsi Produk</label>
                    <textarea name="description" rows="4"
                              class="w-full px-6 py-5 rounded-2xl bg-[#F1FBFD]/50 border-none focus:ring-2 focus:ring-[#CFB53B] outline-none text-indigo-900 font-medium italic shadow-inner transition-all"
                              placeholder="Jelaskan keanggunan bahan dan detail desain produk ini...">{{ old('description') }}</textarea>
                </div>

                {{-- Action Buttons --}}
                <div class="pt-6 flex flex-col md:flex-row gap-4">
                    <button type="submit" class="flex-1 bg-indigo-950 text-white py-5 rounded-[1.5rem] font-black text-xs uppercase tracking-[0.3em] hover:bg-[#CFB53B] hover:text-indigo-950 shadow-2xl shadow-indigo-100 transition-all active:scale-95 transform">
                        Simpan Koleksi
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="px-10 py-5 bg-white text-indigo-300 rounded-[1.5rem] font-black text-xs uppercase tracking-[0.3em] text-center hover:bg-red-50 hover:text-red-400 transition-all border border-indigo-50">
                        Batal
                    </a>
                </div>
            </form>
        </div>

        <p class="text-center mt-10 text-[10px] font-black text-indigo-200 uppercase tracking-[0.5em]">DAMandiri Project • Informatics 2388010027</p>
    </div>

    {{-- TomSelect JS --}}
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            new TomSelect("#category-select", {
                maxItems: 3,
                plugins: ['remove_button'],
                persist: false,
                create: false,
                render: {
                    option: function(data, escape) {
                        return '<div class="px-4 py-2 font-bold text-xs uppercase tracking-tight">' + escape(data.text) + '</div>';
                    },
                    item: function(data, escape) {
                        return '<div class="flex items-center">' + escape(data.text) + '</div>';
                    }
                }
            });
        });

        let variantIdx = 1;

        function previewVariantImg(input, previewId) {
            const preview = document.getElementById(previewId);
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function addVariantCard() {
            const container = document.getElementById('variant-container');
            const idx = variantIdx;
            const previewId = `preview-${idx}`;

            const card = document.createElement('div');
            card.className = 'variant-card p-6 bg-white rounded-2xl shadow-sm border border-indigo-50 space-y-4';
            card.innerHTML = `
                <div class="flex justify-between items-center">
                    <span class="text-[10px] font-black text-indigo-400 uppercase tracking-widest">Varian #${idx + 1}</span>
                    <button type="button" onclick="this.closest('.variant-card').remove()"
                        class="text-red-300 hover:text-red-500 transition-colors p-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                    <div>
                        <label class="text-[8px] font-black text-indigo-300 uppercase tracking-wider mb-1.5 block ml-1">Warna</label>
                        <input type="text" name="variants[${idx}][color]" placeholder="Merah" class="w-full px-3 py-3 rounded-xl bg-gray-50 border-none text-xs font-bold focus:ring-2 focus:ring-[#CFB53B] outline-none">
                    </div>
                    <div>
                        <label class="text-[8px] font-black text-indigo-300 uppercase tracking-wider mb-1.5 block ml-1">Ukuran</label>
                        <input type="text" name="variants[${idx}][size]" placeholder="XL" class="w-full px-3 py-3 rounded-xl bg-gray-50 border-none text-xs font-bold focus:ring-2 focus:ring-[#CFB53B] outline-none">
                    </div>
                    <div>
                        <label class="text-[8px] font-black text-indigo-300 uppercase tracking-wider mb-1.5 block ml-1">Harga (IDR)</label>
                        <input type="number" name="variants[${idx}][price]" placeholder="250000" class="w-full px-3 py-3 rounded-xl bg-gray-50 border-none text-xs font-black text-[#CFB53B] focus:ring-2 focus:ring-[#CFB53B] outline-none" required>
                    </div>
                    <div>
                        <label class="text-[8px] font-black text-indigo-300 uppercase tracking-wider mb-1.5 block ml-1">Berat (Gram)</label>
                        <input type="number" name="variants[${idx}][weight]" placeholder="500" value="500" class="w-full px-3 py-3 rounded-xl bg-gray-50 border-none text-xs font-bold focus:ring-2 focus:ring-[#CFB53B] outline-none" required>
                    </div>
                    <div>
                        <label class="text-[8px] font-black text-indigo-300 uppercase tracking-wider mb-1.5 block ml-1">Stok</label>
                        <input type="number" name="variants[${idx}][stock]" placeholder="10" value="0" class="w-full px-3 py-3 rounded-xl bg-gray-50 border-none text-xs font-bold focus:ring-2 focus:ring-[#CFB53B] outline-none" required>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="text-[8px] font-black text-indigo-300 uppercase tracking-wider mb-1.5 block ml-1">Status Stok</label>
                        <select name="variants[${idx}][status]" class="w-full px-3 py-3 rounded-xl bg-gray-50 border-none text-xs font-black uppercase tracking-widest focus:ring-2 focus:ring-[#CFB53B] outline-none appearance-none cursor-pointer">
                            <option value="ready">🟢 Ready Stock</option>
                            <option value="sold_out">🔴 Sold Out</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[8px] font-black text-indigo-300 uppercase tracking-wider mb-1.5 block ml-1">Foto Varian (opsional)</label>
                        <div class="flex items-center gap-3">
                            <img class="variant-img-preview" id="${previewId}" alt="preview">
                            <input type="file" name="variants[${idx}][image]" accept="image/*"
                                   onchange="previewVariantImg(this, '${previewId}')"
                                   class="block w-full text-[10px] text-indigo-300 file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[9px] file:font-black file:bg-[#CFB53B]/10 file:text-[#CFB53B] hover:file:bg-[#CFB53B]/20 cursor-pointer">
                        </div>
                    </div>
                </div>
            `;
            container.appendChild(card);
            variantIdx++;
        }
    </script>
@endsection
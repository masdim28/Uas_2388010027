@extends('layouts.admin')

@section('content')
    <style>
        .variant-card { transition: all 0.3s ease; animation: fadeInDown 0.3s ease; }
        @keyframes fadeInDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        .variant-img-preview { width: 60px; height: 60px; object-fit: cover; border-radius: 0.75rem; border: 2px solid #e0e7ff; }
    </style>

    <div class="max-w-4xl mx-auto">
        {{-- Header Section --}}
        <div class="mb-10 flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-black text-indigo-950 tracking-tighter uppercase italic">Perbarui Koleksi</h1>
                <p class="text-indigo-400 font-medium italic mt-1">
                    Sedang mengubah: <span class="text-indigo-900 font-black underline decoration-[#CFB53B] decoration-2">{{ $product->name }}</span>
                </p>
            </div>
            <a href="{{ route('admin.products.index') }}" class="bg-white p-3 rounded-2xl border border-indigo-50 shadow-sm text-gray-400 hover:text-red-500 transition-all hover:rotate-90 group">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </a>
        </div>

        {{-- Validasi Error Alert --}}
        @if ($errors->any())
            <div class="mb-8 bg-red-50 border-l-4 border-red-500 p-4 rounded-xl shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-bold text-red-800 uppercase tracking-wider">Terjadi kesalahan input:</h3>
                        <ul class="mt-1 list-disc list-inside text-xs text-red-700 font-medium">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        {{-- Form Card --}}
        <div class="bg-white rounded-[2.5rem] shadow-[0_30px_60px_rgba(0,0,0,0.02)] p-10 border border-white">
            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-10">
                @csrf
                @method('PUT')

                {{-- Product Name --}}
                <div>
                    <label class="block text-[10px] font-black text-indigo-300 uppercase tracking-[0.2em] mb-3 ml-2">Nama Koleksi Eksklusif</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}"
                           class="w-full px-6 py-5 rounded-2xl bg-[#F1FBFD]/50 border-none focus:ring-2 focus:ring-[#CFB53B] outline-none text-indigo-950 font-bold shadow-inner transition-all" required>
                </div>

                {{-- Category Selection --}}
                <div class="pt-4">
                    <label class="block text-[10px] font-black text-indigo-300 uppercase tracking-[0.2em] mb-4 ml-2">Pilih Kategori Koleksi</label>
                    <div class="space-y-6">
                        @foreach($categories as $parent)
                            <div>
                                <h4 class="text-[9px] font-bold text-indigo-950 uppercase mb-3 ml-2 opacity-50">{{ $parent->name }}</h4>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                    {{-- Parent Category --}}
                                    <label class="relative group cursor-pointer">
                                        <input type="checkbox" name="category_ids[]" value="{{ $parent->id }}"
                                            {{ $product->categories->contains($parent->id) ? 'checked' : '' }}
                                            class="peer hidden">
                                        <div class="p-4 rounded-2xl bg-[#F1FBFD]/50 border border-transparent peer-checked:border-[#CFB53B] peer-checked:bg-white peer-checked:shadow-md transition-all flex items-center justify-between group-hover:bg-white">
                                            <span class="text-[10px] font-black text-indigo-950 uppercase tracking-tighter">{{ $parent->name }}</span>
                                            <div class="w-4 h-4 rounded-full border-2 border-indigo-100 peer-checked:bg-[#CFB53B] peer-checked:border-[#CFB53B] flex items-center justify-center transition-all">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-2 w-2 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7" /></svg>
                                            </div>
                                        </div>
                                    </label>
                                    {{-- Sub Categories --}}
                                    @foreach($parent->children as $child)
                                        <label class="relative group cursor-pointer">
                                            <input type="checkbox" name="category_ids[]" value="{{ $child->id }}"
                                                {{ $product->categories->contains($child->id) ? 'checked' : '' }}
                                                class="peer hidden">
                                            <div class="p-4 rounded-2xl bg-[#F1FBFD]/30 border border-transparent peer-checked:border-[#CFB53B] peer-checked:bg-white peer-checked:shadow-md transition-all flex items-center justify-between group-hover:bg-white">
                                                <span class="text-[10px] font-bold text-indigo-800 uppercase tracking-tighter">{{ $child->name }}</span>
                                                <div class="w-4 h-4 rounded-full border-2 border-indigo-50 peer-checked:bg-[#CFB53B] peer-checked:border-[#CFB53B] flex items-center justify-center transition-all">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-2 w-2 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7" /></svg>
                                                </div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Variant Management Section --}}
                <div class="p-8 bg-gray-50 rounded-[2rem] border border-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <label class="block text-[10px] font-black text-indigo-400 uppercase tracking-[0.2em]">Manajemen Varian Produk</label>
                            <p class="text-[9px] text-indigo-300 mt-1">Setiap varian memiliki harga, berat, stok, status, dan foto sendiri.</p>
                        </div>
                        <button type="button" onclick="addVariant()" class="text-[9px] font-black bg-[#CFB53B] text-indigo-950 px-5 py-2.5 rounded-full uppercase tracking-wider hover:bg-indigo-950 hover:text-white transition-all">
                            + Tambah Varian Baru
                        </button>
                    </div>

                    <div id="variant-container" class="space-y-4">
                        @foreach($product->variants as $index => $variant)
                        <div class="variant-card p-6 bg-white rounded-2xl shadow-sm border border-indigo-50">
                            <input type="hidden" name="variants[{{ $index }}][id]" value="{{ $variant->id }}">
                            <div class="flex justify-between items-center mb-5">
                                <span class="text-[10px] font-black text-indigo-400 uppercase tracking-widest">Varian #{{ $index + 1 }}</span>
                                <button type="button" onclick="this.closest('.variant-card').remove()" class="text-red-300 hover:text-red-500 transition-colors p-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                            {{-- Row 1: Identitas --}}
                            <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-4">
                                <div>
                                    <label class="text-[8px] font-black text-indigo-300 uppercase tracking-wider mb-1.5 block ml-1">Warna</label>
                                    <input type="text" name="variants[{{ $index }}][color]" value="{{ old("variants.$index.color", $variant->color) }}" class="w-full px-3 py-3 rounded-xl bg-gray-50 border-none text-xs font-bold focus:ring-2 focus:ring-[#CFB53B] outline-none" placeholder="Sage Green">
                                </div>
                                <div>
                                    <label class="text-[8px] font-black text-indigo-300 uppercase tracking-wider mb-1.5 block ml-1">Ukuran</label>
                                    <input type="text" name="variants[{{ $index }}][size]" value="{{ old("variants.$index.size", $variant->size) }}" class="w-full px-3 py-3 rounded-xl bg-gray-50 border-none text-xs font-bold focus:ring-2 focus:ring-[#CFB53B] outline-none" placeholder="XL / 42">
                                </div>
                                <div>
                                    <label class="text-[8px] font-black text-indigo-300 uppercase tracking-wider mb-1.5 block ml-1">Harga (IDR)</label>
                                    <input type="number" name="variants[{{ $index }}][price]" value="{{ old("variants.$index.price", $variant->price) }}" class="w-full px-3 py-3 rounded-xl bg-gray-50 border-none text-xs font-black text-[#CFB53B] focus:ring-2 focus:ring-[#CFB53B] outline-none" required>
                                </div>
                                <div>
                                    <label class="text-[8px] font-black text-indigo-300 uppercase tracking-wider mb-1.5 block ml-1">Berat (Gram)</label>
                                    <input type="number" name="variants[{{ $index }}][weight]" value="{{ old("variants.$index.weight", $variant->weight) }}" class="w-full px-3 py-3 rounded-xl bg-gray-50 border-none text-xs font-bold focus:ring-2 focus:ring-[#CFB53B] outline-none" required>
                                </div>
                                <div>
                                    <label class="text-[8px] font-black text-indigo-300 uppercase tracking-wider mb-1.5 block ml-1">Stok</label>
                                    <input type="number" name="variants[{{ $index }}][stock]" value="{{ old("variants.$index.stock", $variant->stock) }}" class="w-full px-3 py-3 rounded-xl bg-gray-50 border-none text-xs font-bold focus:ring-2 focus:ring-[#CFB53B] outline-none" required>
                                </div>
                            </div>
                            {{-- Row 2: Status & Foto --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="text-[8px] font-black text-indigo-300 uppercase tracking-wider mb-1.5 block ml-1">Status Stok</label>
                                    <select name="variants[{{ $index }}][status]" class="w-full px-3 py-3 rounded-xl bg-gray-50 border-none text-xs font-black uppercase tracking-widest focus:ring-2 focus:ring-[#CFB53B] outline-none appearance-none cursor-pointer">
                                        <option value="ready" {{ old("variants.$index.status", $variant->status) == 'ready' ? 'selected' : '' }}>🟢 Ready Stock</option>
                                        <option value="sold_out" {{ old("variants.$index.status", $variant->status) == 'sold_out' ? 'selected' : '' }}>🔴 Sold Out</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-[8px] font-black text-indigo-300 uppercase tracking-wider mb-1.5 block ml-1">Foto Varian</label>
                                    <div class="flex items-center gap-3">
                                        @if($variant->image)
                                            <img src="{{ asset('storage/' . $variant->image) }}"
                                                 id="edit-preview-{{ $index }}"
                                                 class="variant-img-preview" alt="foto varian">
                                        @else
                                            <img id="edit-preview-{{ $index }}" class="variant-img-preview" style="display:none;" alt="preview">
                                        @endif
                                        <input type="file" name="variants[{{ $index }}][image]" accept="image/*"
                                               onchange="previewVariantImg(this, 'edit-preview-{{ $index }}')"
                                               class="block w-full text-[10px] text-indigo-300 file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[9px] file:font-black file:bg-[#CFB53B]/10 file:text-[#CFB53B] hover:file:bg-[#CFB53B]/20 cursor-pointer">
                                    </div>
                                    @if($variant->image)
                                        <p class="text-[8px] text-indigo-300 mt-1 ml-1 italic">Pilih file baru untuk mengganti foto varian ini.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Update Foto Utama Produk --}}
                <div>
                    <label class="block text-[10px] font-black text-indigo-300 uppercase tracking-[0.2em] mb-3 ml-2">Update Foto Utama Produk</label>
                    <p class="text-[9px] text-indigo-200 mb-4 ml-2 italic">Kosongkan jika tidak ingin mengganti. Foto baru akan menggantikan semua foto utama lama.</p>
                    <div class="p-6 bg-[#F1FBFD]/30 rounded-[2rem] border-2 border-dashed border-indigo-100 hover:border-[#CFB53B] transition-colors">
                        @if($product->image)
                        <div class="flex flex-wrap gap-3 mb-4">
                            @foreach($product->images as $img)
                                <img src="{{ asset('storage/' . $img->image_path) }}" class="w-20 h-20 object-cover rounded-2xl border-2 border-indigo-50 shadow-sm" alt="foto utama">
                            @endforeach
                        </div>
                        @endif
                        <input type="file" name="images[]" multiple accept="image/*"
                               class="block w-full text-[10px] text-indigo-300 file:mr-4 file:py-2 file:px-6 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-[#CFB53B] file:text-indigo-950 hover:file:bg-[#b89f33] cursor-pointer">
                    </div>
                </div>

                {{-- Description --}}
                <div>
                    <label class="block text-[10px] font-black text-indigo-300 uppercase tracking-[0.2em] mb-3 ml-2">Deskripsi Produk</label>
                    <textarea name="description" rows="4" class="w-full px-6 py-5 rounded-2xl bg-[#F1FBFD]/50 border-none focus:ring-2 focus:ring-[#CFB53B] outline-none text-indigo-900 font-medium italic shadow-inner">{{ old('description', $product->description) }}</textarea>
                </div>

                <div class="pt-6 flex flex-col md:flex-row gap-4">
                    <button type="submit" class="flex-1 bg-indigo-950 text-white py-5 rounded-[1.5rem] font-black text-xs uppercase tracking-[0.3em] hover:bg-[#CFB53B] hover:text-indigo-950 shadow-2xl transition-all">
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="px-10 py-5 bg-white text-indigo-300 rounded-[1.5rem] font-black text-xs uppercase tracking-[0.3em] text-center hover:bg-red-50 border border-indigo-50">
                        Batalkan
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        let variantIndex = {{ $product->variants->count() }};

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

        function addVariant() {
            const container = document.getElementById('variant-container');
            const idx = variantIndex;
            const previewId = `new-preview-${idx}`;
            const html = `
                <div class="variant-card p-6 bg-white rounded-2xl shadow-sm border border-indigo-50">
                    <div class="flex justify-between items-center mb-5">
                        <span class="text-[10px] font-black text-indigo-400 uppercase tracking-widest">Varian Baru</span>
                        <button type="button" onclick="this.closest('.variant-card').remove()" class="text-red-300 hover:text-red-500 transition-colors p-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-4">
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
                            <input type="number" name="variants[${idx}][stock]" placeholder="0" value="0" class="w-full px-3 py-3 rounded-xl bg-gray-50 border-none text-xs font-bold focus:ring-2 focus:ring-[#CFB53B] outline-none" required>
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
                                <img id="${previewId}" class="variant-img-preview" style="display:none;" alt="preview">
                                <input type="file" name="variants[${idx}][image]" accept="image/*"
                                       onchange="previewVariantImg(this, '${previewId}')"
                                       class="block w-full text-[10px] text-indigo-300 file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[9px] file:font-black file:bg-[#CFB53B]/10 file:text-[#CFB53B] hover:file:bg-[#CFB53B]/20 cursor-pointer">
                            </div>
                        </div>
                    </div>
                </div>`;
            container.insertAdjacentHTML('beforeend', html);
            variantIndex++;
        }
    </script>
@endsection
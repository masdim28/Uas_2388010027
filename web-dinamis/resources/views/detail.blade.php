<x-app-layout>
    <div class="pt-6 pb-16 bg-[#F1FBFD] min-h-screen">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Breadcrumb --}}
            <div class="mb-8">
                <a href="/products" class="group flex items-center text-[#CFB53B] hover:text-gray-900 transition-colors uppercase text-[10px] font-bold tracking-[0.2em]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Koleksi
                </a>
            </div>

            {{-- Main Product Card --}}
            <div class="bg-white rounded-3xl shadow-sm overflow-hidden border border-gray-100">
                <div class="grid grid-cols-1 md:grid-cols-2">

                    {{-- ===== KIRI: GALERI FOTO ===== --}}
                    <div class="bg-gray-50 p-6 md:p-8">

                        {{-- Foto Utama --}}
                        <div class="relative group mb-4">
                            @php
                                $firstImg = $product->images->first();
                                $mainSrc  = $firstImg ? asset('storage/' . $firstImg->image_path) : asset('storage/' . $product->image);
                            @endphp
                            <img id="main-display-image"
                                 src="{{ $mainSrc }}"
                                 alt="{{ $product->name }}"
                                 class="w-full object-cover aspect-[3/4] rounded-2xl shadow-sm transition-all duration-300">

                            <div class="absolute top-4 left-4 bg-[#CFB53B] text-white px-4 py-1 text-[10px] font-bold uppercase tracking-widest rounded-full shadow-sm">
                                Koleksi Eksklusif
                            </div>

                            {{-- Badge foto aktif --}}
                            <div id="active-variant-badge" class="absolute top-4 right-4 hidden">
                                <span id="active-variant-text" class="bg-indigo-950 text-white text-[9px] font-black px-3 py-1.5 rounded-full uppercase tracking-widest shadow-lg"></span>
                            </div>
                        </div>

                        {{-- Strip Thumbnail: Foto Utama + Foto Per Varian --}}
                        <div class="flex gap-3 overflow-x-auto pb-2 scrollbar-hide" id="thumbnail-strip">

                            {{-- Foto-foto utama produk --}}
                            @foreach($product->images as $img)
                                <button type="button"
                                    onclick="switchPhoto('{{ asset('storage/' . $img->image_path) }}', null, this)"
                                    class="thumb-btn flex-shrink-0 focus:outline-none group relative"
                                    title="Foto Utama">
                                    <img src="{{ asset('storage/' . $img->image_path) }}"
                                         class="w-[72px] h-[90px] object-cover rounded-xl border-2 border-transparent group-hover:border-[#CFB53B] transition-all shadow-sm">
                                    <span class="absolute -bottom-1.5 left-1/2 -translate-x-1/2 text-[7px] font-black text-[#CFB53B] uppercase tracking-wide bg-white px-1 rounded-full opacity-0 group-hover:opacity-100 transition-opacity">Utama</span>
                                </button>
                            @endforeach

                            {{-- Foto per varian (muncul setelah foto utama) --}}
                            @foreach($product->variants as $variant)
                                @if($variant->image)
                                    <button type="button"
                                        onclick="switchPhoto('{{ asset('storage/' . $variant->image) }}', {{ $variant->id }}, this)"
                                        class="thumb-btn flex-shrink-0 focus:outline-none group relative"
                                        title="{{ $variant->color }} - {{ $variant->size }}">
                                        <img src="{{ asset('storage/' . $variant->image) }}"
                                             class="w-[72px] h-[90px] object-cover rounded-xl border-2 border-transparent group-hover:border-indigo-400 transition-all shadow-sm">
                                        <span class="absolute -bottom-1.5 left-1/2 -translate-x-1/2 text-[7px] font-black text-indigo-400 uppercase tracking-wide bg-white px-1 rounded-full opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">{{ $variant->size }}</span>
                                    </button>
                                @endif
                            @endforeach

                        </div>
                        <p class="text-[9px] text-center text-indigo-200 mt-3 font-bold uppercase tracking-widest">Geser untuk lihat lebih banyak foto</p>
                    </div>

                    {{-- ===== KANAN: INFO PRODUK ===== --}}
                    <div class="p-8 md:p-12 flex flex-col justify-start">

                        {{-- Nama & Harga --}}
                        <div class="mb-6">
                            <h1 class="text-3xl md:text-4xl font-serif text-gray-800 mb-4 tracking-tight leading-tight capitalize">
                                {{ $product->name }}
                            </h1>

                            {{-- Harga dinamis —berubah saat pilih varian --}}
                            <div class="flex items-baseline gap-3">
                                <p id="display-price" class="text-2xl font-light text-[#CFB53B] transition-all duration-300">
                                    @php
                                        $minP = $product->variants->min('price');
                                        $maxP = $product->variants->max('price');
                                    @endphp
                                    @if($minP !== null && $minP != $maxP)
                                        Rp {{ number_format($minP, 0, ',', '.') }}
                                    @else
                                        Rp {{ number_format($minP ?? $product->price, 0, ',', '.') }}
                                    @endif
                                </p>
                                @if($minP !== null && $minP != $maxP)
                                    <span id="price-range-hint" class="text-xs text-gray-400 font-medium">– Rp {{ number_format($maxP, 0, ',', '.') }}</span>
                                @endif
                            </div>
                            <p id="price-hint" class="text-[10px] text-indigo-300 mt-1 font-bold uppercase tracking-widest">
                                {{ ($minP !== null && $minP != $maxP) ? '↑ Harga dapat berubah sesuai varian' : '' }}
                            </p>
                        </div>

                        {{-- Kategori --}}
                        <div class="flex flex-wrap gap-2 mb-6">
                            @foreach($product->categories as $category)
                                <span class="text-[9px] font-bold uppercase tracking-widest bg-[#F1FBFD] text-[#CFB53B] px-3 py-1 border border-[#CFB53B]/10 rounded-full">
                                    {{ $category->name }}
                                </span>
                            @endforeach
                        </div>

                        {{-- Deskripsi --}}
                        <div class="border-t border-gray-100 pt-6 mb-8">
                            <h3 class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400 mb-3">Deskripsi Produk</h3>
                            <div class="text-gray-600 leading-relaxed text-sm italic">
                                {!! nl2br(e($product->description ?: 'Keanggunan eksklusif dari koleksi Ade Afwa Boutique.')) !!}
                            </div>
                        </div>

                        {{-- Tombol Add to Cart --}}
                        <div class="space-y-4 mt-auto">
                            @php $hasAvailableVariant = $product->variants->where('status', 'ready')->where('stock', '>', 0)->count() > 0; @endphp
                            
                            @if(!$hasAvailableVariant)
                                <button type="button" disabled class="w-full bg-gray-400 text-white py-5 rounded-sm font-bold text-xs uppercase tracking-[0.3em] cursor-not-allowed shadow-xl flex justify-center items-center gap-3">
                                    Stok Habis
                                </button>
                            @else
                                <button type="button" onclick="openCartModal()" class="w-full bg-gray-900 text-white py-5 rounded-sm font-bold text-xs uppercase tracking-[0.3em] hover:bg-[#CFB53B] transition-all duration-500 shadow-xl flex justify-center items-center gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 11-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                    Tambah ke Keranjang
                                </button>
                            @endif
                            <div class="flex items-center justify-center gap-3 text-[9px] text-gray-400 uppercase tracking-widest pt-6">
                                Jaminan Kualitas Ade Afwa Boutique
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- MODAL PILIH VARIAN --}}
    <div id="cartModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        {{-- Overlay --}}
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeCartModal()"></div>

        <div class="flex min-h-full items-end justify-center p-0 text-center sm:items-center sm:p-4">
            <div class="relative transform overflow-hidden rounded-t-[2rem] sm:rounded-2xl bg-white text-left shadow-2xl transition-all w-full sm:max-w-lg animate-slide-up">
                
                {{-- Header Modal --}}
                <div class="px-6 py-6 border-b border-gray-50 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-serif text-gray-800">Pilih Varian</h3>
                        <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">Sesuaikan dengan keinginan Anda</p>
                    </div>
                    <button onclick="closeCartModal()" class="text-gray-400 hover:text-gray-600 p-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form action="/cart/add/{{ $product->id }}" method="POST">
                    @csrf
                    <div class="px-6 py-8">
                        <div class="grid grid-cols-1 gap-4 max-h-[50vh] overflow-y-auto pr-2 custom-scrollbar">
                            @forelse($product->variants as $variant)
                                @php $available = $variant->status === 'ready' && $variant->stock > 0; @endphp
                                <label class="relative flex items-center justify-between p-4 border-2 border-gray-50 rounded-2xl cursor-pointer hover:border-[#CFB53B]/30 transition-all group has-[:checked]:border-[#CFB53B] has-[:checked]:bg-[#F1FBFD] {{ !$available ? 'opacity-60' : '' }}"
                                       onclick="{{ $available ? 'onVariantSelect(' . $variant->id . ', ' . $variant->price . ', \'' . addslashes($variant->color . ' - ' . $variant->size) . '\')' : '' }}">
                                    <input type="radio" name="product_variant_id" value="{{ $variant->id }}" 
                                           class="sr-only variant-radio" 
                                           data-price="{{ $variant->price }}"
                                           data-label="{{ $variant->color }} - {{ $variant->size }}"
                                           data-variant-id="{{ $variant->id }}"
                                           {{ !$available ? 'disabled' : '' }} required>
                                    
                                    <div class="flex items-center gap-4">
                                        {{-- Foto mini varian (jika ada) --}}
                                        @if($variant->image)
                                            <img src="{{ asset('storage/' . $variant->image) }}"
                                                 class="w-12 h-12 rounded-xl object-cover border-2 border-gray-100 shadow-sm flex-shrink-0">
                                        @else
                                            <div class="w-12 h-12 bg-white rounded-xl shadow-sm border border-gray-100 flex items-center justify-center font-bold text-xs text-gray-700 flex-shrink-0">
                                                {{ $variant->size }}
                                            </div>
                                        @endif
                                        <div>
                                            <p class="text-sm font-bold text-gray-800">{{ $variant->color }}</p>
                                            <p class="text-[10px] text-[#CFB53B] font-bold uppercase">{{ $variant->size }} &nbsp;•&nbsp; Stok: {{ $variant->stock }} unit</p>
                                        </div>
                                    </div>

                                    <div class="flex flex-col items-end gap-1">
                                        <span class="text-sm font-black text-[#CFB53B]">Rp {{ number_format($variant->price, 0, ',', '.') }}</span>
                                        <div class="w-5 h-5 rounded-full border-2 border-gray-100 flex items-center justify-center group-has-[:checked]:border-[#CFB53B] transition-all flex-shrink-0">
                                            <div class="w-2.5 h-2.5 rounded-full bg-[#CFB53B] scale-0 group-has-[:checked]:scale-100 transition-transform"></div>
                                        </div>
                                    </div>

                                    @if(!$available)
                                        <div class="absolute inset-0 bg-white/70 backdrop-blur-[1px] flex items-center justify-center rounded-2xl cursor-not-allowed">
                                            <span class="text-[10px] font-black text-red-500 uppercase tracking-widest">Habis Terjual</span>
                                        </div>
                                    @endif
                                </label>
                            @empty
                                <div class="text-center py-8">
                                    <p class="text-sm text-gray-400 italic">Varian belum tersedia untuk produk ini.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-white border-t border-gray-100 flex justify-between items-center">
                        <span class="text-sm font-bold text-gray-800">Jumlah:</span>
                        <div class="flex items-center border border-gray-200 rounded-full h-10 w-32">
                            <button type="button" onclick="changeQty(-1)" class="w-10 h-full flex items-center justify-center text-gray-500 hover:text-[#CFB53B] focus:outline-none rounded-l-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" /></svg>
                            </button>
                            <input type="number" name="qty" id="qty-input" value="1" min="1" class="flex-1 w-full text-center border-none focus:ring-0 text-sm font-bold p-0 appearance-none" onchange="validateQty()">
                            <button type="button" onclick="changeQty(1)" class="w-10 h-full flex items-center justify-center text-gray-500 hover:text-[#CFB53B] focus:outline-none rounded-r-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                            </button>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 flex flex-col gap-3">
                        <button type="submit" id="add-to-cart-btn" class="w-full bg-gray-900 text-white py-4 rounded-xl font-bold text-xs uppercase tracking-[0.2em] hover:bg-[#CFB53B] transition-all shadow-lg">
                            Tambahkan Ke Keranjang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        .thumb-btn.active img { border-color: #CFB53B !important; box-shadow: 0 0 0 2px rgba(207,181,59,0.3); }

        @keyframes slide-up {
            from { transform: translateY(100%); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .animate-slide-up { animation: slide-up 0.4s cubic-bezier(0.16, 1, 0.3, 1); }
    </style>

    @php
        $variantJs = $product->variants->map(function($v) {
            return [
                'id'    => $v->id,
                'price' => $v->price,
                'stock' => $v->stock,
                'label' => $v->color . ' - ' . $v->size,
                'image' => $v->image ? asset('storage/' . $v->image) : null,
            ];
        });
    @endphp

    <script>
        // Data varian dari server
        const variantData = @json($variantJs);

        let selectedVariantId = null;
        let maxStock = {{ $product->variants->count() > 0 ? 0 : $product->stock }};

        // Set thumbnail aktif pertama kali
        document.addEventListener('DOMContentLoaded', function () {
            const firstThumb = document.querySelector('.thumb-btn');
            if (firstThumb) firstThumb.classList.add('active');
        });

        function openCartModal() {
            const modal = document.getElementById('cartModal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeCartModal() {
            const modal = document.getElementById('cartModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        /**
         * Ganti foto utama + update badge varian
         */
        function switchPhoto(src, variantId, btn) {
            // Update gambar utama
            const mainImg = document.getElementById('main-display-image');
            mainImg.style.opacity = '0';
            mainImg.style.transform = 'scale(0.97)';
            setTimeout(() => {
                mainImg.src = src;
                mainImg.style.opacity = '1';
                mainImg.style.transform = 'scale(1)';
            }, 200);

            // Highlight thumbnail aktif
            document.querySelectorAll('.thumb-btn').forEach(b => b.classList.remove('active'));
            if (btn) btn.classList.add('active');

            // Jika foto varian diklik → otomatis pilih varian tersebut
            if (variantId !== null) {
                const radio = document.querySelector(`.variant-radio[data-variant-id="${variantId}"]`);
                if (radio && !radio.disabled) {
                    radio.checked = true;
                    onVariantSelect(variantId, null, null); // harga sudah ada di data-price
                    const price = radio.getAttribute('data-price');
                    updatePrice(price, radio.getAttribute('data-label'));
                }
                // Tampilkan badge
                const v = variantData.find(x => x.id == variantId);
                if (v) showVariantBadge(v.label);
            } else {
                hideVariantBadge();
            }
        }

        /**
         * Event saat pilih varian dari daftar radio di dalam modal
         */
        function onVariantSelect(variantId, price, label) {
            selectedVariantId = variantId;

            const v = variantData.find(x => x.id == variantId);
            if (v) {
                maxStock = v.stock;
                validateQty();
            }

            // Update harga di tampilan utama
            const radio = document.querySelector(`.variant-radio[data-variant-id="${variantId}"]`);
            if (radio) {
                const p = radio.getAttribute('data-price');
                const l = radio.getAttribute('data-label');
                updatePrice(p, l);

                // Jika varian punya foto, tampilkan foto tersebut di galeri utama
                const v = variantData.find(x => x.id == variantId);
                if (v && v.image) {
                    const thumbBtn = [...document.querySelectorAll('.thumb-btn')].find(b => {
                        const img = b.querySelector('img');
                        return img && img.src === v.image;
                    });
                    switchPhoto(v.image, variantId, thumbBtn || null);
                }
            }
        }

        function updatePrice(price, label) {
            const displayEl = document.getElementById('display-price');
            displayEl.style.opacity = '0';
            displayEl.style.transform = 'translateY(-4px)';
            setTimeout(() => {
                displayEl.textContent = 'Rp ' + parseInt(price).toLocaleString('id-ID');
                displayEl.style.opacity = '1';
                displayEl.style.transform = 'translateY(0)';
            }, 150);

            const hint = document.getElementById('price-hint');
            if (hint) hint.textContent = label ? '✓ Varian terpilih: ' + label : '';

            const rangeHint = document.getElementById('price-range-hint');
            if (rangeHint) rangeHint.style.display = 'none';
        }

        function showVariantBadge(label) {
            const badge = document.getElementById('active-variant-badge');
            const text  = document.getElementById('active-variant-text');
            if (badge && text) {
                text.textContent = label;
                badge.classList.remove('hidden');
            }
        }

        function hideVariantBadge() {
            const badge = document.getElementById('active-variant-badge');
            if (badge) badge.classList.add('hidden');
        }

        // Dukung klik radio dari HTML onclick attr
        document.querySelectorAll('.variant-radio').forEach(radio => {
            radio.addEventListener('change', function () {
                if (this.checked && !this.disabled) {
                    onVariantSelect(
                        parseInt(this.dataset.variantId),
                        parseInt(this.dataset.price),
                        this.dataset.label
                    );
                }
            });
        });

        function changeQty(change) {
            const input = document.getElementById('qty-input');
            let val = parseInt(input.value) + change;
            if (isNaN(val)) val = 1;
            if (val < 1) val = 1;
            input.value = val;
            validateQty();
        }

        function validateQty() {
            const input = document.getElementById('qty-input');
            let val = parseInt(input.value);
            
            if (isNaN(val) || val < 1) {
                input.value = 1;
                val = 1;
            }

            if (selectedVariantId !== null || maxStock > 0) {
                if (val > maxStock) {
                    alert('Stok terbatas! Sisa stok yang tersedia: ' + maxStock);
                    input.value = maxStock;
                }
            }
        }
    </script>
</x-app-layout>
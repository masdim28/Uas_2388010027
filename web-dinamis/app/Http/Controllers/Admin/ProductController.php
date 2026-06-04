<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['categories', 'variants'])->latest()->get();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::whereNull('parent_id')->with('children')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'category_ids'      => 'required|array',
            'category_ids.*'    => 'exists:categories,id',
            'images'            => 'required|array|min:1|max:5',
            'images.*'          => 'image|mimes:jpeg,png,jpg|max:2048',
            'variants'          => 'required|array|min:1',
            'variants.*.color'  => 'nullable|string|max:100',
            'variants.*.size'   => 'nullable|string|max:50',
            'variants.*.price'  => 'required|numeric|min:0',
            'variants.*.weight' => 'required|numeric|min:1',
            'variants.*.stock'  => 'required|numeric|min:0',
            'variants.*.status' => 'required|in:ready,sold_out',
            'variants.*.image'  => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 1. Buat Produk Utama (nilai sementara, akan di-sync dari varian)
        $product = Product::create([
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => 0,
            'stock'       => 0,
            'weight'      => 500,
            'status'      => 'ready',
        ]);

        // 2. Simpan Relasi Kategori
        $product->categories()->attach($request->category_ids);

        // 3. Simpan Varian Produk beserta semua detailnya
        foreach ($request->variants as $idx => $variantData) {
            $variantImagePath = null;
            $variantFile = $request->file("variants.{$idx}.image");
            if ($variantFile && $variantFile->isValid()) {
                $variantImagePath = $variantFile->store('products/variants', 'public');
            }
            $product->variants()->create([
                'color'  => $variantData['color'] ?? '-',
                'size'   => $variantData['size'] ?? '-',
                'price'  => $variantData['price'],
                'weight' => $variantData['weight'],
                'stock'  => $variantData['stock'],
                'status' => $variantData['status'],
                'image'  => $variantImagePath,
            ]);
        }

        // 4. Sinkronisasi harga/stok/berat/status produk dari varian
        $product->refresh();
        $this->syncProductFromVariants($product);

        // 5. Simpan Foto Utama Produk
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('products', 'public');
                if ($index === 0) {
                    $product->update(['image' => $path]);
                }
                $product->images()->create(['image_path' => $path]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Produk dan varian berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $product = Product::with(['categories', 'images', 'variants'])->findOrFail($id);
        $categories = Category::whereNull('parent_id')->with('children')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'category_ids'      => 'required|array',
            'variants'          => 'required|array|min:1',
            'variants.*.price'  => 'required|numeric|min:0',
            'variants.*.weight' => 'required|numeric|min:1',
            'variants.*.stock'  => 'required|numeric|min:0',
            'variants.*.status' => 'required|in:ready,sold_out',
            'variants.*.image'  => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'images.*'          => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $product = Product::findOrFail($id);

        // 1. Update data dasar produk
        $product->update([
            'name'        => $request->name,
            'description' => $request->description,
        ]);

        // 2. Sinkronisasi Kategori
        $product->categories()->sync($request->category_ids);

        // 3. Update Varian Produk
        $incomingVariantIds = collect($request->variants)->pluck('id')->filter()->values()->toArray();

        // Hapus varian yang dihapus user dari UI (beserta fotonya)
        $product->variants()->whereNotIn('id', $incomingVariantIds)->each(function ($variant) {
            if ($variant->image) {
                Storage::disk('public')->delete($variant->image);
            }
            $variant->delete();
        });

        foreach ($request->variants as $idx => $variantData) {
            $variantFile = $request->file("variants.{$idx}.image");

            if (isset($variantData['id']) && !empty($variantData['id'])) {
                // Update varian yang sudah ada
                $existingVariant = $product->variants()->find($variantData['id']);
                if ($existingVariant) {
                    $variantImagePath = $existingVariant->image; // Pertahankan foto lama
                    if ($variantFile && $variantFile->isValid()) {
                        if ($existingVariant->image) {
                            Storage::disk('public')->delete($existingVariant->image);
                        }
                        $variantImagePath = $variantFile->store('products/variants', 'public');
                    }
                    $existingVariant->update([
                        'color'  => $variantData['color'] ?? '-',
                        'size'   => $variantData['size'] ?? '-',
                        'price'  => $variantData['price'],
                        'weight' => $variantData['weight'],
                        'stock'  => $variantData['stock'],
                        'status' => $variantData['status'],
                        'image'  => $variantImagePath,
                    ]);
                }
            } else {
                // Buat varian baru
                $variantImagePath = null;
                if ($variantFile && $variantFile->isValid()) {
                    $variantImagePath = $variantFile->store('products/variants', 'public');
                }
                $product->variants()->create([
                    'color'  => $variantData['color'] ?? '-',
                    'size'   => $variantData['size'] ?? '-',
                    'price'  => $variantData['price'],
                    'weight' => $variantData['weight'],
                    'stock'  => $variantData['stock'],
                    'status' => $variantData['status'],
                    'image'  => $variantImagePath,
                ]);
            }
        }

        // 4. Sinkronisasi harga/stok/berat/status produk dari varian
        $product->refresh();
        $this->syncProductFromVariants($product);

        // 5. Update foto utama produk (hanya jika ada file baru)
        if ($request->hasFile('images')) {
            foreach ($product->images as $oldImage) {
                Storage::disk('public')->delete($oldImage->image_path);
            }
            $product->images()->delete();
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('products', 'public');
                if ($index === 0) {
                    $product->update(['image' => $path]);
                }
                $product->images()->create(['image_path' => $path]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Koleksi Ade Afwa berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $product = Product::with(['images', 'variants'])->findOrFail($id);

        foreach ($product->images as $img) {
            Storage::disk('public')->delete($img->image_path);
        }
        foreach ($product->variants as $variant) {
            if ($variant->image) {
                Storage::disk('public')->delete($variant->image);
            }
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }

    public function show($id)
    {
        $product = Product::with(['categories', 'images', 'variants'])->findOrFail($id);
        return view('admin.products.detail', compact('product'));
    }

    /**
     * Sinkronisasi field agregat produk (price, stock, weight, status) dari data varian.
     */
    private function syncProductFromVariants(Product $product): void
    {
        $variants = $product->variants;
        if ($variants->isEmpty()) return;

        $product->update([
            'price'  => $variants->min('price'),
            'stock'  => $variants->sum('stock'),
            'weight' => (int) $variants->min('weight'),
            'status' => $variants->contains('status', 'ready') ? 'ready' : 'sold_out',
        ]);
    }
}
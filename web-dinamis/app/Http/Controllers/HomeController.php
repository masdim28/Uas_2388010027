<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category; 
use Illuminate\Http\Request;

class HomeController extends Controller
{
    // Halaman Home Utama
    public function index()
    {
        // Mengambil kategori utama (Parent)
        $categoriesWithProducts = Category::whereNull('parent_id')
            ->get()
            ->map(function ($category) {
                // Ambil ID kategori ini dan semua ID sub-kategorinya
                $categoryIds = $category->children->pluck('id')->toArray();
                $categoryIds[] = $category->id;

                // PERBAIKAN: Gunakan whereHas untuk mencari produk di tabel pivot category_product
                $category->setRelation('products', 
                    Product::whereHas('categories', function($query) use ($categoryIds) {
                        $query->whereIn('categories.id', $categoryIds);
                    })
                    ->latest()
                    ->take(4)
                    ->get()
                );

                return $category;
            })
            // Filter agar kategori yang benar-benar tidak punya produk tidak muncul
            ->filter(function ($category) {
                return $category->products->count() > 0;
            });

        return view('home', compact('categoriesWithProducts'));
    }

    // Fungsi saat kategori di klik
    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        // Ambil ID kategori dan semua anaknya (sub-kategori)
        $categoryIds = $category->children->pluck('id')->toArray();
        $categoryIds[] = $category->id;

        // PERBAIKAN: Gunakan whereHas agar kategori Best Seller dkk yang ada di pivot terbaca
        $products = Product::whereHas('categories', function($query) use ($categoryIds) {
                $query->whereIn('categories.id', $categoryIds);
            })
            ->latest()
            ->get();

        // Kirim data ke view
        return view('home', [
            'products' => $products,
            'currentCategory' => $category
        ]);
    }

   // Halaman Produk (Bisa untuk Menampilkan Semua atau Hasil Pencarian)
    public function products(Request $request)
    {
        // Tangkap keyword dari input 'search'
        $query = $request->input('search');

        // Lakukan filter jika ada kata kunci, jika kosong ambil semua produk
        $products = Product::with(['categories', 'variants'])
            ->when($query, function($q) use ($query) {
            $q->where('name', 'like', '%' . $query . '%');
        })
        ->latest()
        ->get();

        // Kirimkan ke view, beserta kata kuncinya
        return view('products', compact('products', 'query'));
    }

    public function detail($id)
    {
        // Tambahkan with('categories') agar muncul kategorinya
        $product = Product::with(['categories', 'images', 'variants'])->findOrFail($id);
        
        // --- LOGIKA BARU UNTUK DASHBOARD ---
        // Setiap kali halaman detail dibuka, jumlah klik bertambah 1
        $product->increment('clicks'); 
        
        return view('detail', compact('product'));
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Tampilkan semua kategori utama beserta sub-kategorinya.
     */
    public function index()
    {
        $categories = Category::whereNull('parent_id')
            ->with(['children' => function ($q) {
                $q->withCount('products')->orderBy('name');
            }])
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Simpan sub-kategori baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:100',
            'parent_id' => 'required|exists:categories,id',
        ]);

        // Pastikan parent_id adalah kategori utama (bukan sub-kategori)
        $parent = Category::findOrFail($request->parent_id);
        if ($parent->parent_id !== null) {
            return back()->with('error', 'Sub-kategori hanya bisa ditambahkan di bawah kategori utama.');
        }

        // Cek duplikat nama di bawah parent yang sama
        $exists = Category::where('parent_id', $request->parent_id)
            ->whereRaw('LOWER(name) = ?', [strtolower($request->name)])
            ->exists();

        if ($exists) {
            return back()->with('error', 'Sub-kategori "' . $request->name . '" sudah ada di bawah ' . $parent->name . '.');
        }

        Category::create([
            'name'      => $request->name,
            'slug'      => Str::slug($request->name),
            'parent_id' => $request->parent_id,
        ]);

        return back()->with('success', 'Sub-kategori "' . $request->name . '" berhasil ditambahkan ke ' . $parent->name . '!');
    }

    /**
     * Update nama sub-kategori.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:100',
        ]);

        $category = Category::findOrFail($id);

        // Hanya sub-kategori yang bisa di-edit
        if ($category->parent_id === null) {
            return back()->with('error', 'Kategori utama tidak bisa diubah.');
        }

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return back()->with('success', 'Sub-kategori berhasil diperbarui menjadi "' . $request->name . '"!');
    }

    /**
     * Hapus sub-kategori (hanya jika tidak ada produk terkait).
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        // Hanya sub-kategori yang bisa dihapus
        if ($category->parent_id === null) {
            return back()->with('error', 'Kategori utama tidak bisa dihapus.');
        }

        // Cek apakah ada produk terkait
        $productCount = $category->products()->count();
        if ($productCount > 0) {
            return back()->with('error', 'Tidak bisa menghapus "' . $category->name . '" karena masih memiliki ' . $productCount . ' produk terkait.');
        }

        $name = $category->name;
        $category->delete();

        return back()->with('success', 'Sub-kategori "' . $name . '" berhasil dihapus!');
    }
}

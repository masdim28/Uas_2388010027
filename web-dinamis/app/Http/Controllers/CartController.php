<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        // Mengambil data keranjang beserta produk dan variannya (warna & ukuran)
        $cart = Cart::with(['items.product', 'items.variant'])->where('user_id', Auth::id())->first();
        return view('cart', compact('cart'));
    }

    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $variantId = $request->input('product_variant_id');
        $qty = $request->input('qty', 1); // Default to 1 if not provided

        if ($product->status === 'sold_out') {
            return redirect()->back()->with('error', 'Maaf, produk ini sudah habis.');
        }

        $cart = Cart::firstOrCreate([
            'user_id' => Auth::id() 
        ]);

        // Cek apakah produk dengan varian yang sama sudah ada di keranjang
        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $id)
            ->where('product_variant_id', $variantId)
            ->first();

        // Cek Stock Variasi
        $variant = null;
        if ($variantId) {
            $variant = \App\Models\ProductVariant::find($variantId);
            if ($variant && $variant->stock < $qty) {
                return redirect()->back()->with('error', 'Stok terbatas! Sisa stok: ' . $variant->stock);
            }
        } else {
            if ($product->stock < $qty) {
                return redirect()->back()->with('error', 'Stok terbatas! Sisa stok: ' . $product->stock);
            }
        }

        if ($item) {
            $newQty = $item->qty + $qty;
            if ($variant && $variant->stock < $newQty) {
                 return redirect()->back()->with('error', 'Stok terbatas! Keranjang Anda melebihi sisa stok: ' . $variant->stock);
            } elseif (!$variant && $product->stock < $newQty) {
                 return redirect()->back()->with('error', 'Stok terbatas! Keranjang Anda melebihi sisa stok: ' . $product->stock);
            }
            $item->qty = $newQty;
            $item->save();
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $id,
                'product_variant_id' => $variantId,
                'qty' => $qty
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambah!');
    }

    public function update(Request $request, $id)
    {
        $item = CartItem::findOrFail($id);
        $change = (int) $request->input('change', 0);
        
        if ($change === 0) return back();

        $newQty = $item->qty + $change;

        if ($newQty < 1) {
            $item->delete();
            return back()->with('success', 'Produk dihapus dari keranjang.');
        }

        // Cek stok
        $stock = $item->variant ? $item->variant->stock : $item->product->stock;
        
        if ($newQty > $stock) {
            return back()->with('error', 'Stok terbatas! Sisa stok: ' . $stock);
        }

        $item->qty = $newQty;
        $item->save();

        return back()->with('success', 'Kuantitas berhasil diperbarui.');
    }

    /**
     * FUNGSI UNTUK MENGHAPUS ITEM (INI YANG TADI KURANG)
     */
    public function remove($id)
    {
        // Mencari item berdasarkan ID yang dikirim dari tombol hapus
        $item = CartItem::findOrFail($id);

        // Menghapus data dari database
        $item->delete();

        // Kembali ke halaman keranjang
        return redirect()->route('cart.index')->with('success', 'Produk berhasil dihapus dari keranjang.');
    }
}
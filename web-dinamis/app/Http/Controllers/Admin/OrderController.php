<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // 1. Menu Transaksi
public function transaksi() {
    // Ubah 'unpaid' menjadi 'pending' sesuai database
    $orders = Order::with(['items.product', 'items.variant', 'user'])->where('status_payment', 'pending')->latest()->get();
    return view('admin.orders.transaksi', compact('orders'));
}

    public function konfirmasiPembayaran($id) {
        $order = Order::findOrFail($id);
        
        $order->update([
            'status_payment' => 'paid', 
            'status_shipping' => 'processing'
        ]);
        return back()->with('success', 'Pembayaran dikonfirmasi!');
    }

   // 2. Menu Pesanan
public function pesanan() {
    // Sesuaikan logika jika Anda ingin menampilkan pesanan yang sudah dibayar
    // Atau jika ingin menampilkan yang masih pending, ubah status_payment-nya
    $orders = Order::with(['items.product', 'items.variant', 'user'])
                  ->where('status_payment', 'paid')
                  ->where('status_shipping', '!=', 'completed')
                  ->latest()->get();
    return view('admin.orders.pesanan', compact('orders'));
}

    public function updateResi(Request $request, $id) {
        $request->validate([
            'resi' => 'required|string'
        ]);

        $order = Order::with(['items.product', 'items.variant', 'user'])->findOrFail($id);
        $order->update([
            'resi' => $request->resi, 
            'shipped_at' => now(),
            'status_shipping' => 'shipped'
        ]);

        // WhatsApp redirect logic
        $phone = $order->phone ?? ($order->user->phone ?? '');
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        // Get customer details
        $customerName = $order->recipient_name ?? ($order->user->name ?? 'Pelanggan');
        $courier = strtoupper($order->courier ?? 'Jasa Kirim');
        $resi = $request->resi;

        // Compile ordered items details
        $itemDetails = [];
        foreach ($order->items as $item) {
            $name = $item->product ? $item->product->name : 'Produk';
            if ($item->variant) {
                $variantName = $item->variant->name ?? '';
                if (empty($variantName)) {
                    $parts = array_filter([$item->variant->color, $item->variant->size]);
                    $variantName = implode(', ', $parts);
                }
                if (!empty($variantName)) {
                    $name .= ' (' . $variantName . ')';
                }
            }
            $itemDetails[] = "- " . $name . " (x" . $item->qty . ")";
        }
        $itemsText = implode("\n", $itemDetails);

        // Build the shipping confirmation message
        $message = "Pesanan atas nama {$customerName} dengan no resi {$resi} sudah di serahkan ke jasa kirim {$courier}, mohon untuk memantaunya lewat web jasa kirim tersebut.\n\n" .
                   "Berikut detail pesanan:\n" .
                   "{$itemsText}";

        $waLink = "https://api.whatsapp.com/send?phone=" . $phone . "&text=" . rawurlencode($message);

        // Redirect directly to WhatsApp with prefilled message
        return redirect($waLink);
    }

    public function printOrder($id) {
        $order = Order::with('items.product')->findOrFail($id);
        return view('admin.orders.print', compact('order'));
    }

    // 3. Menu Pesanan Berhasil
    public function selesai(Request $request) {
        $query = Order::with(['items.product', 'items.variant', 'user'])->whereIn('status_shipping', ['completed', 'cancelled']);

        if ($request->has('filter_type')) {
            if ($request->filter_type == 'day' && $request->date) {
                $query->whereDate('created_at', $request->date);
            } elseif ($request->filter_type == 'month' && $request->month) {
                $monthYear = explode('-', $request->month); // Format: YYYY-MM
                if (count($monthYear) == 2) {
                    $query->whereYear('created_at', $monthYear[0])
                          ->whereMonth('created_at', $monthYear[1]);
                }
            }
        }

        if ($request->has('status') && in_array($request->status, ['completed', 'cancelled'])) {
            $query->where('status_shipping', $request->status);
        }

        $orders = $query->latest()->get();
        return view('admin.orders.selesai', compact('orders'));
    }

    public function batalkanPesanan($id) {
        // Eager load items, products, variants, and user details
        $order = Order::with(['items.product', 'items.variant', 'user'])->findOrFail($id);

        \Illuminate\Support\Facades\DB::transaction(function () use ($order) {
            // Update order status
            $order->update([
                'status_payment' => 'cancelled',
                'status_shipping' => 'cancelled'
            ]);

            // Restore product and variant stock
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->qty);
                    // If product was sold_out and now has stock, set back to ready
                    if ($item->product->status === 'sold_out' && $item->product->stock > 0) {
                        $item->product->update(['status' => 'ready']);
                    }
                }

                if ($item->product_variant_id) {
                    $variant = \App\Models\ProductVariant::find($item->product_variant_id);
                    if ($variant) {
                        $variant->increment('stock', $item->qty);
                    }
                }
            }
        });

        // WhatsApp message redirect
        $phone = $order->phone ?? ($order->user->phone ?? '');
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        // Get customer name
        $customerName = $order->recipient_name ?? ($order->user->name ?? 'Pelanggan');

        // Compile ordered items details
        $itemDetails = [];
        foreach ($order->items as $item) {
            $name = $item->product ? $item->product->name : 'Produk';
            if ($item->variant) {
                // Determine variant options representation
                $variantName = $item->variant->name ?? '';
                if (empty($variantName)) {
                    $parts = array_filter([$item->variant->color, $item->variant->size]);
                    $variantName = implode(', ', $parts);
                }
                if (!empty($variantName)) {
                    $name .= ' (' . $variantName . ')';
                }
            }
            $itemDetails[] = "- " . $name . " (x" . $item->qty . ")";
        }
        $itemsText = implode("\n", $itemDetails);

        // Build the personalized WhatsApp message
        $message = "mohon maaf pesanan atas nama {$customerName} dengan detail pesanan:\n" .
                   "{$itemsText}\n" .
                   "telah di batalkan , apabila telah melakukan pembayaran harap menghubungi admin atau membalas pesan ini untuk pengembalian dana";
        
        $waLink = "https://api.whatsapp.com/send?phone=" . $phone . "&text=" . rawurlencode($message);

        // Redirect directly to WhatsApp with prefilled message
        return redirect($waLink);
    }
}
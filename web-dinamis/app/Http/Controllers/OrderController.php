<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class OrderController extends Controller
{
    /**
     * Display a listing of the user's orders.
     */
    public function index()
    {
        $userId = Auth::id();

        // AUTO-COMPLETE CHECK: Jika sudah 10 hari sejak shipped_at dan belum di-klik "Pesanan Diterima", otomatis selesai.
        Order::where('user_id', $userId)
            ->where('status_shipping', 'shipped')
            ->whereNotNull('shipped_at')
            ->where('shipped_at', '<=', now()->subDays(10))
            ->update(['status_shipping' => 'completed']);

        // 1. Belum Dibayar (Pending Payment)
        $unpaidOrders = Order::with('items.product')
            ->where('user_id', $userId)
            ->where('status_payment', 'pending')
            ->where('status_shipping', '!=', 'cancelled')
            ->orderBy('created_at', 'desc')
            ->get();

        // 2. Sedang Dikemas (Paid, Processing)
        $processingOrders = Order::with('items.product')
            ->where('user_id', $userId)
            ->where('status_payment', 'paid')
            ->where('status_shipping', 'processing')
            ->orderBy('created_at', 'desc')
            ->get();

        // 3. Dalam Pengiriman (Paid, Shipped)
        $shippedOrders = Order::with('items.product')
            ->where('user_id', $userId)
            ->where('status_payment', 'paid')
            ->where('status_shipping', 'shipped')
            ->orderBy('created_at', 'desc')
            ->get();

        // 4. Pesanan Selesai (Completed)
        $completedOrders = Order::with('items.product')
            ->where('user_id', $userId)
            ->whereIn('status_shipping', ['completed', 'cancelled'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('orders', compact('unpaidOrders', 'processingOrders', 'shippedOrders', 'completedOrders'));
    }

    /**
     * Show the payment page for a specific order.
     */
    public function payment($id)
    {
        $order = Order::with(['items.product', 'paymentMethod'])->where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        // If order is already paid or cancelled, redirect back to orders
        if ($order->status_payment != 'pending') {
            return redirect()->route('orders.index')->with('error', 'Pesanan ini sudah dibayar atau tidak valid untuk pembayaran.');
        }

        return view('payment', compact('order'));
    }

    /**
     * Mark order as received by user.
     */
    public function receiveOrder(Request $request, $id)
    {
        $order = Order::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if ($order->status_shipping != 'shipped') {
            return redirect()->route('orders.index')->with('error', 'Pesanan belum dalam status pengiriman.');
        }

        $order->update(['status_shipping' => 'completed']);

        return redirect()->route('orders.index')->with('success', 'Terima kasih telah mengonfirmasi penerimaan pesanan Anda.');
    }
}

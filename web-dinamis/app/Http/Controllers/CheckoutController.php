<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\PaymentMethod;

class CheckoutController extends Controller
{
    /* -------------------------------------------------------------------------- */
    /* HALAMAN CHECKOUT                                                            */
    /* -------------------------------------------------------------------------- */

    public function index(Request $request)
    {
        $selectedIds = $request->input('selected_items');

        // Jika refresh halaman checkout
        if (!$selectedIds) {
            $selectedIds = CartItem::whereHas('cart', function ($query) {
                $query->where('user_id', Auth::id());
            })->pluck('id')->toArray();
        }

        if (empty($selectedIds)) {
            return redirect()->route('cart.index')
                ->with('error', 'Silahkan pilih barang terlebih dahulu!');
        }

        $items = CartItem::whereIn('id', $selectedIds)
            ->whereHas('cart', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->with(['product', 'variant'])
            ->get();

        // Total harga produk
        $total = $items->sum(function ($item) {
            $unitPrice = ($item->variant && $item->variant->price > 0) ? $item->variant->price : $item->product->price;
            return $unitPrice * $item->qty;
        });

        // Total berat (mengutamakan berat varian jika ada)
        $totalWeight = $items->sum(function ($item) {
            $unitWeight = ($item->variant && $item->variant->weight > 0) ? $item->variant->weight : ($item->product->weight ?? 1000);
            return $unitWeight * $item->qty;
        });

        // Payment Methods
        $paymentMethods = PaymentMethod::where('is_active', true)->get();

        return view('checkout', compact(
            'items',
            'total',
            'selectedIds',
            'totalWeight',
            'paymentMethods'
        ));
    }

    /* -------------------------------------------------------------------------- */
    /* SEARCH DESTINATION (DIRECT SEARCH METHOD)                                  */
    /* -------------------------------------------------------------------------- */

    public function searchDestination(Request $request)
    {
        try {

            $baseUrl = config('services.komerce.base_url');
            $apiKey  = config('services.komerce.api_key');

            \Log::info('[Komerce] Search Destination', [
                'search'   => $request->search,
                'base_url' => $baseUrl,
            ]);

            $response = Http::withoutVerifying()->withHeaders([
                'key' => $apiKey
            ])->get(
                $baseUrl . '/destination/domestic-destination',
                [
                    'search' => $request->search,
                    'limit'  => 10,
                    'offset' => 0
                ]
            );

            \Log::info('[Komerce] Search Destination Response', [
                'status' => $response->status(),
                'body'   => $response->json(),
            ]);

            // Return langsung JSON dari Komerce: { meta: {...}, data: [...] }
            return response()->json($response->json());

        } catch (\Exception $e) {

            \Log::error('[Komerce] Search Destination Error', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return response()->json([
                'meta' => ['status' => 'error', 'message' => $e->getMessage()],
                'data' => []
            ], 500);
        }
    }

    /* -------------------------------------------------------------------------- */
    /* CEK ONGKIR                                                                 */
    /* -------------------------------------------------------------------------- */

    public function checkOngkir(Request $request)
    {
        try {

            $baseUrl     = config('services.komerce.base_url');
            $apiKey      = config('services.komerce.api_key');
            $origin      = config('services.komerce.origin');

            $payload = [
                'origin'      => intval($origin),
                'destination' => intval($request->destination),
                'weight'      => intval($request->weight),
                'courier'     => strtolower($request->courier),
            ];

            \Log::info('[Komerce] Check Ongkir Request', $payload);

            $response = Http::withoutVerifying()->asForm()->withHeaders([
                'key' => $apiKey
            ])->post(
                $baseUrl . '/calculate/domestic-cost',
                $payload
            );

            \Log::info('[Komerce] Check Ongkir Response', [
                'status' => $response->status(),
                'body'   => $response->json(),
            ]);

            return response()->json(
                $response->json()
            );

        } catch (\Exception $e) {

            \Log::error('[Komerce] Check Ongkir Error', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return response()->json([
                'meta' => [
                    'status' => 'error',
                    'message' => $e->getMessage()
                ]
            ], 500);
        }
    }

    /* -------------------------------------------------------------------------- */
    /* PROSES CHECKOUT                                                            */
    /* -------------------------------------------------------------------------- */

    public function process(Request $request)
    {
        $selectedIds = $request->input('selected_items');

        $items = CartItem::whereIn('id', $selectedIds)
            ->with(['product', 'variant'])
            ->get();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Item tidak ditemukan.');
        }

        DB::beginTransaction();

        try {

            /* ------------------------------------------------------------------ */
            /* VALIDASI STOK                                                      */
            /* ------------------------------------------------------------------ */

            foreach ($items as $item) {

                // Jika pakai variant
                if ($item->product_variant_id) {

                    $variant = $item->variant;

                    if (!$variant || $variant->stock < $item->qty) {
                        throw new \Exception(
                            "Stok varian {$item->product->name} habis."
                        );
                    }

                } else {

                    if ($item->product->stock < $item->qty) {
                        throw new \Exception(
                            "Stok produk {$item->product->name} habis."
                        );
                    }
                }
            }

            /* ------------------------------------------------------------------ */
            /* TOTAL                                                              */
            /* ------------------------------------------------------------------ */

            $totalProductPrice = $items->sum(function ($item) {
                $unitPrice = ($item->variant && $item->variant->price > 0) ? $item->variant->price : $item->product->price;
                return $unitPrice * $item->qty;
            });

            $shippingCost = $request->shipping_cost ?? 0;

            /* ------------------------------------------------------------------ */
            /* SIMPAN ORDER                                                       */
            /* ------------------------------------------------------------------ */

            // Fetch payment method to save its name in `payment_method` column for backward compatibility
            // and save its ID in `payment_method_id` column
            $paymentMethod = PaymentMethod::find($request->payment_method_id);
            $paymentMethodName = $paymentMethod ? $paymentMethod->name : 'Unknown';

            $order = Order::create([
                'user_id' => Auth::id(),
                'total_price' => $totalProductPrice + $shippingCost,
                'payment_method' => $paymentMethodName,
                'payment_method_id' => $request->payment_method_id,
                'status_payment' => 'pending',

                // Data pengiriman
                'recipient_name' => $request->recipient_name,
                'phone' => $request->phone,
                'address_details' => $request->address_details,

                // Ongkir
                'courier' => $request->courier_name,
                'shipping_cost' => $shippingCost,
            ]);

            /* ------------------------------------------------------------------ */
            /* SIMPAN ORDER ITEM + KURANGI STOK                                  */
            /* ------------------------------------------------------------------ */

            foreach ($items as $item) {

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_variant_id' => $item->product_variant_id,
                    'qty' => $item->qty,
                    'price' => ($item->variant && $item->variant->price > 0) ? $item->variant->price : $item->product->price
                ]);

                // Kurangi stok variant
                if ($item->product_variant_id) {

                    $variant = ProductVariant::find(
                        $item->product_variant_id
                    );

                    if ($variant) {
                        $variant->decrement('stock', $item->qty);
                    }
                }

                // Kurangi stok produk
                $item->product->decrement('stock', $item->qty);

                // Jika stok habis
                if ($item->product->fresh()->stock <= 0) {
                    $item->product->update([
                        'status' => 'sold_out'
                    ]);
                }
            }

            /* ------------------------------------------------------------------ */
            /* HAPUS CART                                                         */
            /* ------------------------------------------------------------------ */

            CartItem::whereIn('id', $selectedIds)->delete();

            DB::commit();

            /* ------------------------------------------------------------------ */
            /* REDIRECT TO PAYMENT PAGE                                           */
            /* ------------------------------------------------------------------ */

            return redirect()->route('orders.payment', $order->id);

        } catch (\Exception $e) {

            DB::rollback();

            return redirect()->route('cart.index')
                ->with('error', 'Checkout gagal: ' . $e->getMessage());
        }
    }
}
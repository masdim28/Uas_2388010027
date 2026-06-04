<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Statistik Dasar
        $totalProduk = Product::count(); //
        
        // Menghitung produk berdasarkan kolom 'status' di database
        $readyStockCount = Product::where('status', 'ready')->count(); 
        
        // PERBAIKAN: Gunakan 'sold_out' sesuai yang tertulis di phpMyAdmin Anda
$soldOutCount = Product::where('status', 'sold_out')->count();
        
        // Menghitung total user dengan role 'user' atau 'customer'
        $totalUser = User::where('role', 'user')->count();

        // Statistik Pesanan & Pendapatan
        $pesananMasuk = Order::where('status_payment', 'pending')->count();
        $totalRevenue = Order::where('status_payment', 'paid')->selectRaw('SUM(total_price - shipping_cost) as net_revenue')->value('net_revenue') ?? 0;

        // 2. Data Grafik Penjualan (Bulan berjalan)
        $salesData = Order::where('status_payment', 'paid')
            ->select(DB::raw("SUM(total_price - shipping_cost) as total"), DB::raw("MONTHNAME(created_at) as month"))
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('created_at')
            ->get();

        // 3. Produk Terpopuler
        $mostClicked = Product::orderBy('clicks', 'desc')->take(3)->get();

        $topCheckout = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select('products.name', 'products.image', DB::raw('SUM(order_items.qty) as total_sold'))
            ->groupBy('products.id', 'products.name', 'products.image')
            ->orderBy('total_sold', 'desc')
            ->take(5)
            ->get();

        //
        return view('admin.index', compact(
            'totalProduk', 'readyStockCount', 'soldOutCount', 
            'totalUser', 'pesananMasuk', 'totalRevenue',
            'mostClicked', 'topCheckout', 'salesData'
        ));
    }

    public function getChartData(\Illuminate\Http\Request $request)
    {
        $filter = $request->input('filter', '1bln');
        $dateStr = $request->input('date', date('Y-m-d'));
        
        try {
            $dateCarbon = \Illuminate\Support\Carbon::parse($dateStr);
        } catch (\Exception $e) {
            $dateCarbon = \Illuminate\Support\Carbon::now();
        }

        $labels = [];
        $data = [];
        $periodLabel = '';

        if ($filter === '1bln') {
            $start = $dateCarbon->copy()->startOfMonth();
            $end = $dateCarbon->copy()->endOfMonth();

            $sales = Order::where('status_payment', 'paid')
                ->whereBetween('created_at', [$start->copy()->startOfDay(), $end->copy()->endOfDay()])
                ->select(DB::raw('DATE(created_at) as date_key'), DB::raw('SUM(total_price - shipping_cost) as total'))
                ->groupBy('date_key')
                ->pluck('total', 'date_key');

            $temp = $start->copy();
            while ($temp->lte($end)) {
                $dayStr = $temp->toDateString();
                $labels[] = $temp->format('d');
                $data[] = (int)($sales[$dayStr] ?? 0);
                $temp->addDay();
            }

            // Period Label e.g. "Mei 2026"
            $periodLabel = $dateCarbon->translatedFormat('F Y');
        } elseif ($filter === '3bln') {
            $start = $dateCarbon->copy()->subMonths(2)->startOfMonth();
            $end = $dateCarbon->copy()->endOfMonth();

            $sales = Order::where('status_payment', 'paid')
                ->whereBetween('created_at', [$start->copy()->startOfDay(), $end->copy()->endOfDay()])
                ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month_key"), DB::raw('SUM(total_price - shipping_cost) as total'))
                ->groupBy('month_key')
                ->pluck('total', 'month_key');

            $temp = $start->copy();
            while ($temp->lte($end)) {
                $monthKey = $temp->format('Y-m');
                $labels[] = $temp->translatedFormat('M Y');
                $data[] = (int)($sales[$monthKey] ?? 0);
                $temp->addMonth();
            }

            // Period Label e.g. "Mar - Mei 2026"
            $periodLabel = $start->translatedFormat('M Y') . ' - ' . $end->translatedFormat('M Y');
        } else { // 1thn
            $start = $dateCarbon->copy()->startOfYear();
            $end = $dateCarbon->copy()->endOfYear();

            $sales = Order::where('status_payment', 'paid')
                ->whereBetween('created_at', [$start->copy()->startOfDay(), $end->copy()->endOfDay()])
                ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month_key"), DB::raw('SUM(total_price - shipping_cost) as total'))
                ->groupBy('month_key')
                ->pluck('total', 'month_key');

            $temp = $start->copy();
            while ($temp->lte($end)) {
                $monthKey = $temp->format('Y-m');
                $labels[] = $temp->translatedFormat('M');
                $data[] = (int)($sales[$monthKey] ?? 0);
                $temp->addMonth();
            }

            // Period Label e.g. "Tahun 2026"
            $periodLabel = 'Tahun ' . $dateCarbon->format('Y');
        }

        $totalRevenuePeriod = array_sum($data);

        return response()->json([
            'labels' => $labels,
            'data' => $data,
            'period_label' => $periodLabel,
            'current_date' => $dateCarbon->toDateString(),
            'total_revenue' => $totalRevenuePeriod,
            'total_revenue_formatted' => 'Rp ' . number_format($totalRevenuePeriod, 0, ',', '.'),
        ]);
    }

    public function getProductsReport(\Illuminate\Http\Request $request)
    {
        $sortBy = $request->input('sort_by', 'checkout');

        $query = Product::leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->select('products.id', 'products.name', 'products.image', 'products.clicks', DB::raw('COALESCE(SUM(order_items.qty), 0) as total_sold'))
            ->groupBy('products.id', 'products.name', 'products.image', 'products.clicks');

        if ($sortBy === 'clicks') {
            $query->orderBy('products.clicks', 'desc');
        } else {
            $query->orderBy('total_sold', 'desc');
        }

        $products = $query->get();

        $products = $products->map(function ($item) {
            $item->image_url = asset('storage/' . $item->image);
            $item->clicks_formatted = number_format($item->clicks) . ' Klik';
            $item->total_sold_formatted = number_format($item->total_sold) . ' Pcs';
            return $item;
        });

        return response()->json($products);
    }

    public function getMonthlySalesReport(\Illuminate\Http\Request $request)
    {
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        $sales = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('orders.status_payment', 'paid')
            ->whereMonth('orders.created_at', $month)
            ->whereYear('orders.created_at', $year)
            ->select(
                'products.id',
                'products.name',
                'products.image',
                'products.clicks',
                DB::raw('SUM(order_items.qty) as total_sold'),
                DB::raw('SUM(order_items.price * order_items.qty) as total_revenue')
            )
            ->groupBy('products.id', 'products.name', 'products.image', 'products.clicks')
            ->orderBy('total_sold', 'desc')
            ->get();

        $sales = $sales->map(function ($item) {
            $item->image_url = asset('storage/' . $item->image);
            $item->total_sold_formatted = number_format($item->total_sold) . ' Pcs';
            $item->total_revenue_formatted = 'Rp ' . number_format($item->total_revenue, 0, ',', '.');
            return $item;
        });

        return response()->json($sales);
    }

    public function getRevenueReport(\Illuminate\Http\Request $request)
    {
        $year = $request->input('year');
        
        $query = Order::where('status_payment', 'paid');
        
        if ($year && $year !== 'all') {
            $query->whereYear('created_at', $year);
        }
        
        $totalRevenue = $query->selectRaw('SUM(total_price - shipping_cost) as net_revenue')->value('net_revenue') ?? 0;
        
        return response()->json([
            'total_revenue' => $totalRevenue,
            'total_revenue_formatted' => 'Rp ' . number_format($totalRevenue, 0, ',', '.'),
        ]);
    }
}
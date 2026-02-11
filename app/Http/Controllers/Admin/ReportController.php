<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        // Sales Overview
        $today = now()->startOfDay();
        $thisMonth = now()->startOfMonth();
        $lastMonth = now()->subMonth()->startOfMonth();

        $salesData = [
            'today' => Order::where('payment_status', 'paid')
                ->whereDate('created_at', $today)
                ->sum('total'),
            'this_month' => Order::where('payment_status', 'paid')
                ->whereDate('created_at', '>=', $thisMonth)
                ->sum('total'),
            'last_month' => Order::where('payment_status', 'paid')
                ->whereDate('created_at', '>=', $lastMonth)
                ->whereDate('created_at', '<', $thisMonth)
                ->sum('total'),
            'total' => Order::where('payment_status', 'paid')->sum('total'),
        ];

        // Top Selling Products
        $topProducts = DB::table('products')
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->select(
                'products.id',
                'products.name',
                'products.image',
                'products.price',
                'products.sale_price',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.total) as total_revenue')
            )
            ->whereNull('products.deleted_at')
            ->groupBy('products.id', 'products.name', 'products.image', 'products.price', 'products.sale_price')
            ->orderBy('total_sold', 'desc')
            ->limit(10)
            ->get();

        // Recent Orders
        $recentOrders = Order::with('user')
            ->latest()
            ->limit(10)
            ->get();

        // Customer Stats
        $customerStats = [
            'total_customers' => User::where('role', 'customer')->count(),
            'new_this_month' => User::where('role', 'customer')
                ->whereDate('created_at', '>=', $thisMonth)
                ->count(),
            'active_customers' => User::where('role', 'customer')
                ->where('is_active', true)
                ->count(),
        ];

        // Order Status Distribution
        $ordersByStatus = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        // Low Stock Products
        $lowStock = Product::where('quantity', '<', 10)
            ->where('is_active', true)
            ->orderBy('quantity', 'asc')
            ->limit(10)
            ->get();

        return view('admin.reports.index', compact(
            'salesData',
            'topProducts',
            'recentOrders',
            'customerStats',
            'ordersByStatus',
            'lowStock'
        ));
    }

    public function sales(Request $request)
    {
        $startDate = $request->get('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $orders = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with('user')
            ->latest()
            ->get();

        $summary = [
            'total_orders' => $orders->count(),
            'total_revenue' => $orders->sum('total'),
            'average_order' => $orders->count() > 0 ? $orders->avg('total') : 0,
            'total_items' => $orders->sum(function($order) {
                return $order->items->sum('quantity');
            }),
        ];

        return view('admin.reports.sales', compact('orders', 'summary', 'startDate', 'endDate'));
    }
}
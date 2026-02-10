<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total'),
            'total_orders' => Order::count(),
            'total_products' => Product::count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'low_stock_products' => Product::where('quantity', '<', 10)->count(),
        ];

        $recent_orders = Order::with('user')
            ->latest()
            ->take(10)
            ->get();

        $top_products = Product::withCount('orderItems')
            ->orderBy('order_items_count', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_orders', 'top_products'));
    }
}

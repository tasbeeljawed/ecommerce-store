@extends('layouts.admin')

@section('header', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Revenue -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-semibold">Total Revenue</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">${{ number_format($stats['total_revenue'], 2) }}</p>
            </div>
            <div class="bg-green-100 rounded-full p-3">
                <i class="fas fa-dollar-sign text-green-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Total Orders -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-semibold">Total Orders</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['total_orders'] }}</p>
            </div>
            <div class="bg-blue-100 rounded-full p-3">
                <i class="fas fa-shopping-cart text-blue-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Total Products -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-semibold">Total Products</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['total_products'] }}</p>
            </div>
            <div class="bg-purple-100 rounded-full p-3">
                <i class="fas fa-box text-purple-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Total Customers -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-semibold">Total Customers</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['total_customers'] }}</p>
            </div>
            <div class="bg-yellow-100 rounded-full p-3">
                <i class="fas fa-users text-yellow-600 text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Pending Orders -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Pending Orders</h3>
            <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm font-semibold">
                {{ $stats['pending_orders'] }}
            </span>
        </div>
    </div>

    <!-- Low Stock Alert -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Low Stock Products</h3>
            <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-semibold">
                {{ $stats['low_stock_products'] }}
            </span>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<div class="bg-white rounded-lg shadow-md p-6 mb-8">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Orders</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order #</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($recent_orders as $order)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                {{ $order->order_number }}
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $order->user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">${{ number_format($order->total, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full 
                                {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $order->status === 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $order->status === 'shipped' ? 'bg-purple-100 text-purple-800' : '' }}
                                {{ $order->status === 'delivered' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $order->created_at->format('M d, Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Top Products -->
<div class="bg-white rounded-lg shadow-md p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Top Selling Products</h3>
    <div class="space-y-4">
        @foreach($top_products as $product)
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-12 h-12 rounded object-cover">
                    @else
                        <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
                            <i class="fas fa-image text-gray-400"></i>
                        </div>
                    @endif
                    <div class="ml-4">
                        <p class="font-semibold text-gray-800">{{ $product->name }}</p>
                        <p class="text-sm text-gray-500">{{ $product->order_items_count }} sales</p>
                    </div>
                </div>
                <span class="text-indigo-600 font-semibold">${{ number_format($product->final_price, 2) }}</span>
            </div>
        @endforeach
    </div>
</div>

@endsection

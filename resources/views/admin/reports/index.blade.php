@extends('layouts.admin')

@section('header', 'Reports & Analytics')

@section('content')

<!-- Sales Overview -->
<div class="mb-8">
    <h3 class="text-xl font-bold text-gray-800 mb-4">Sales Overview</h3>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-gray-500 text-sm">Today's Sales</div>
            <div class="text-3xl font-bold text-gray-800 mt-1">${{ number_format($salesData['today'], 2) }}</div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-gray-500 text-sm">This Month</div>
            <div class="text-3xl font-bold text-gray-800 mt-1">${{ number_format($salesData['this_month'], 2) }}</div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-gray-500 text-sm">Last Month</div>
            <div class="text-3xl font-bold text-gray-800 mt-1">${{ number_format($salesData['last_month'], 2) }}</div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-gray-500 text-sm">Total Revenue</div>
            <div class="text-3xl font-bold text-gray-800 mt-1">${{ number_format($salesData['total'], 2) }}</div>
        </div>
    </div>
</div>

<!-- Customer Stats -->
<div class="mb-8">
    <h3 class="text-xl font-bold text-gray-800 mb-4">Customer Statistics</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-gray-500 text-sm">Total Customers</div>
            <div class="text-3xl font-bold text-gray-800 mt-1">{{ $customerStats['total_customers'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-gray-500 text-sm">New This Month</div>
            <div class="text-3xl font-bold text-gray-800 mt-1">{{ $customerStats['new_this_month'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-gray-500 text-sm">Active Customers</div>
            <div class="text-3xl font-bold text-gray-800 mt-1">{{ $customerStats['active_customers'] }}</div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    
    <!-- Top Selling Products -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Top Selling Products</h3>
        <div class="space-y-4">
            @foreach($topProducts as $product)
                <div class="flex items-center justify-between border-b pb-3">
                    <div class="flex items-center">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-12 h-12 rounded object-cover">
                        @else
                            <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
                                <i class="fas fa-image text-gray-400"></i>
                            </div>
                        @endif
                        <div class="ml-4">
                            <p class="font-semibold text-gray-800">{{ $product->name }}</p>
                            <p class="text-sm text-gray-500">{{ $product->total_sold }} units sold</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-green-600">${{ number_format($product->total_revenue, 2) }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Order Status Distribution -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Orders by Status</h3>
        <div class="space-y-4">
            @foreach(['pending', 'processing', 'shipped', 'delivered', 'cancelled'] as $status)
                @php
                    $count = $ordersByStatus[$status] ?? 0;
                    $percentage = $ordersByStatus->sum() > 0 ? ($count / $ordersByStatus->sum() * 100) : 0;
                @endphp
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700">{{ ucfirst($status) }}</span>
                        <span class="text-sm font-medium text-gray-700">{{ $count }} ({{ number_format($percentage, 1) }}%)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="h-2 rounded-full 
                            {{ $status === 'pending' ? 'bg-yellow-500' : '' }}
                            {{ $status === 'processing' ? 'bg-blue-500' : '' }}
                            {{ $status === 'shipped' ? 'bg-purple-500' : '' }}
                            {{ $status === 'delivered' ? 'bg-green-500' : '' }}
                            {{ $status === 'cancelled' ? 'bg-red-500' : '' }}"
                             style="width: {{ $percentage }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>

<!-- Low Stock Alert -->
<div class="bg-white rounded-lg shadow-md p-6 mb-8">
    <h3 class="text-lg font-bold text-gray-800 mb-4">
        <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
        Low Stock Products
    </h3>
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">SKU</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($lowStock as $product)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-10 h-10 rounded object-cover">
                                @else
                                    <div class="w-10 h-10 bg-gray-200 rounded flex items-center justify-center">
                                        <i class="fas fa-image text-gray-400"></i>
                                    </div>
                                @endif
                                <span class="ml-3 font-medium text-gray-800">{{ $product->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ $product->sku }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-semibold {{ $product->quantity < 5 ? 'text-red-600' : 'text-orange-600' }}">
                                {{ $product->quantity }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">
                                {{ $product->quantity === 0 ? 'Out of Stock' : 'Low Stock' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            No low stock products
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Export Options -->
<div class="bg-white rounded-lg shadow-md p-6">
    <h3 class="text-lg font-bold text-gray-800 mb-4">Generate Reports</h3>
    <div class="flex gap-4">
        <a href="{{ route('admin.reports.sales') }}" 
           class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
            <i class="fas fa-chart-line mr-2"></i> Sales Report
        </a>
    </div>
</div>

@endsection
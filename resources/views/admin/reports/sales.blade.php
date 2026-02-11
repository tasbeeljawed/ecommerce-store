@extends('layouts.admin')

@section('header', 'Sales Report')

@section('content')

<!-- Date Filter -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <form method="GET" action="{{ route('admin.reports.sales') }}" class="flex items-end gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
            <input type="date" 
                   name="start_date" 
                   value="{{ $startDate }}"
                   class="px-4 py-2 border border-gray-300 rounded-lg">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
            <input type="date" 
                   name="end_date" 
                   value="{{ $endDate }}"
                   class="px-4 py-2 border border-gray-300 rounded-lg">
        </div>
        <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
            Generate Report
        </button>
    </form>
</div>

<!-- Summary -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="text-gray-500 text-sm">Total Orders</div>
        <div class="text-3xl font-bold text-gray-800 mt-1">{{ $summary['total_orders'] }}</div>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="text-gray-500 text-sm">Total Revenue</div>
        <div class="text-3xl font-bold text-gray-800 mt-1">${{ number_format($summary['total_revenue'], 2) }}</div>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="text-gray-500 text-sm">Average Order</div>
        <div class="text-3xl font-bold text-gray-800 mt-1">${{ number_format($summary['average_order'], 2) }}</div>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="text-gray-500 text-sm">Total Items Sold</div>
        <div class="text-3xl font-bold text-gray-800 mt-1">{{ $summary['total_items'] }}</div>
    </div>
</div>

<!-- Orders Table -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <table class="min-w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order #</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Items</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($orders as $order)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="{{ route('admin.orders.show', $order->id) }}" 
                           class="text-indigo-600 hover:text-indigo-900 font-medium">
                            {{ $order->order_number }}
                        </a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $order->user->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $order->created_at->format('M d, Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $order->items->count() }}</td>
                    <td class="px-6 py-4 whitespace-nowrap font-semibold">${{ number_format($order->total, 2) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs rounded-full 
                            {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $order->status === 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $order->status === 'shipped' ? 'bg-purple-100 text-purple-800' : '' }}
                            {{ $order->status === 'delivered' ? 'bg-green-100 text-green-800' : '' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        No orders found for this date range
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    <a href="{{ route('admin.reports.index') }}" class="text-indigo-600 hover:text-indigo-800">
        <i class="fas fa-arrow-left mr-2"></i> Back to Reports
    </a>
</div>

@endsection
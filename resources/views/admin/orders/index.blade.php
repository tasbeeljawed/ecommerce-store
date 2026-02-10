@extends('layouts.admin')

@section('header', 'Orders Management')

@section('content')

<div class="mb-6 flex justify-between items-center">
    <form method="GET" action="{{ route('admin.orders.index') }}" class="flex space-x-2">
        <input type="text" 
               name="search" 
               value="{{ request('search') }}" 
               placeholder="Search orders..."
               class="px-4 py-2 border border-gray-300 rounded-lg">
        <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg">
            <option value="">All Statuses</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
            <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped</option>
            <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>
        <button type="submit" class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700">
            Filter
        </button>
    </form>
</div>

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <table class="min-w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order #</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Items</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Payment</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($orders as $order)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">
                            {{ $order->order_number }}
                        </a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div>
                            <div class="font-medium text-gray-800">{{ $order->user->name }}</div>
                            <div class="text-sm text-gray-500">{{ $order->user->email }}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $order->items->count() }} item(s)
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="font-semibold">${{ number_format($order->total, 2) }}</span>
                    </td>
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
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs rounded-full
                            {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $order->payment_status === 'pending' ? 'bg-orange-100 text-orange-800' : '' }}
                            {{ $order->payment_status === 'failed' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $order->payment_status === 'refunded' ? 'bg-gray-100 text-gray-800' : '' }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $order->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="{{ route('admin.orders.show', $order->id) }}" 
                           class="text-indigo-600 hover:text-indigo-900">
                            <i class="fas fa-eye"></i> View
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                        No orders found
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $orders->links() }}
</div>

@endsection
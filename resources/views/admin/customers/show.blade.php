@extends('layouts.admin')

@section('header', 'Customer Details')

@section('content')

<div class="max-w-6xl">
    
    <!-- Customer Header -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex justify-between items-start">
            <div class="flex items-center">
                <div class="w-16 h-16 bg-indigo-600 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                </div>
                <div class="ml-4">
                    <h2 class="text-2xl font-bold text-gray-800">{{ $customer->name }}</h2>
                    <p class="text-gray-600">{{ $customer->email }}</p>
                    @if($customer->phone)
                        <p class="text-gray-600">{{ $customer->phone }}</p>
                    @endif
                </div>
            </div>
            <div class="text-right">
                <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $customer->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $customer->is_active ? 'Active' : 'Inactive' }}
                </span>
                <p class="text-sm text-gray-600 mt-2">Member since {{ $customer->created_at->format('M d, Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-gray-500 text-sm">Total Orders</div>
            <div class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['total_orders'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-gray-500 text-sm">Total Spent</div>
            <div class="text-3xl font-bold text-gray-800 mt-1">${{ number_format($stats['total_spent'], 2) }}</div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-gray-500 text-sm">Pending Orders</div>
            <div class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['pending_orders'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-gray-500 text-sm">Completed Orders</div>
            <div class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['completed_orders'] }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Recent Orders -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Recent Orders</h3>
                
                @if($customer->orders->count() > 0)
                    <div class="space-y-4">
                        @foreach($customer->orders as $order)
                            <div class="flex items-center justify-between border-b pb-4">
                                <div>
                                    <a href="{{ route('admin.orders.show', $order->id) }}" 
                                       class="font-semibold text-indigo-600 hover:text-indigo-900">
                                        {{ $order->order_number }}
                                    </a>
                                    <p class="text-sm text-gray-600">{{ $order->created_at->format('M d, Y') }}</p>
                                    <span class="px-2 py-1 text-xs rounded-full inline-block mt-1
                                        {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $order->status === 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $order->status === 'shipped' ? 'bg-purple-100 text-purple-800' : '' }}
                                        {{ $order->status === 'delivered' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-800">${{ number_format($order->total, 2) }}</p>
                                    <p class="text-sm text-gray-600">{{ $order->items->count() }} item(s)</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">No orders yet</p>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            
            <!-- Actions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Actions</h3>
                
                <form action="{{ route('admin.customers.toggle-status', $customer->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" 
                            class="w-full px-4 py-2 rounded-lg font-semibold {{ $customer->is_active ? 'bg-red-600 hover:bg-red-700 text-white' : 'bg-green-600 hover:bg-green-700 text-white' }}">
                        {{ $customer->is_active ? 'Deactivate Account' : 'Activate Account' }}
                    </button>
                </form>
            </div>

            <!-- Saved Addresses -->
            @if($customer->addresses->count() > 0)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Saved Addresses</h3>
                    
                    @foreach($customer->addresses->unique('type')->take(2) as $address)
                        <div class="mb-4 pb-4 border-b last:border-b-0">
                            <p class="font-semibold text-gray-700 mb-2">{{ ucfirst($address->type) }} Address</p>
                            <div class="text-sm text-gray-600">
                                <p>{{ $address->full_name }}</p>
                                <p>{{ $address->address_line_1 }}</p>
                                @if($address->address_line_2)
                                    <p>{{ $address->address_line_2 }}</p>
                                @endif
                                <p>{{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>

    </div>

    <div class="mt-6">
        <a href="{{ route('admin.customers.index') }}" class="text-indigo-600 hover:text-indigo-800">
            <i class="fas fa-arrow-left mr-2"></i> Back to Customers
        </a>
    </div>

</div>

@endsection
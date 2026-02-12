@extends('layouts.app')

@section('title', 'My Orders - E-Commerce Store')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">My Orders</h1>
        <p class="text-gray-600 mt-2">Track and manage your orders</p>
    </div>

    @if($orders->count() > 0)
        <div class="space-y-6">
            @foreach($orders as $order)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    
                    <!-- Order Header -->
                    <div class="bg-gray-50 px-6 py-4 border-b">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                            <div class="flex items-center space-x-4">
                                <div>
                                    <p class="text-sm text-gray-600">Order Number</p>
                                    <p class="font-bold text-gray-800">{{ $order->order_number }}</p>
                                </div>
                                <div class="hidden md:block">
                                    <p class="text-sm text-gray-600">Date</p>
                                    <p class="font-semibold text-gray-800">{{ $order->created_at->format('M d, Y') }}</p>
                                </div>
                                <div class="hidden md:block">
                                    <p class="text-sm text-gray-600">Total</p>
                                    <p class="font-bold text-indigo-600">${{ number_format($order->total, 2) }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-3 mt-3 md:mt-0">
                                <span class="px-3 py-1 rounded-full text-sm font-semibold
                                    {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $order->status === 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $order->status === 'shipped' ? 'bg-purple-100 text-purple-800' : '' }}
                                    {{ $order->status === 'delivered' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                                
                                <a href="{{ route('orders.show', $order->id) }}" 
                                   class="text-indigo-600 hover:text-indigo-800 font-semibold">
                                    View Details →
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items Preview -->
                    <div class="px-6 py-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            @foreach($order->items->take(4) as $item)
                                <div class="flex items-center space-x-3">
                                    @if($item->product && $item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}" 
                                             alt="{{ $item->product_name }}" 
                                             class="w-16 h-16 object-cover rounded">
                                    @else
                                        <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                            <i class="fas fa-box text-gray-400"></i>
                                        </div>
                                    @endif
                                    
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-gray-800 text-sm truncate">{{ $item->product_name }}</p>
                                        <p class="text-xs text-gray-600">Qty: {{ $item->quantity }}</p>
                                        <p class="text-sm font-semibold text-gray-800">${{ number_format($item->total, 2) }}</p>
                                    </div>
                                </div>
                            @endforeach
                            
                            @if($order->items->count() > 4)
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-500 text-sm">
                                        +{{ $order->items->count() - 4 }} more item(s)
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Order Actions -->
                    <div class="bg-gray-50 px-6 py-3 border-t flex justify-between items-center">
                        <div class="text-sm">
                            <span class="text-gray-600">Payment: </span>
                            <span class="font-semibold {{ $order->payment_status === 'paid' ? 'text-green-600' : 'text-orange-600' }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </div>
                        
                        <div class="flex space-x-3">
                            @if($order->status === 'delivered')
                                <button class="text-sm text-indigo-600 hover:text-indigo-800 font-semibold">
                                    <i class="fas fa-redo mr-1"></i> Reorder
                                </button>
                            @endif
                            
                            @if(in_array($order->status, ['pending', 'processing']))
                                <button class="text-sm text-red-600 hover:text-red-800 font-semibold">
                                    <i class="fas fa-times mr-1"></i> Cancel Order
                                </button>
                            @endif
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $orders->links() }}
        </div>

    @else
        <!-- Empty State -->
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <div class="mb-6">
                <i class="fas fa-shopping-bag text-gray-300 text-6xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-800 mb-2">No Orders Yet</h3>
            <p class="text-gray-600 mb-6">You haven't placed any orders yet. Start shopping to see your orders here!</p>
            <a href="{{ route('products.index') }}" 
               class="inline-block bg-indigo-600 text-white px-8 py-3 rounded-lg hover:bg-indigo-700 font-semibold">
                Start Shopping
            </a>
        </div>
    @endif

</div>
@endsection
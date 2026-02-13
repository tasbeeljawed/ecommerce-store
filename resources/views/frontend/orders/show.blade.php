@extends('layouts.app')

@section('title', 'Order Details - E-Commerce Store')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('orders.index') }}" class="text-indigo-600 hover:text-indigo-800">
            <i class="fas fa-arrow-left mr-2"></i> Back to My Orders
        </a>
    </div>

    <!-- Order Header -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Order {{ $order->order_number }}</h1>
                <p class="text-gray-600 mt-2">Placed on {{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
            </div>
            <div class="mt-4 md:mt-0">
                <span class="px-4 py-2 rounded-full text-sm font-semibold
                    {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                    {{ $order->status === 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                    {{ $order->status === 'shipped' ? 'bg-purple-100 text-purple-800' : '' }}
                    {{ $order->status === 'delivered' ? 'bg-green-100 text-green-800' : '' }}
                    {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                    {{ ucfirst($order->status) }}
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Order Items -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6">Order Items</h2>
                
                <div class="space-y-4">
                    @foreach($order->items as $item)
                        <div class="flex items-center space-x-4 pb-4 border-b last:border-b-0">
                            @if($item->product && $item->product->image)
                                <img src="{{ asset('storage/' . $item->product->image) }}" 
                                     alt="{{ $item->product_name }}" 
                                     class="w-20 h-20 object-cover rounded">
                            @else
                                <div class="w-20 h-20 bg-gray-200 rounded flex items-center justify-center">
                                    <i class="fas fa-box text-gray-400 text-2xl"></i>
                                </div>
                            @endif
                            
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-800">
                                    @if($item->product)
                                        <a href="{{ route('products.show', $item->product->slug) }}" 
                                           class="hover:text-indigo-600">
                                            {{ $item->product_name }}
                                        </a>
                                    @else
                                        {{ $item->product_name }}
                                    @endif
                                </h3>
                                <p class="text-sm text-gray-600">SKU: {{ $item->product_sku }}</p>
                                <p class="text-sm text-gray-600">Quantity: {{ $item->quantity }}</p>
                            </div>
                            
                            <div class="text-right">
                                <p class="font-bold text-gray-800">${{ number_format($item->total, 2) }}</p>
                                <p class="text-sm text-gray-600">${{ number_format($item->price, 2) }} each</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Order Summary -->
                <div class="mt-6 border-t pt-6">
                    <div class="space-y-2">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal</span>
                            <span>${{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Tax</span>
                            <span>${{ number_format($order->tax, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Shipping</span>
                            <span>${{ number_format($order->shipping, 2) }}</span>
                        </div>
                        @if($order->discount > 0)
                            <div class="flex justify-between text-green-600">
                                <span>Discount</span>
                                <span>-${{ number_format($order->discount, 2) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-xl font-bold text-gray-800 border-t pt-3">
                            <span>Total</span>
                            <span>${{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            
            <!-- Shipping Address -->
            @if($order->shippingAddress)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                        <i class="fas fa-truck text-indigo-600 mr-2"></i> Shipping Address
                    </h3>
                    <div class="text-gray-600 text-sm space-y-1">
                        <p class="font-semibold text-gray-800">{{ $order->shippingAddress->full_name }}</p>
                        <p>{{ $order->shippingAddress->address_line_1 }}</p>
                        @if($order->shippingAddress->address_line_2)
                            <p>{{ $order->shippingAddress->address_line_2 }}</p>
                        @endif
                        <p>{{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }} {{ $order->shippingAddress->postal_code }}</p>
                        <p class="mt-2">{{ $order->shippingAddress->phone }}</p>
                    </div>
                </div>
            @endif

            <!-- Billing Address -->
            @if($order->billingAddress)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                        <i class="fas fa-file-invoice text-indigo-600 mr-2"></i> Billing Address
                    </h3>
                    <div class="text-gray-600 text-sm space-y-1">
                        <p class="font-semibold text-gray-800">{{ $order->billingAddress->full_name }}</p>
                        <p>{{ $order->billingAddress->address_line_1 }}</p>
                        @if($order->billingAddress->address_line_2)
                            <p>{{ $order->billingAddress->address_line_2 }}</p>
                        @endif
                        <p>{{ $order->billingAddress->city }}, {{ $order->billingAddress->state }} {{ $order->billingAddress->postal_code }}</p>
                    </div>
                </div>
            @endif

            <!-- Payment Info -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">
                    <i class="fas fa-credit-card text-indigo-600 mr-2"></i> Payment Information
                </h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Method:</span>
                        <span class="font-semibold text-gray-800">{{ ucfirst($order->payment_method) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status:</span>
                        <span class="font-semibold {{ $order->payment_status === 'paid' ? 'text-green-600' : 'text-orange-600' }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                    @if($order->transaction_id)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Transaction ID:</span>
                            <span class="font-mono text-xs text-gray-800">{{ $order->transaction_id }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Order Notes -->
            @if($order->notes)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                        <i class="fas fa-sticky-note text-indigo-600 mr-2"></i> Order Notes
                    </h3>
                    <p class="text-gray-600 text-sm">{{ $order->notes }}</p>
                </div>
            @endif

            <!-- Actions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="space-y-3">
                    @if($order->status === 'delivered')
                        <button class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 font-semibold">
                            <i class="fas fa-redo mr-2"></i> Reorder
                        </button>
                    @endif
                    
                    @if(in_array($order->status, ['pending', 'processing']))
                        <form action="{{ route('orders.cancel', $order->id) }}" 
                              method="POST"
                              onsubmit="return confirm('Are you sure you want to cancel this order?')">
                            @csrf
                            <button type="submit" class="w-full bg-red-600 text-white py-2 rounded-lg hover:bg-red-700 font-semibold">
                                <i class="fas fa-times mr-2"></i> Cancel Order
                            </button>
                        </form>
                    @endif
                    
                    <button class="w-full bg-gray-200 text-gray-800 py-2 rounded-lg hover:bg-gray-300 font-semibold">
                        <i class="fas fa-download mr-2"></i> Download Invoice
                    </button>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
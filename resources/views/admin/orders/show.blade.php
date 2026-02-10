@extends('layouts.admin')

@section('header', 'Order Details')

@section('content')

<div class="max-w-5xl">
    
    <!-- Order Header -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex justify-between items-start">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">{{ $order->order_number }}</h2>
                <p class="text-gray-600 mt-1">Placed on {{ $order->created_at->format('M d, Y \a\t h:i A') }}</p>
            </div>
            <div class="text-right">
                <span class="px-3 py-1 rounded-full text-sm font-semibold
                    {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                    {{ $order->status === 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                    {{ $order->status === 'shipped' ? 'bg-purple-100 text-purple-800' : '' }}
                    {{ $order->status === 'delivered' ? 'bg-green-100 text-green-800' : '' }}
                    {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                    {{ ucfirst($order->status) }}
                </span>
                <p class="text-sm text-gray-600 mt-2">
                    Payment: 
                    <span class="font-semibold {{ $order->payment_status === 'paid' ? 'text-green-600' : 'text-orange-600' }}">
                        {{ ucfirst($order->payment_status) }}
                    </span>
                </p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Order Items -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Order Items</h3>
                
                <div class="space-y-4">
                    @foreach($order->items as $item)
                        <div class="flex items-center justify-between border-b pb-4">
                            <div class="flex items-center space-x-4">
                                @if($item->product && $item->product->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}" 
                                         alt="{{ $item->product_name }}" 
                                         class="w-16 h-16 object-cover rounded">
                                @else
                                    <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                        <i class="fas fa-box text-gray-400"></i>
                                    </div>
                                @endif
                                
                                <div>
                                    <h4 class="font-semibold text-gray-800">{{ $item->product_name }}</h4>
                                    <p class="text-sm text-gray-600">SKU: {{ $item->product_sku }}</p>
                                    <p class="text-sm text-gray-600">Qty: {{ $item->quantity }}</p>
                                </div>
                            </div>
                            
                            <div class="text-right">
                                <p class="font-semibold text-gray-800">${{ number_format($item->total, 2) }}</p>
                                <p class="text-sm text-gray-600">${{ number_format($item->price, 2) }} each</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Order Summary -->
                <div class="mt-6 border-t pt-4">
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
                        <div class="flex justify-between text-lg font-bold text-gray-800 border-t pt-2">
                            <span>Total</span>
                            <span>${{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            
            <!-- Customer Info -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Customer</h3>
                <div class="space-y-2">
                    <p class="text-gray-800 font-semibold">{{ $order->user->name }}</p>
                    <p class="text-gray-600">{{ $order->user->email }}</p>
                    @if($order->user->phone)
                        <p class="text-gray-600">{{ $order->user->phone }}</p>
                    @endif
                </div>
            </div>

            <!-- Shipping Address -->
            @if($order->shippingAddress)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Shipping Address</h3>
                    <div class="text-gray-600">
                        <p>{{ $order->shippingAddress->full_name }}</p>
                        <p>{{ $order->shippingAddress->address_line_1 }}</p>
                        @if($order->shippingAddress->address_line_2)
                            <p>{{ $order->shippingAddress->address_line_2 }}</p>
                        @endif
                        <p>{{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }} {{ $order->shippingAddress->postal_code }}</p>
                        <p class="mt-2">{{ $order->shippingAddress->phone }}</p>
                    </div>
                </div>
            @endif

            <!-- Payment Info -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Payment</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Method:</span>
                        <span class="font-semibold">{{ ucfirst($order->payment_method) }}</span>
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
                            <span class="font-mono text-sm">{{ $order->transaction_id }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Update Status -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Update Order</h3>
                
                <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST" class="mb-4">
                    @csrf
                    @method('PATCH')
                    <label class="block text-sm font-medium text-gray-700 mb-2">Order Status</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg mb-3">
                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                        Update Status
                    </button>
                </form>

                <form action="{{ route('admin.orders.update-payment', $order->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Status</label>
                    <select name="payment_status" class="w-full px-4 py-2 border border-gray-300 rounded-lg mb-3">
                        <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Failed</option>
                        <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                    </select>
                    <button type="submit" class="w-full bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                        Update Payment
                    </button>
                </form>
            </div>

            @if($order->notes)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Order Notes</h3>
                    <p class="text-gray-600">{{ $order->notes }}</p>
                </div>
            @endif

        </div>

    </div>

    <div class="mt-6">
        <a href="{{ route('admin.orders.index') }}" class="text-indigo-600 hover:text-indigo-800">
            <i class="fas fa-arrow-left mr-2"></i> Back to Orders
        </a>
    </div>

</div>

@endsection
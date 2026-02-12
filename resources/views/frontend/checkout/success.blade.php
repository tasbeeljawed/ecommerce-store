@extends('layouts.app')

@section('title', 'Order Successful - E-Commerce Store')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    
    <div class="bg-white rounded-lg shadow-lg p-8 text-center">
        
        <!-- Success Icon -->
        <div class="mb-6">
            <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto">
                <i class="fas fa-check text-green-600 text-5xl"></i>
            </div>
        </div>

        <!-- Success Message -->
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Order Placed Successfully!</h1>
        <p class="text-lg text-gray-600 mb-8">
            Thank you for your order. We've received your order and will process it shortly.
        </p>

        <!-- Order Details -->
        <div class="bg-gray-50 rounded-lg p-6 mb-8">
            <div class="grid grid-cols-2 gap-4 text-left">
                <div>
                    <p class="text-sm text-gray-600">Order Number</p>
                    <p class="font-bold text-gray-800">{{ $order->order_number }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Order Date</p>
                    <p class="font-bold text-gray-800">{{ $order->created_at->format('M d, Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Amount</p>
                    <p class="font-bold text-indigo-600 text-xl">${{ number_format($order->total, 2) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Payment Method</p>
                    <p class="font-bold text-gray-800">{{ ucfirst($order->payment_method) }}</p>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="text-left mb-8">
            <h3 class="font-bold text-gray-800 mb-4">Order Items</h3>
            <div class="space-y-3">
                @foreach($order->items as $item)
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                        <div>
                            <p class="font-semibold text-gray-800">{{ $item->product_name }}</p>
                            <p class="text-sm text-gray-600">Qty: {{ $item->quantity }} × ${{ number_format($item->price, 2) }}</p>
                        </div>
                        <p class="font-semibold text-gray-800">${{ number_format($item->total, 2) }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Actions -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('home') }}" 
               class="bg-indigo-600 text-white px-8 py-3 rounded-lg hover:bg-indigo-700 font-semibold">
                Continue Shopping
            </a>
            <a href="#" 
               class="bg-gray-200 text-gray-800 px-8 py-3 rounded-lg hover:bg-gray-300 font-semibold">
                View Order Details
            </a>
        </div>

        <!-- Email Confirmation -->
        <div class="mt-8 p-4 bg-blue-50 rounded-lg">
            <p class="text-sm text-gray-600">
                <i class="fas fa-envelope text-blue-600 mr-2"></i>
                A confirmation email has been sent to <strong>{{ $order->user->email }}</strong>
            </p>
        </div>

    </div>

</div>
@endsection

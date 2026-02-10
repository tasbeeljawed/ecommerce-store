@extends('layouts.app')

@section('title', 'Shopping Cart - E-Commerce Store')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <h1 class="text-3xl font-bold text-gray-800 mb-8">Shopping Cart</h1>

    @if($cart_items->isEmpty())
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <i class="fas fa-shopping-cart text-6xl text-gray-300 mb-4"></i>
            <p class="text-xl text-gray-600 mb-4">Your cart is empty</p>
            <a href="{{ route('products.index') }}" class="inline-block bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700">
                Continue Shopping
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Cart Items -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    @foreach($cart_items as $item)
                        <div class="flex items-center p-6 border-b last:border-b-0">
                            <a href="{{ route('products.show', $item->product->slug) }}" class="flex-shrink-0">
                                @if($item->product->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}" 
                                         alt="{{ $item->product->name }}" 
                                         class="w-24 h-24 object-cover rounded">
                                @else
                                    <div class="w-24 h-24 bg-gray-200 rounded flex items-center justify-center">
                                        <i class="fas fa-image text-gray-400 text-3xl"></i>
                                    </div>
                                @endif
                            </a>
                            
                            <div class="ml-6 flex-1">
                                <h3 class="text-lg font-semibold text-gray-800">
                                    <a href="{{ route('products.show', $item->product->slug) }}" class="hover:text-indigo-600">
                                        {{ $item->product->name }}
                                    </a>
                                </h3>
                                <p class="text-sm text-gray-600 mt-1">{{ $item->product->category->name }}</p>
                                <p class="text-lg font-bold text-indigo-600 mt-2">
                                    ${{ number_format($item->product->final_price, 2) }}
                                </p>
                            </div>
                            
                            <div class="flex items-center space-x-4">
                                <!-- Quantity -->
                                <form action="{{ route('cart.update', $item->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <div class="flex items-center border border-gray-300 rounded">
                                        <button type="button" 
                                                onclick="this.nextElementSibling.stepDown(); this.form.submit();"
                                                class="px-3 py-1 hover:bg-gray-100">-</button>
                                        <input type="number" 
                                               name="quantity" 
                                               value="{{ $item->quantity }}" 
                                               min="1" 
                                               max="{{ $item->product->quantity }}"
                                               class="w-16 text-center border-x border-gray-300 py-1">
                                        <button type="button" 
                                                onclick="this.previousElementSibling.stepUp(); this.form.submit();"
                                                class="px-3 py-1 hover:bg-gray-100">+</button>
                                    </div>
                                </form>
                                
                                <!-- Remove -->
                                <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                    <h2 class="text-xl font-bold text-gray-800 mb-6">Order Summary</h2>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal</span>
                            <span>${{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Tax (10%)</span>
                            <span>${{ number_format($tax, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Shipping</span>
                            <span>${{ number_format($shipping, 2) }}</span>
                        </div>
                        <div class="border-t pt-3">
                            <div class="flex justify-between text-xl font-bold text-gray-800">
                                <span>Total</span>
                                <span>${{ number_format($total, 2) }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <a href="{{ route('checkout.index') }}" 
                       class="block w-full bg-indigo-600 text-white text-center py-3 rounded-lg font-semibold hover:bg-indigo-700 mb-3">
                        Proceed to Checkout
                    </a>
                    
                    <a href="{{ route('products.index') }}" 
                       class="block w-full bg-gray-200 text-gray-800 text-center py-3 rounded-lg font-semibold hover:bg-gray-300">
                        Continue Shopping
                    </a>
                </div>
            </div>

        </div>
    @endif

</div>
@endsection

@extends('layouts.app')

@section('title', 'Checkout - E-Commerce Store')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <h1 class="text-3xl font-bold text-gray-800 mb-8">Checkout</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Checkout Form -->
        <div class="lg:col-span-2">
            <form action="{{ route('checkout.process') }}" method="POST">
                @csrf

                <!-- Shipping Address -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Shipping Address</h2>
                    
                    @if($addresses->where('type', 'shipping')->count() > 0)
                        <div class="space-y-3 mb-4">
                            @foreach($addresses->where('type', 'shipping') as $address)
                                <label class="flex items-start p-4 border-2 rounded-lg cursor-pointer hover:border-indigo-500 {{ $address->is_default ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200' }}">
                                    <input type="radio" 
                                           name="shipping_address_id" 
                                           value="{{ $address->id }}" 
                                           {{ $address->is_default ? 'checked' : '' }}
                                           required
                                           class="mt-1">
                                    <div class="ml-3">
                                        <div class="font-semibold text-gray-800">{{ $address->full_name }}</div>
                                        <div class="text-sm text-gray-600">{{ $address->full_address }}</div>
                                        <div class="text-sm text-gray-600">{{ $address->phone }}</div>
                                        @if($address->is_default)
                                            <span class="inline-block mt-1 bg-indigo-100 text-indigo-800 text-xs px-2 py-1 rounded">Default</span>
                                        @endif
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    @else
                        <p class="text-red-600 mb-4">You need to add a shipping address first.</p>
                    @endif
                </div>

                <!-- Billing Address -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Billing Address</h2>
                    
                    @if($addresses->where('type', 'billing')->count() > 0)
                        <div class="space-y-3 mb-4">
                            @foreach($addresses->where('type', 'billing') as $address)
                                <label class="flex items-start p-4 border-2 rounded-lg cursor-pointer hover:border-indigo-500 {{ $address->is_default ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200' }}">
                                    <input type="radio" 
                                           name="billing_address_id" 
                                           value="{{ $address->id }}" 
                                           {{ $address->is_default ? 'checked' : '' }}
                                           required
                                           class="mt-1">
                                    <div class="ml-3">
                                        <div class="font-semibold text-gray-800">{{ $address->full_name }}</div>
                                        <div class="text-sm text-gray-600">{{ $address->full_address }}</div>
                                        <div class="text-sm text-gray-600">{{ $address->phone }}</div>
                                        @if($address->is_default)
                                            <span class="inline-block mt-1 bg-indigo-100 text-indigo-800 text-xs px-2 py-1 rounded">Default</span>
                                        @endif
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    @else
                        <p class="text-red-600 mb-4">You need to add a billing address first.</p>
                    @endif

                    <label class="flex items-center text-sm text-gray-600">
                        <input type="checkbox" id="same-as-shipping" class="mr-2">
                        Same as shipping address
                    </label>
                </div>

                <!-- Payment Method -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Payment Method</h2>
                    
                    <div class="space-y-3">
                        <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer hover:border-indigo-500 border-gray-200">
                            <input type="radio" 
                                   name="payment_method" 
                                   value="cash" 
                                   checked
                                   required
                                   class="mr-3">
                            <div>
                                <div class="font-semibold text-gray-800">Cash on Delivery</div>
                                <div class="text-sm text-gray-600">Pay when you receive your order</div>
                            </div>
                        </label>

                        <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer hover:border-indigo-500 border-gray-200">
                            <input type="radio" 
                                   name="payment_method" 
                                   value="card"
                                   class="mr-3">
                            <div>
                                <div class="font-semibold text-gray-800">Credit/Debit Card</div>
                                <div class="text-sm text-gray-600">Secure payment via Stripe</div>
                            </div>
                        </label>

                        <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer hover:border-indigo-500 border-gray-200">
                            <input type="radio" 
                                   name="payment_method" 
                                   value="paypal"
                                   class="mr-3">
                            <div>
                                <div class="font-semibold text-gray-800">PayPal</div>
                                <div class="text-sm text-gray-600">Pay securely with PayPal</div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Order Notes -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Order Notes (Optional)</h2>
                    <textarea name="notes" 
                              rows="4" 
                              placeholder="Special instructions for delivery..."
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg"></textarea>
                </div>

                <!-- Place Order Button -->
                <button type="submit" 
                        class="w-full bg-indigo-600 text-white py-4 rounded-lg text-lg font-semibold hover:bg-indigo-700">
                    Place Order
                </button>

            </form>
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Order Summary</h2>
                
                <!-- Cart Items -->
                <div class="space-y-4 mb-6 max-h-64 overflow-y-auto">
                    @foreach($cart_items as $item)
                        <div class="flex items-center space-x-3">
                            @if($item->product->image)
                                <img src="{{ asset('storage/' . $item->product->image) }}" 
                                     alt="{{ $item->product->name }}" 
                                     class="w-16 h-16 object-cover rounded">
                            @else
                                <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                    <i class="fas fa-image text-gray-400"></i>
                                </div>
                            @endif
                            
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-800 text-sm">{{ $item->product->name }}</h4>
                                <p class="text-xs text-gray-600">Qty: {{ $item->quantity }}</p>
                            </div>
                            
                            <div class="text-right">
                                <p class="font-semibold text-gray-800">${{ number_format($item->product->final_price * $item->quantity, 2) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Summary -->
                <div class="border-t pt-4 space-y-3">
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

                <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-600 mt-1 mr-2"></i>
                        <p class="text-sm text-gray-600">
                            Your personal data will be used to process your order and support your experience throughout this website.
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

<script>
    // Same as shipping checkbox
    document.getElementById('same-as-shipping')?.addEventListener('change', function() {
        const shippingRadio = document.querySelector('input[name="shipping_address_id"]:checked');
        if (this.checked && shippingRadio) {
            const billingRadios = document.querySelectorAll('input[name="billing_address_id"]');
            billingRadios.forEach(radio => {
                if (radio.value === shippingRadio.value) {
                    radio.checked = true;
                }
            });
        }
    });
</script>
@endsection

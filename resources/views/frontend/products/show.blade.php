@extends('layouts.app')

@section('title', $product->name . ' - E-Commerce Store')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
        
        <!-- Product Images -->
        <div>
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" 
                     alt="{{ $product->name }}" 
                     class="w-full h-96 object-cover rounded-lg shadow-lg">
            @else
                <div class="w-full h-96 bg-gray-200 rounded-lg flex items-center justify-center">
                    <i class="fas fa-image text-gray-400 text-9xl"></i>
                </div>
            @endif
            
            @if($product->images && count($product->images) > 0)
                <div class="grid grid-cols-4 gap-4 mt-4">
                    @foreach($product->images as $image)
                        <img src="{{ asset('storage/' . $image) }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-24 object-cover rounded cursor-pointer hover:opacity-75">
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Product Info -->
        <div>
            <nav class="text-sm mb-4">
                <a href="{{ route('home') }}" class="text-indigo-600 hover:text-indigo-800">Home</a>
                <span class="mx-2">/</span>
                <a href="{{ route('products.index', ['category' => $product->category->slug]) }}" 
                   class="text-indigo-600 hover:text-indigo-800">{{ $product->category->name }}</a>
                <span class="mx-2">/</span>
                <span class="text-gray-600">{{ $product->name }}</span>
            </nav>

            <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $product->name }}</h1>

            <!-- Rating -->
            <div class="flex items-center mb-4">
                <div class="flex text-yellow-400 text-lg">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star {{ $i <= $product->average_rating ? '' : 'text-gray-300' }}"></i>
                    @endfor
                </div>
                <span class="ml-2 text-gray-600">({{ $product->reviews->count() }} reviews)</span>
            </div>

            <!-- Price -->
            <div class="mb-6">
                @if($product->sale_price)
                    <div class="flex items-center">
                        <span class="text-4xl font-bold text-indigo-600">${{ number_format($product->sale_price, 2) }}</span>
                        <span class="text-2xl text-gray-500 line-through ml-4">${{ number_format($product->price, 2) }}</span>
                        <span class="ml-4 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-bold">
                            Save {{ $product->discount_percentage }}%
                        </span>
                    </div>
                @else
                    <span class="text-4xl font-bold text-indigo-600">${{ number_format($product->price, 2) }}</span>
                @endif
            </div>

            <!-- Stock Status -->
            <div class="mb-6">
                @if($product->quantity > 0)
                    <span class="text-green-600 font-semibold">
                        <i class="fas fa-check-circle"></i> In Stock ({{ $product->quantity }} available)
                    </span>
                @else
                    <span class="text-red-600 font-semibold">
                        <i class="fas fa-times-circle"></i> Out of Stock
                    </span>
                @endif
            </div>

            <!-- Short Description -->
            @if($product->short_description)
                <p class="text-gray-700 mb-6">{{ $product->short_description }}</p>
            @endif

            <!-- Add to Cart Form -->
            @if($product->quantity > 0)
                <form action="{{ route('cart.add') }}" method="POST" class="mb-6">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    
                    <div class="flex items-center space-x-4 mb-4">
                        <label class="font-semibold text-gray-700">Quantity:</label>
                        <input type="number" 
                               name="quantity" 
                               value="1" 
                               min="1" 
                               max="{{ $product->quantity }}"
                               class="w-20 px-3 py-2 border border-gray-300 rounded">
                    </div>

                    <div class="flex space-x-4">
                        <button type="submit" 
                                class="flex-1 bg-indigo-600 text-white px-8 py-3 rounded-lg hover:bg-indigo-700 font-semibold">
                            <i class="fas fa-shopping-cart mr-2"></i> Add to Cart
                        </button>
                        <button type="button" 
                                class="px-6 py-3 border-2 border-indigo-600 text-indigo-600 rounded-lg hover:bg-indigo-50">
                            <i class="fas fa-heart"></i>
                        </button>
                    </div>
                </form>
            @endif

            <!-- Product Details -->
            <div class="border-t pt-6">
                <h3 class="font-semibold text-gray-800 mb-3">Product Details</h3>
                <ul class="space-y-2 text-gray-600">
                    <li><span class="font-semibold">SKU:</span> {{ $product->sku }}</li>
                    <li><span class="font-semibold">Category:</span> {{ $product->category->name }}</li>
                    @if($product->weight)
                        <li><span class="font-semibold">Weight:</span> {{ $product->weight }} kg</li>
                    @endif
                    @if($product->dimensions)
                        <li><span class="font-semibold">Dimensions:</span> {{ $product->dimensions }}</li>
                    @endif
                </ul>
            </div>
        </div>
    </div>

    <!-- Description Tabs -->
    <div class="bg-white rounded-lg shadow-md p-8 mb-12">
        <div class="border-b mb-6">
            <button class="px-6 py-3 border-b-2 border-indigo-600 text-indigo-600 font-semibold">
                Description
            </button>
        </div>
        
        <div class="prose max-w-none">
            {!! nl2br(e($product->description)) !!}
        </div>
    </div>

    <!-- Reviews -->
    <div class="bg-white rounded-lg shadow-md p-8 mb-12">
        <h3 class="text-2xl font-bold text-gray-800 mb-6">Customer Reviews</h3>
        
        @if($product->reviews->where('is_approved', true)->count() > 0)
            <div class="space-y-6">
                @foreach($product->reviews->where('is_approved', true) as $review)
                    <div class="border-b pb-6">
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                <span class="font-semibold text-gray-800">{{ $review->user->name }}</span>
                                <div class="flex text-yellow-400 text-sm mt-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $review->rating ? '' : 'text-gray-300' }}"></i>
                                    @endfor
                                </div>
                            </div>
                            <span class="text-sm text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-gray-700">{{ $review->comment }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 text-center py-8">No reviews yet. Be the first to review this product!</p>
        @endif
    </div>

    <!-- Related Products -->
    @if($related_products->count() > 0)
        <div class="mb-12">
            <h3 class="text-2xl font-bold text-gray-800 mb-6">Related Products</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($related_products as $related)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                        <a href="{{ route('products.show', $related->slug) }}">
                            @if($related->image)
                                <img src="{{ asset('storage/' . $related->image) }}" 
                                     alt="{{ $related->name }}" 
                                     class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-image text-gray-400 text-5xl"></i>
                                </div>
                            @endif
                        </a>
                        
                        <div class="p-4">
                            <h4 class="font-semibold text-gray-800 mb-2">
                                <a href="{{ route('products.show', $related->slug) }}" class="hover:text-indigo-600">
                                    {{ $related->name }}
                                </a>
                            </h4>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-lg font-bold text-indigo-600">
                                    ${{ number_format($related->final_price, 2) }}
                                </span>
                                
                                <form action="{{ route('cart.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $related->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                                        <i class="fas fa-cart-plus"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection

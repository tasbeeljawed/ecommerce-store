@extends('layouts.app')

@section('title', 'Home - E-Commerce Store')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg shadow-xl p-12 mb-12 text-white">
        <div class="max-w-3xl">
            <h1 class="text-5xl font-bold mb-4">Welcome to Our Store</h1>
            <p class="text-xl mb-6">Discover amazing products at unbeatable prices</p>
            <a href="{{ route('products.index') }}" class="inline-block bg-white text-indigo-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                Shop Now <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>

    <!-- Categories -->
    <div class="mb-12">
        <h2 class="text-3xl font-bold text-gray-800 mb-6">Shop by Category</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach($categories as $category)
                <a href="{{ route('products.index', ['category' => $category->slug]) }}" 
                   class="bg-white rounded-lg shadow-md p-6 hover:shadow-xl transition text-center">
                    @if($category->image)
                        <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="w-16 h-16 mx-auto mb-3">
                    @else
                        <i class="fas fa-box text-4xl text-indigo-600 mb-3"></i>
                    @endif
                    <h3 class="font-semibold text-gray-800">{{ $category->name }}</h3>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Featured Products -->
    <div class="mb-12">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-gray-800">Featured Products</h2>
            <a href="{{ route('products.index') }}" class="text-indigo-600 hover:text-indigo-800">
                View All <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($featured_products as $product)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                    <a href="{{ route('products.show', $product->slug) }}">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <i class="fas fa-image text-gray-400 text-5xl"></i>
                            </div>
                        @endif
                    </a>
                    
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-800 mb-2">
                            <a href="{{ route('products.show', $product->slug) }}" class="hover:text-indigo-600">
                                {{ $product->name }}
                            </a>
                        </h3>
                        
                        <div class="flex items-center mb-2">
                            <div class="flex text-yellow-400">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $product->average_rating ? '' : 'text-gray-300' }}"></i>
                                @endfor
                            </div>
                            <span class="text-sm text-gray-600 ml-2">({{ $product->reviews->count() }})</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div>
                                @if($product->sale_price)
                                    <span class="text-xl font-bold text-indigo-600">${{ number_format($product->sale_price, 2) }}</span>
                                    <span class="text-sm text-gray-500 line-through ml-2">${{ number_format($product->price, 2) }}</span>
                                @else
                                    <span class="text-xl font-bold text-indigo-600">${{ number_format($product->price, 2) }}</span>
                                @endif
                            </div>
                            
                            <form action="{{ route('cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
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

    <!-- New Arrivals -->
    <div class="mb-12">
        <h2 class="text-3xl font-bold text-gray-800 mb-6">New Arrivals</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($new_arrivals as $product)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                    <div class="relative">
                        <a href="{{ route('products.show', $product->slug) }}">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-image text-gray-400 text-5xl"></i>
                                </div>
                            @endif
                        </a>
                        <span class="absolute top-2 right-2 bg-green-500 text-white px-2 py-1 rounded text-xs font-bold">NEW</span>
                    </div>
                    
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-800 mb-2">
                            <a href="{{ route('products.show', $product->slug) }}" class="hover:text-indigo-600">
                                {{ $product->name }}
                            </a>
                        </h3>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-xl font-bold text-indigo-600">${{ number_format($product->final_price, 2) }}</span>
                            
                            <form action="{{ route('cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
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

</div>
@endsection

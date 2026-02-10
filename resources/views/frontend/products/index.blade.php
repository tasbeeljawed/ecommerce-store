@extends('layouts.app')

@section('title', 'Products - E-Commerce Store')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">All Products</h1>
        <p class="text-gray-600 mt-2">Browse our extensive collection</p>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">
        
        <!-- Filters Sidebar -->
        <aside class="lg:w-64">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Filters</h3>
                
                <!-- Search -->
                <form method="GET" action="{{ route('products.index') }}" class="mb-6">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Search products..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    <button type="submit" class="w-full mt-2 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                        Search
                    </button>
                </form>

                <!-- Categories -->
                <div class="mb-6">
                    <h4 class="font-semibold text-gray-700 mb-3">Categories</h4>
                    <div class="space-y-2">
                        <a href="{{ route('products.index') }}" 
                           class="block text-gray-600 hover:text-indigo-600 {{ !request('category') ? 'text-indigo-600 font-semibold' : '' }}">
                            All Categories
                        </a>
                        @foreach($categories as $category)
                            <a href="{{ route('products.index', ['category' => $category->slug]) }}" 
                               class="block text-gray-600 hover:text-indigo-600 {{ request('category') === $category->slug ? 'text-indigo-600 font-semibold' : '' }}">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Price Range -->
                <div class="mb-6">
                    <h4 class="font-semibold text-gray-700 mb-3">Price Range</h4>
                    <form method="GET" action="{{ route('products.index') }}">
                        <input type="hidden" name="category" value="{{ request('category') }}">
                        <div class="space-y-2">
                            <input type="number" 
                                   name="min_price" 
                                   value="{{ request('min_price') }}" 
                                   placeholder="Min"
                                   class="w-full px-3 py-2 border border-gray-300 rounded">
                            <input type="number" 
                                   name="max_price" 
                                   value="{{ request('max_price') }}" 
                                   placeholder="Max"
                                   class="w-full px-3 py-2 border border-gray-300 rounded">
                            <button type="submit" class="w-full bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">
                                Apply
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Products Grid -->
        <div class="flex-1">
            
            <!-- Sort -->
            <div class="bg-white rounded-lg shadow-md p-4 mb-6">
                <div class="flex items-center justify-between">
                    <p class="text-gray-600">{{ $products->total() }} products found</p>
                    <form method="GET" action="{{ route('products.index') }}" class="flex items-center">
                        <input type="hidden" name="category" value="{{ request('category') }}">
                        <label class="mr-2 text-gray-600">Sort by:</label>
                        <select name="sort" 
                                onchange="this.form.submit()"
                                class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Latest</option>
                            <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Name</option>
                        </select>
                    </form>
                </div>
            </div>

            <!-- Products -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($products as $product)
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
                            <p class="text-xs text-gray-500 mb-1">{{ $product->category->name }}</p>
                            <h3 class="font-semibold text-gray-800 mb-2">
                                <a href="{{ route('products.show', $product->slug) }}" class="hover:text-indigo-600">
                                    {{ $product->name }}
                                </a>
                            </h3>
                            
                            <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $product->short_description }}</p>
                            
                            <div class="flex items-center mb-3">
                                <div class="flex text-yellow-400 text-sm">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $product->average_rating ? '' : 'text-gray-300' }}"></i>
                                    @endfor
                                </div>
                                <span class="text-xs text-gray-600 ml-2">({{ $product->reviews->count() }})</span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div>
                                    @if($product->sale_price)
                                        <span class="text-lg font-bold text-indigo-600">${{ number_format($product->sale_price, 2) }}</span>
                                        <span class="text-sm text-gray-500 line-through ml-2">${{ number_format($product->price, 2) }}</span>
                                    @else
                                        <span class="text-lg font-bold text-indigo-600">${{ number_format($product->price, 2) }}</span>
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
                @empty
                    <div class="col-span-3 text-center py-12">
                        <i class="fas fa-box-open text-6xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500 text-lg">No products found</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $products->links() }}
            </div>
        </div>
    </div>

</div>
@endsection

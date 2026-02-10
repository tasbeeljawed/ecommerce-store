@extends('layouts.admin')

@section('header', 'Products Management')

@section('content')

<div class="mb-6 flex justify-between items-center">
    <div>
        <form method="GET" action="{{ route('admin.products.index') }}" class="flex space-x-2">
            <input type="text" 
                   name="search" 
                   value="{{ request('search') }}" 
                   placeholder="Search products..."
                   class="px-4 py-2 border border-gray-300 rounded-lg">
            <select name="category_id" class="px-4 py-2 border border-gray-300 rounded-lg">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700">
                Filter
            </button>
        </form>
    </div>
    <a href="{{ route('admin.products.create') }}" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">
        <i class="fas fa-plus mr-2"></i> Add Product
    </a>
</div>

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <table class="min-w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Image</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">SKU</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($products as $product)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-12 h-12 rounded object-cover">
                        @else
                            <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
                                <i class="fas fa-image text-gray-400"></i>
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-800">{{ $product->name }}</div>
                        @if($product->is_featured)
                            <span class="inline-block bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded">Featured</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $product->sku }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $product->category->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($product->sale_price)
                            <div class="text-green-600 font-semibold">${{ number_format($product->sale_price, 2) }}</div>
                            <div class="text-sm text-gray-500 line-through">${{ number_format($product->price, 2) }}</div>
                        @else
                            <div class="font-semibold">${{ number_format($product->price, 2) }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="{{ $product->quantity < 10 ? 'text-red-600' : 'text-gray-800' }}">
                            {{ $product->quantity }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.products.edit', $product->id) }}" 
                               class="text-indigo-600 hover:text-indigo-900">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product->id) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this product?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                        No products found
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $products->links() }}
</div>

@endsection

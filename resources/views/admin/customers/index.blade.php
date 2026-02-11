@extends('layouts.admin')

@section('header', 'Customers Management')

@section('content')

<div class="mb-6 flex justify-between items-center">
    <form method="GET" action="{{ route('admin.customers.index') }}" class="flex space-x-2">
        <input type="text" 
               name="search" 
               value="{{ request('search') }}" 
               placeholder="Search customers..."
               class="px-4 py-2 border border-gray-300 rounded-lg">
        <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg">
            <option value="">All Statuses</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        <button type="submit" class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700">
            Filter
        </button>
    </form>
</div>

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <table class="min-w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Orders</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Joined</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($customers as $customer)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-indigo-600 rounded-full flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr($customer->name, 0, 1)) }}
                            </div>
                            <div class="ml-4">
                                <a href="{{ route('admin.customers.show', $customer->id) }}" 
                                   class="font-medium text-gray-800 hover:text-indigo-600">
                                    {{ $customer->name }}
                                </a>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                        {{ $customer->email }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                        {{ $customer->phone ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="font-semibold">{{ $customer->orders_count }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs rounded-full {{ $customer->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $customer->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $customer->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.customers.show', $customer->id) }}" 
                               class="text-indigo-600 hover:text-indigo-900">
                                <i class="fas fa-eye"></i>
                            </a>
                            <form action="{{ route('admin.customers.toggle-status', $customer->id) }}" 
                                  method="POST" 
                                  class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="text-gray-600 hover:text-gray-900"
                                        title="{{ $customer->is_active ? 'Deactivate' : 'Activate' }}">
                                    <i class="fas fa-{{ $customer->is_active ? 'ban' : 'check' }}"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                        No customers found
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $customers->links() }}
</div>

@endsection
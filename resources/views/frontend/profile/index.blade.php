@extends('layouts.app')

@section('title', 'My Profile - E-Commerce Store')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <h1 class="text-3xl font-bold text-gray-800 mb-8">My Profile</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Profile Information -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Personal Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6">Personal Information</h2>
                
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                            <input type="text" 
                                   name="name" 
                                   value="{{ old('name', $user->name) }}"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                            <input type="email" 
                                   name="email" 
                                   value="{{ old('email', $user->email) }}"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                            <input type="text" 
                                   name="phone" 
                                   value="{{ old('phone', $user->phone) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            @error('phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>

            <!-- Change Password -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6">Change Password</h2>
                
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <input type="hidden" name="name" value="{{ $user->name }}">
                    <input type="hidden" name="email" value="{{ $user->email }}">
                    <input type="hidden" name="phone" value="{{ $user->phone }}">

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                            <input type="password" 
                                   name="current_password" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            @error('current_password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                            <input type="password" 
                                   name="new_password" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            @error('new_password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                            <input type="password" 
                                   name="new_password_confirmation" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">
                            Change Password
                        </button>
                    </div>
                </form>
            </div>

            <!-- Saved Addresses -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-gray-800">Saved Addresses</h2>
                    <a href="{{ route('profile.address.create') }}" 
                       class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                        <i class="fas fa-plus mr-2"></i> Add Address
                    </a>
                </div>

                @if($addresses->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($addresses as $address)
                            <div class="border-2 rounded-lg p-4 {{ $address->is_default ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200' }}">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="px-2 py-1 text-xs rounded {{ $address->type === 'shipping' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                        {{ ucfirst($address->type) }}
                                    </span>
                                    @if($address->is_default)
                                        <span class="px-2 py-1 text-xs rounded bg-indigo-100 text-indigo-800">Default</span>
                                    @endif
                                </div>
                                <p class="font-semibold text-gray-800">{{ $address->full_name }}</p>
                                <p class="text-sm text-gray-600 mt-1">{{ $address->full_address }}</p>
                                <p class="text-sm text-gray-600">{{ $address->phone }}</p>
                                
                                <div class="flex space-x-3 mt-4">
                                    <a href="{{ route('profile.address.edit', $address->id) }}" 
                                       class="text-sm text-indigo-600 hover:text-indigo-800">
                                        <i class="fas fa-edit mr-1"></i> Edit
                                    </a>
                                    <form action="{{ route('profile.address.delete', $address->id) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash mr-1"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">No saved addresses yet.</p>
                @endif
            </div>

        </div>

        <!-- Sidebar -->
        <div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="text-center mb-6">
                    <div class="w-24 h-24 bg-indigo-600 rounded-full flex items-center justify-center text-white text-4xl font-bold mx-auto">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mt-4">{{ $user->name }}</h3>
                    <p class="text-gray-600">{{ $user->email }}</p>
                </div>

                <div class="space-y-3">
                    <a href="{{ route('orders.index') }}" 
                       class="block w-full text-center bg-gray-100 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-200">
                        <i class="fas fa-shopping-bag mr-2"></i> My Orders
                    </a>
                    <a href="{{ route('profile.index') }}" 
                       class="block w-full text-center bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                        <i class="fas fa-user mr-2"></i> My Profile
                    </a>
                </div>

                <div class="mt-6 pt-6 border-t">
                    <p class="text-sm text-gray-600">Member since</p>
                    <p class="font-semibold text-gray-800">{{ $user->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
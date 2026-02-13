@extends('layouts.app')

@section('title', isset($address) ? 'Edit Address' : 'Add Address' . ' - E-Commerce Store')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <div class="mb-6">
        <a href="{{ route('profile.index') }}" class="text-indigo-600 hover:text-indigo-800">
            <i class="fas fa-arrow-left mr-2"></i> Back to Profile
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">{{ isset($address) ? 'Edit' : 'Add' }} Address</h1>

        <form action="{{ isset($address) ? route('profile.address.update', $address->id) : route('profile.address.store') }}" 
              method="POST">
            @csrf
            @if(isset($address))
                @method('PATCH')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Address Type *</label>
                    <select name="type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="shipping" {{ (isset($address) && $address->type === 'shipping') || old('type') === 'shipping' ? 'selected' : '' }}>
                            Shipping Address
                        </option>
                        <option value="billing" {{ (isset($address) && $address->type === 'billing') || old('type') === 'billing' ? 'selected' : '' }}>
                            Billing Address
                        </option>
                    </select>
                    @error('type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                    <input type="text" 
                           name="first_name" 
                           value="{{ old('first_name', $address->first_name ?? '') }}"
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    @error('first_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                    <input type="text" 
                           name="last_name" 
                           value="{{ old('last_name', $address->last_name ?? '') }}"
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    @error('last_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone *</label>
                    <input type="text" 
                           name="phone" 
                           value="{{ old('phone', $address->phone ?? '') }}"
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    @error('phone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" 
                           name="email" 
                           value="{{ old('email', $address->email ?? '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Address Line 1 *</label>
                    <input type="text" 
                           name="address_line_1" 
                           value="{{ old('address_line_1', $address->address_line_1 ?? '') }}"
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    @error('address_line_1')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Address Line 2</label>
                    <input type="text" 
                           name="address_line_2" 
                           value="{{ old('address_line_2', $address->address_line_2 ?? '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    @error('address_line_2')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">City *</label>
                    <input type="text" 
                           name="city" 
                           value="{{ old('city', $address->city ?? '') }}"
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    @error('city')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">State *</label>
                    <input type="text" 
                           name="state" 
                           value="{{ old('state', $address->state ?? '') }}"
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    @error('state')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Postal Code *</label>
                    <input type="text" 
                           name="postal_code" 
                           value="{{ old('postal_code', $address->postal_code ?? '') }}"
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    @error('postal_code')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Country *</label>
                    <select name="country" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="US" {{ (isset($address) && $address->country === 'US') || old('country') === 'US' ? 'selected' : '' }}>United States</option>
                        <option value="CA" {{ (isset($address) && $address->country === 'CA') || old('country') === 'CA' ? 'selected' : '' }}>Canada</option>
                        <option value="UK" {{ (isset($address) && $address->country === 'UK') || old('country') === 'UK' ? 'selected' : '' }}>United Kingdom</option>
                        <option value="PK" {{ (isset($address) && $address->country === 'PK') || old('country') === 'PK' ? 'selected' : '' }}>Pakistan</option>
                    </select>
                    @error('country')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="is_default" 
                               value="1"
                               {{ (isset($address) && $address->is_default) || old('is_default') ? 'checked' : '' }}
                               class="mr-2">
                        <span class="text-sm text-gray-700">Set as default address</span>
                    </label>
                </div>

            </div>

            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('profile.index') }}" 
                   class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    {{ isset($address) ? 'Update' : 'Save' }} Address
                </button>
            </div>

        </form>
    </div>

</div>
@endsection
<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $addresses = $user->addresses()->whereNull('order_id')->get();
        
        return view('frontend.profile.index', compact('user', 'addresses'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Check current password if trying to change password
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect']);
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'];
        $user->save();

        return back()->with('success', 'Profile updated successfully!');
    }

    public function createAddress()
    {
        return view('frontend.profile.address-create');
    }

    public function storeAddress(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:billing,shipping',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'required|string|max:20',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:2',
            'is_default' => 'boolean',
        ]);

        // If this is set as default, remove default from other addresses of same type
        if ($request->is_default) {
            Address::where('user_id', Auth::id())
                ->where('type', $validated['type'])
                ->whereNull('order_id')
                ->update(['is_default' => false]);
        }

        $validated['user_id'] = Auth::id();
        Address::create($validated);

        return redirect()->route('profile.index')->with('success', 'Address added successfully!');
    }

    public function editAddress(Address $address)
    {
        // Ensure user owns this address
        if ($address->user_id !== Auth::id() || $address->order_id !== null) {
            abort(403);
        }

        return view('frontend.profile.address-edit', compact('address'));
    }

    public function updateAddress(Request $request, Address $address)
    {
        // Ensure user owns this address
        if ($address->user_id !== Auth::id() || $address->order_id !== null) {
            abort(403);
        }

        $validated = $request->validate([
            'type' => 'required|in:billing,shipping',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'required|string|max:20',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:2',
            'is_default' => 'boolean',
        ]);

        // If this is set as default, remove default from other addresses of same type
        if ($request->is_default) {
            Address::where('user_id', Auth::id())
                ->where('type', $validated['type'])
                ->where('id', '!=', $address->id)
                ->whereNull('order_id')
                ->update(['is_default' => false]);
        }

        $address->update($validated);

        return redirect()->route('profile.index')->with('success', 'Address updated successfully!');
    }

    public function deleteAddress(Address $address)
    {
        // Ensure user owns this address
        if ($address->user_id !== Auth::id() || $address->order_id !== null) {
            abort(403);
        }

        $address->delete();

        return back()->with('success', 'Address deleted successfully!');
    }
}

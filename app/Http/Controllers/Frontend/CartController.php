<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart_items = $this->getCartItems();
        
        $subtotal = $cart_items->sum(function($item) {
            return $item->product->final_price * $item->quantity;
        });

        $tax = $subtotal * 0.1; // 10% tax
        $shipping = 10; // Flat shipping
        $total = $subtotal + $tax + $shipping;

        return view('frontend.cart.index', compact('cart_items', 'subtotal', 'tax', 'shipping', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->quantity < $request->quantity) {
            return back()->with('error', 'Insufficient stock available.');
        }

        $cart_data = [
            'product_id' => $product->id,
            'quantity' => $request->quantity,
        ];

        if (Auth::check()) {
            $cart_data['user_id'] = Auth::id();
            
            $cart_item = Cart::where('user_id', Auth::id())
                ->where('product_id', $product->id)
                ->first();

            if ($cart_item) {
                $cart_item->increment('quantity', $request->quantity);
            } else {
                Cart::create($cart_data);
            }
        } else {
            $cart_data['session_id'] = session()->getId();
            
            $cart_item = Cart::where('session_id', session()->getId())
                ->where('product_id', $product->id)
                ->first();

            if ($cart_item) {
                $cart_item->increment('quantity', $request->quantity);
            } else {
                Cart::create($cart_data);
            }
        }

        return redirect()->route('cart.index')->with('success', 'Product added to cart.');
    }

    public function update(Request $request, Cart $cart)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        if ($cart->product->quantity < $request->quantity) {
            return back()->with('error', 'Insufficient stock available.');
        }

        $cart->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Cart updated successfully.');
    }

    public function remove(Cart $cart)
    {
        $cart->delete();

        return back()->with('success', 'Item removed from cart.');
    }

    private function getCartItems()
    {
        if (Auth::check()) {
            return Cart::with('product')->where('user_id', Auth::id())->get();
        } else {
            return Cart::with('product')->where('session_id', session()->getId())->get();
        }
    }
}

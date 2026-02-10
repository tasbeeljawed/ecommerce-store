<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to checkout.');
        }

        $cart_items = Cart::with('product')->where('user_id', Auth::id())->get();

        if ($cart_items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $addresses = Auth::user()->addresses;
        
        $subtotal = $cart_items->sum(function($item) {
            return $item->product->final_price * $item->quantity;
        });

        $tax = $subtotal * 0.1;
        $shipping = 10;
        $total = $subtotal + $tax + $shipping;

        return view('frontend.checkout.index', compact('cart_items', 'addresses', 'subtotal', 'tax', 'shipping', 'total'));
    }

    public function process(Request $request)
    {
        $validated = $request->validate([
            'payment_method' => 'required|in:cash,card,paypal',
            'billing_address_id' => 'required|exists:addresses,id',
            'shipping_address_id' => 'required|exists:addresses,id',
            'notes' => 'nullable|string',
        ]);

        $cart_items = Cart::with('product')->where('user_id', Auth::id())->get();

        if ($cart_items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        DB::beginTransaction();

        try {
            $subtotal = $cart_items->sum(function($item) {
                return $item->product->final_price * $item->quantity;
            });

            $tax = $subtotal * 0.1;
            $shipping = 10;
            $total = $subtotal + $tax + $shipping;

            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping' => $shipping,
                'total' => $total,
                'payment_method' => $validated['payment_method'],
                'notes' => $validated['notes'],
            ]);

            // Create order items
            foreach ($cart_items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'product_sku' => $item->product->sku,
                    'price' => $item->product->final_price,
                    'quantity' => $item->quantity,
                    'total' => $item->product->final_price * $item->quantity,
                ]);

                // Update product quantity
                $item->product->decrement('quantity', $item->quantity);
            }

            // Copy addresses to order
            $billing_address = Address::find($validated['billing_address_id']);
            $shipping_address = Address::find($validated['shipping_address_id']);

            Address::create(array_merge(
                $billing_address->toArray(),
                ['order_id' => $order->id, 'type' => 'billing']
            ));

            Address::create(array_merge(
                $shipping_address->toArray(),
                ['order_id' => $order->id, 'type' => 'shipping']
            ));

            // Clear cart
            Cart::where('user_id', Auth::id())->delete();

            DB::commit();

            return redirect()->route('order.success', $order->id)
                ->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred while processing your order.');
        }
    }

    public function success($orderId)
    {
        $order = Order::with('items')->where('user_id', Auth::id())->findOrFail($orderId);
        
        return view('frontend.checkout.success', compact('order'));
    }
}

<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Auth::user()
            ->orders()
            ->with('items')
            ->latest()
            ->paginate(10);

        return view('frontend.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Auth::user()
            ->orders()
            ->with(['items.product', 'shippingAddress', 'billingAddress'])
            ->findOrFail($id);

        return view('frontend.orders.show', compact('order'));
    }

    public function cancel($id)
    {
        $order = Auth::user()->orders()->findOrFail($id);

        // Only allow cancellation for pending or processing orders
        if (!in_array($order->status, ['pending', 'processing'])) {
            return back()->with('error', 'This order cannot be cancelled.');
        }

        $order->update(['status' => 'cancelled']);

        // Restore product quantities
        foreach ($order->items as $item) {
            if ($item->product) {
                $item->product->increment('quantity', $item->quantity);
            }
        }

        return back()->with('success', 'Order cancelled successfully!');
    }
}
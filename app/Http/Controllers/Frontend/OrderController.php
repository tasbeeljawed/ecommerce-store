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
}
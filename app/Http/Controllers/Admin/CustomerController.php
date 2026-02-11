<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'customer');

        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('status')) {
            $is_active = $request->status === 'active' ? 1 : 0;
            $query->where('is_active', $is_active);
        }

        $customers = $query->withCount('orders')
            ->latest()
            ->paginate(20);

        return view('admin.customers.index', compact('customers'));
    }

    public function show(User $customer)
    {
        if ($customer->role !== 'customer') {
            abort(404);
        }

        $customer->load(['orders' => function($query) {
            $query->latest()->limit(10);
        }, 'addresses']);

        $stats = [
            'total_orders' => $customer->orders()->count(),
            'total_spent' => $customer->orders()->where('payment_status', 'paid')->sum('total'),
            'pending_orders' => $customer->orders()->where('status', 'pending')->count(),
            'completed_orders' => $customer->orders()->whereIn('status', ['delivered'])->count(),
        ];

        return view('admin.customers.show', compact('customer', 'stats'));
    }

    public function toggleStatus(User $customer)
    {
        if ($customer->role !== 'customer') {
            abort(404);
        }

        $customer->update([
            'is_active' => !$customer->is_active
        ]);

        return redirect()->back()->with('success', 'Customer status updated successfully.');
    }
}
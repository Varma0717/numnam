<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;

class CustomerManagementController extends Controller
{
    public function index(Request $request)
    {
        $customers = User::query()
            ->where('is_admin', false)
            ->withCount('orders')
            ->when($request->q, fn($q) => $q->where('name', 'like', "%{$request->q}%")
                ->orWhere('email', 'like', "%{$request->q}%"))
            ->latest('id')
            ->paginate(25)
            ->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }

    public function show(User $customer)
    {
        $customer->loadCount('orders', 'reviews');
        $orders = $customer->orders()->latest()->limit(20)->get();
        $totalSpent = $customer->orders()->where('payment_status', 'paid')->sum('total');

        return view('admin.customers.show', compact('customer', 'orders', 'totalSpent'));
    }
}

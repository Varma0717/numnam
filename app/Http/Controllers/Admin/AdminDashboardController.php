<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Contact;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\RewardLedger;
use App\Models\Subscription;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $metrics = [
            'products' => Product::query()->count(),
            'orders' => Order::query()->count(),
            'subscriptions' => Subscription::query()->count(),
            'contacts' => Contact::query()->count(),
            'blogs' => Blog::query()->count(),
            'coupons' => class_exists(Coupon::class) ? Coupon::query()->count() : 0,
            'reward_credits' => class_exists(RewardLedger::class)
                ? (float) RewardLedger::query()->where('type', 'credit')->sum('amount')
                : 0,
        ];

        $recentOrders = Order::query()->latest('id')->take(8)->get();

        return view('admin.dashboard', compact('metrics', 'recentOrders'));
    }
}

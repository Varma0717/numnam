<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function sales(Request $request)
    {
        $from = $request->get('from', now()->subDays(30)->format('Y-m-d'));
        $to   = $request->get('to', now()->format('Y-m-d'));

        $orders = Order::whereBetween('created_at', [$from, $to . ' 23:59:59'])
            ->whereIn('status', ['processing', 'shipped', 'delivered']);

        $totalRevenue   = (clone $orders)->sum('total');
        $totalOrders    = (clone $orders)->count();
        $avgOrderValue  = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
        $totalTax       = (clone $orders)->sum('tax_amount');

        // Daily breakdown
        $dailySales = (clone $orders)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as revenue'), DB::raw('COUNT(*) as orders'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top products
        $topProducts = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->whereBetween('orders.created_at', [$from, $to . ' 23:59:59'])
            ->whereIn('orders.status', ['processing', 'shipped', 'delivered'])
            ->select('products.name', DB::raw('SUM(order_items.quantity) as qty'), DB::raw('SUM(order_items.subtotal) as revenue'))
            ->groupBy('products.name')
            ->orderByDesc('revenue')
            ->limit(10)
            ->get();

        return view('admin.reports.sales', compact(
            'from',
            'to',
            'totalRevenue',
            'totalOrders',
            'avgOrderValue',
            'totalTax',
            'dailySales',
            'topProducts'
        ));
    }

    public function customers(Request $request)
    {
        $from = $request->get('from', now()->subDays(30)->format('Y-m-d'));
        $to   = $request->get('to', now()->format('Y-m-d'));

        $newCustomers = User::where('role', 'customer')
            ->whereBetween('created_at', [$from, $to . ' 23:59:59'])
            ->count();

        $totalCustomers = User::where('role', 'customer')->count();

        // Top spenders
        $topSpenders = DB::table('orders')
            ->join('users', 'users.id', '=', 'orders.user_id')
            ->whereBetween('orders.created_at', [$from, $to . ' 23:59:59'])
            ->whereIn('orders.status', ['processing', 'shipped', 'delivered'])
            ->select('users.name', 'users.email', DB::raw('COUNT(*) as orders'), DB::raw('SUM(orders.total) as spent'))
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByDesc('spent')
            ->limit(10)
            ->get();

        return view('admin.reports.customers', compact(
            'from',
            'to',
            'newCustomers',
            'totalCustomers',
            'topSpenders'
        ));
    }

    public function stock()
    {
        $lowStock = Product::where('is_active', true)
            ->where('stock', '<=', 10)
            ->orderBy('stock')
            ->get();

        $outOfStock = Product::where('is_active', true)
            ->where('stock', 0)
            ->count();

        $totalProducts = Product::where('is_active', true)->count();

        return view('admin.reports.stock', compact('lowStock', 'outOfStock', 'totalProducts'));
    }
}

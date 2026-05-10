<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\ContactMessage;
use App\Models\Page;
use App\Models\PricingPlan;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'metrics' => [
                    'pages' => Page::count(),
                    'products' => Product::count(),
                    'pricing_plans' => PricingPlan::count(),
                    'blogs' => Blog::count(),
                    'unread_messages' => ContactMessage::where('status', 'new')->count(),
                ],
                'latest' => [
                    'pages' => Page::latest()->limit(5)->get(['id', 'title', 'slug', 'status', 'updated_at']),
                    'products' => Product::latest()->limit(5)->get(['id', 'name', 'slug', 'price', 'is_active', 'updated_at']),
                    'blogs' => Blog::latest()->limit(5)->get(['id', 'title', 'slug', 'status', 'updated_at']),
                    'messages' => ContactMessage::latest()->limit(5)->get(['id', 'name', 'email', 'status', 'created_at']),
                ],
            ],
        ]);
    }
}

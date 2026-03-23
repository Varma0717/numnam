<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Subscription;
use App\Models\User;
use App\Models\SiteSetting;

class AdminPagesController extends Controller
{
    public function customers()
    {
        $customers = User::query()
            ->where('role', '!=', 'admin')
            ->latest('id')
            ->paginate(25);

        return view('admin.customers.index', compact('customers'));
    }

    public function categories()
    {
        $categories = Category::query()->latest('id')->paginate(25);
        return view('admin.categories.index', compact('categories'));
    }

    public function blogs()
    {
        $blogs = Blog::query()->latest('id')->paginate(25);
        return view('admin.blogs.index', compact('blogs'));
    }

    public function contacts()
    {
        $contacts = Contact::query()->latest('id')->paginate(25);
        return view('admin.contacts.index', compact('contacts'));
    }

    public function subscriptions()
    {
        $subscriptions = Subscription::query()->with('user')->latest('id')->paginate(25);
        return view('admin.subscriptions.index', compact('subscriptions'));
    }

    public function settings()
    {
        $settings = SiteSetting::all()->keyBy('key');
        return view('admin.settings.index', compact('settings'));
    }
}

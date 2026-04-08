<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Order;
use App\Models\Page;
use App\Models\PaymentEvent;
use App\Models\PricingPlan;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\RewardLedger;
use App\Models\SiteSetting;
use App\Models\Subscription;
use App\Models\Wishlist;
use App\Services\Commerce\DiscountService;
use App\Services\Commerce\PaymentGatewayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class StorefrontController extends Controller
{
    private $discountService;
    private $paymentGatewayService;

    public function __construct(DiscountService $discountService, PaymentGatewayService $paymentGatewayService)
    {
        $this->discountService = $discountService;
        $this->paymentGatewayService = $paymentGatewayService;
    }

    public function home()
    {
        $productCardQuery = fn() => Product::query()
            ->with('category')
            ->withCount('approvedReviews')
            ->withAvg('approvedReviews', 'rating')
            ->where('is_active', true);

        $recentlyViewedProducts = $this->loadRecentlyViewedProducts(request());

        $featuredProducts = Product::query()
            ->with('category')
            ->withCount('approvedReviews')
            ->withAvg('approvedReviews', 'rating')
            ->where('is_active', true)
            ->where('is_featured', true)
            ->latest('id')
            ->take(4)
            ->get();

        $bestSellerProducts = $productCardQuery()
            ->inRandomOrder()
            ->take(8)
            ->get();

        $plans = PricingPlan::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->take(3)
            ->get();

        $latestBlogs = Blog::query()
            ->where('status', 'published')
            ->latest('published_at')
            ->take(3)
            ->get();

        $topCategories = Category::query()
            ->where('is_active', true)
            ->where('slug', '!=', 'all-products')
            ->withCount(['products' => fn($query) => $query->where('is_active', true)])
            ->having('products_count', '>', 0)
            ->orderByDesc('products_count')
            ->take(4)
            ->get();

        $homepageSections = SiteSetting::query()
            ->where('group', 'homepage')
            ->where('is_public', true)
            ->pluck('value', 'key');

        $trustHighlights = [
            ['title' => 'Doctor-backed recipes', 'text' => 'Developed with pediatric nutrition intent.'],
            ['title' => 'No refined sugar', 'text' => 'Balanced taste without unnecessary sweeteners.'],
            ['title' => 'Stage-wise textures', 'text' => 'Feeding progression tuned for each age group.'],
            ['title' => 'Subscription friendly', 'text' => 'Predictable recurring delivery for parents.'],
        ];

        $testimonials = [
            ['name' => 'Aarohi S.', 'quote' => 'Our baby transitioned beautifully from purees to puffs.'],
            ['name' => 'Raghav P.', 'quote' => 'Subscription and reorder flow saved us so much time.'],
            ['name' => 'Meera K.', 'quote' => 'Ingredients and nutrition information are very transparent.'],
        ];

        return view('store.home', compact('featuredProducts', 'bestSellerProducts', 'recentlyViewedProducts', 'plans', 'latestBlogs', 'topCategories', 'homepageSections', 'trustHighlights', 'testimonials'));
    }

    public function products(Request $request)
    {
        $categories = Category::query()->where('is_active', true)->orderBy('name')->get();

        $products = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->when($request->filled('category'), function ($query) use ($request) {
                $query->whereHas('category', function ($categoryQuery) use ($request) {
                    $categoryQuery->where('slug', $request->string('category'));
                });
            })
            ->when($request->filled('type'), fn($query) => $query->where('type', $request->string('type')))
            ->when($request->filled('q'), fn($query) => $query->where('name', 'like', '%' . $request->string('q') . '%'))
            ->when($request->filled('age'), fn($query) => $query->where('age_group', 'like', '%' . $request->string('age') . '%'))
            ->when($request->filled('sort'), function ($query) use ($request) {
                return match ($request->string('sort')->toString()) {
                    'price_low'  => $query->orderBy('price'),
                    'price_high' => $query->orderByDesc('price'),
                    'name_az'    => $query->orderBy('name'),
                    default      => $query->latest('id'),
                };
            }, fn($query) => $query->latest('id'))
            ->paginate(12)
            ->appends($request->query());

        return view('store.products.index', compact('products', 'categories'));
    }

    public function searchSuggestions(Request $request): JsonResponse
    {
        $query = trim((string) $request->query('q', ''));
        if ($query === '') {
            return response()->json(['items' => []]);
        }

        $products = Product::query()
            ->withCount('approvedReviews')
            ->withAvg('approvedReviews', 'rating')
            ->where('is_active', true)
            ->where(function ($builder) use ($query) {
                $builder->where('name', 'like', '%' . $query . '%')
                    ->orWhere('short_description', 'like', '%' . $query . '%');
            })
            ->latest('id')
            ->take(6)
            ->get();

        $items = $products->map(function (Product $product) {
            return [
                'name' => $product->name,
                'url' => route('store.product.show', $product),
                'price' => (int) ($product->sale_price ?: $product->price),
                'rating' => number_format((float) ($product->approved_reviews_avg_rating ?? 4.8), 1),
                'reviewCount' => (int) ($product->approved_reviews_count ?? 0),
            ];
        })->values();

        return response()->json(['items' => $items]);
    }

    public function product(Request $request, Product $product)
    {
        abort_unless($product->is_active, 404);

        $this->storeRecentlyViewedProduct($request, $product->id);

        $related = Product::query()
            ->withCount('approvedReviews')
            ->withAvg('approvedReviews', 'rating')
            ->where('is_active', true)
            ->where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->latest('id')
            ->take(4)
            ->get();

        $gallery = collect($product->gallery ?: [])->filter()->values();

        // If no gallery in DB, scan the product image directory on disk
        if ($gallery->isEmpty()) {
            $productName = $product->name;

            // Try Puffs sub-folder (folder name = product name with spaces)
            $puffsDir = public_path('assets/images/Puffs/' . $productName);
            if (is_dir($puffsDir)) {
                $files = glob($puffsDir . '/*.{jpg,jpeg,png,webp}', GLOB_BRACE) ?: [];
                usort($files, function ($a, $b) {
                    $an = strtolower(pathinfo($a, PATHINFO_FILENAME));
                    $bn = strtolower(pathinfo($b, PATHINFO_FILENAME));
                    if ($an === 'front') return -1;
                    if ($bn === 'front') return 1;
                    if ($an === 'back') return -1;
                    if ($bn === 'back') return 1;
                    return strnatcmp($an, $bn);
                });
                $encodedName = implode('%20', explode(' ', $productName));
                $gallery = collect($files)->map(fn($f) => asset('assets/images/Puffs/' . $encodedName . '/' . rawurlencode(basename($f))));
            }

            // Try Purees directory — match by first word of product name
            if ($gallery->isEmpty()) {
                $keyword = strtolower(explode(' ', $productName)[0]);
                $pureeGlob = glob(public_path('assets/images/Purees') . '/*.{jpg,jpeg,png,webp}', GLOB_BRACE) ?: [];
                $matched = array_filter($pureeGlob, fn($f) => str_starts_with(strtolower(basename($f)), $keyword));
                if (!empty($matched)) {
                    sort($matched);
                    $gallery = collect($matched)->map(fn($f) => asset('assets/images/Purees/' . rawurlencode(basename($f))));
                }
            }
        }

        $recentlyViewedProducts = $this->loadRecentlyViewedProducts($request, $product->id, 4);

        return view('store.products.show', compact('product', 'related', 'gallery', 'recentlyViewedProducts'));
    }

    public function category(Category $category)
    {
        abort_unless($category->is_active, 404);

        $products = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->where('category_id', $category->id)
            ->latest('id')
            ->paginate(12);

        $relatedCategories = Category::query()
            ->where('is_active', true)
            ->where('id', '!=', $category->id)
            ->where('slug', '!=', 'all-products')
            ->withCount(['products' => fn($query) => $query->where('is_active', true)])
            ->having('products_count', '>', 0)
            ->orderByDesc('products_count')
            ->take(3)
            ->get();

        return view('store.categories.show', compact('category', 'products', 'relatedCategories'));
    }

    private function storeRecentlyViewedProduct(Request $request, int $productId, int $limit = 6): void
    {
        $recentIds = collect($request->session()->get('recently_viewed_products', []))
            ->map(fn($id) => (int) $id)
            ->reject(fn($id) => $id === $productId)
            ->prepend($productId)
            ->take($limit)
            ->values()
            ->all();

        $request->session()->put('recently_viewed_products', $recentIds);
    }

    private function loadRecentlyViewedProducts(Request $request, ?int $excludeProductId = null, int $limit = 6): Collection
    {
        $recentIds = collect($request->session()->get('recently_viewed_products', []))
            ->map(fn($id) => (int) $id)
            ->when($excludeProductId, fn($ids) => $ids->reject(fn($id) => $id === $excludeProductId))
            ->take($limit)
            ->values();

        if ($recentIds->isEmpty()) {
            return collect();
        }

        $products = Product::query()
            ->with('category')
            ->withCount('approvedReviews')
            ->withAvg('approvedReviews', 'rating')
            ->where('is_active', true)
            ->whereIn('id', $recentIds)
            ->get()
            ->keyBy('id');

        return $recentIds
            ->map(fn($id) => $products->get($id))
            ->filter()
            ->values();
    }

    public function pricing()
    {
        $plans = PricingPlan::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('store.pricing.index', compact('plans'));
    }

    public function about()
    {
        $founders = [
            [
                'name' => 'Dr. Srinath Reddy',
                'role' => 'Pediatric Advisor',
                'bio' => 'Clinical pediatric guidance shaping age-specific nutrition at NumNam.',
            ],
            [
                'name' => 'Smiti Reddy',
                'role' => 'Co-Founder',
                'bio' => 'Parent-first product direction with focus on practical mealtime routines.',
            ],
            [
                'name' => 'Monika Reddy',
                'role' => 'Co-Founder',
                'bio' => 'Builds systems and operations so clean food reaches families faster.',
            ],
        ];

        return view('store.about', compact('founders'));
    }

    public function recipes()
    {
        $recipeTips = [
            'Steam vegetables until fork-soft to preserve nutrients while keeping texture baby-safe.',
            'Introduce one new ingredient at a time and observe for 2-3 days.',
            'For early weaning, begin with smooth consistency and gradually increase texture.',
            'Use healthy fats like cold-pressed oils in tiny amounts for calorie density.',
        ];

        $featuredArticles = Blog::query()
            ->where('status', 'published')
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('store.recipes', compact('recipeTips', 'featuredArticles'));
    }

    public function faq()
    {
        $faqs = [
            [
                'q' => 'What age can babies start NumNam foods?',
                'a' => 'Most puree options are designed for 6M+ and puffs for 8M+. Always check individual product age labels.',
            ],
            [
                'q' => 'Do you add sugar or preservatives?',
                'a' => 'No. Products are designed around clean-label ingredients without refined sugar and unnecessary preservatives.',
            ],
            [
                'q' => 'How does subscription work?',
                'a' => 'Choose your plan, frequency, and quantity. You can manage active subscriptions from your account section.',
            ],
            [
                'q' => 'What payment methods are supported?',
                'a' => 'Razorpay, Stripe, UPI, card, netbanking, and COD are supported based on order eligibility.',
            ],
        ];

        return view('store.faq', compact('faqs'));
    }

    public function referFriends(Request $request)
    {
        return view('store.refer-friends', [
            'referralCode' => $request->user()?->referral_code,
        ]);
    }

    public function legal(string $slug)
    {
        $pages = $this->legalPageMap();
        abort_unless($pages->has($slug), 404);

        return view('store.legal', [
            'slug' => $slug,
            'page' => $pages->get($slug),
        ]);
    }

    public function showPage(string $slug)
    {
        $page = Page::query()
            ->where('slug', $slug)
            ->where('status', 'published')
            ->where(function ($query) {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->with(['sections' => fn($query) => $query->where('is_active', true)->orderBy('position')])
            ->firstOrFail();

        return view('store.page', [
            'page' => $page,
            'sections' => $page->sections,
        ]);
    }

    public function subscribe(Request $request, PricingPlan $plan): RedirectResponse
    {
        // Create subscription
        $subscription = $request->user()->subscriptions()->create([
            'plan_name' => $plan->name,
            'plan_type' => 'general',
            'duration' => $plan->duration ?? '1 month',
            'frequency' => $plan->billing_cycle,
            'price_per_cycle' => $plan->price,
            'discount_percent' => 0,
            'status' => 'active',
            'next_billing_date' => now()->addMonth()->toDateString(),
        ]);

        // Store subscription info in session and redirect to checkout
        $request->session()->put('subscription', [
            'id' => $subscription->id,
            'plan_name' => $plan->name,
            'price' => $plan->price,
            'frequency' => $plan->billing_cycle,
        ]);

        return redirect()->route('store.subscription.checkout')->with('status', 'Plan selected. Please complete your payment.');
    }

    public function subscriptionCheckout(Request $request)
    {
        $subscription = $request->session()->get('subscription');

        if (!$subscription) {
            return redirect()->route('store.pricing')->withErrors(['subscription' => 'No subscription plan selected.']);
        }

        return view('store.subscription-checkout', [
            'subscription' => $subscription,
            'user' => $request->user(),
        ]);
    }

    public function placeSubscriptionOrder(Request $request): RedirectResponse
    {
        $subscription = $request->session()->get('subscription');

        if (!$subscription) {
            return redirect()->route('store.pricing')->withErrors(['subscription' => 'No subscription plan selected.']);
        }

        $validated = $request->validate([
            'ship_name' => 'required|string|max:150',
            'ship_phone' => 'required|string|max:25',
            'ship_address' => 'required|string|max:255',
            'ship_city' => 'required|string|max:120',
        ]);

        // Update subscription with delivery address
        $request->user()->subscriptions()->where('id', $subscription['id'])->update([
            'delivery_address' => $validated['ship_address'],
            'delivery_city' => $validated['ship_city'],
            'delivery_phone' => $validated['ship_phone'],
        ]);

        // Clear subscription from session
        $request->session()->forget('subscription');

        return redirect()->route('store.account')->with('status', 'Subscription activated successfully! Your delivery will start from next billing cycle.');
    }

    public function blogIndex()
    {
        $blogs = Blog::query()
            ->where('status', 'published')
            ->latest('published_at')
            ->paginate(9);

        return view('store.blog.index', compact('blogs'));
    }

    public function blogShow(Blog $blog)
    {
        abort_unless($blog->status === 'published', 404);

        $blog->increment('view_count');

        return view('store.blog.show', compact('blog'));
    }

    public function contact()
    {
        return view('store.contact');
    }

    public function contactSubmit(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:120',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:25',
            'company' => 'nullable|string|max:150',
            'query_type' => 'required|in:general,order,wholesale,press,other',
            'message' => 'required|string|max:2000',
        ]);

        Contact::create($data);

        return back()->with('status', 'Thanks. We have received your message.');
    }

    public function cart(Request $request)
    {
        $cart = $this->hydrateCart($request);

        return view('store.cart', [
            'items' => $cart['items'],
            'totals' => $cart['totals'],
        ]);
    }

    public function addToCart(Request $request, Product $product): RedirectResponse
    {
        if (! $product->is_active) {
            return back()->withErrors(['cart' => 'This product is currently unavailable.']);
        }

        $qty = max(1, (int) $request->input('qty', 1));
        if ($request->user()) {
            $item = CartItem::query()->firstOrNew([
                'user_id' => $request->user()->id,
                'product_id' => $product->id,
            ]);

            $item->qty = max(1, (int) $item->qty) + $qty;
            $item->save();
        } else {
            $cart = $request->session()->get('cart', []);
            $key = (string) $product->id;

            if (isset($cart[$key])) {
                $cart[$key]['qty'] += $qty;
            } else {
                $cart[$key] = [
                    'product_id' => $product->id,
                    'qty' => $qty,
                ];
            }

            $request->session()->put('cart', $cart);
        }

        return back()->with('status', 'Added to cart.');
    }

    public function updateCart(Request $request, Product $product): RedirectResponse
    {
        $qty = max(1, (int) $request->input('qty', 1));

        if ($request->user()) {
            CartItem::query()
                ->where('user_id', $request->user()->id)
                ->where('product_id', $product->id)
                ->update(['qty' => $qty]);
        } else {
            $cart = $request->session()->get('cart', []);
            $key = (string) $product->id;

            if (isset($cart[$key])) {
                $cart[$key]['qty'] = $qty;
                $request->session()->put('cart', $cart);
            }
        }

        return back()->with('status', 'Cart updated.');
    }

    public function removeFromCart(Request $request, Product $product): RedirectResponse
    {
        if ($request->user()) {
            CartItem::query()
                ->where('user_id', $request->user()->id)
                ->where('product_id', $product->id)
                ->delete();
        } else {
            $cart = $request->session()->get('cart', []);
            unset($cart[(string) $product->id]);
            $request->session()->put('cart', $cart);
        }

        return back()->with('status', 'Item removed from cart.');
    }

    public function checkout(Request $request)
    {
        $cart = $this->hydrateCart($request);

        if (empty($cart['items'])) {
            return redirect()->route('store.products')->withErrors(['checkout' => 'Cart is empty.']);
        }

        return view('store.checkout', [
            'items' => $cart['items'],
            'totals' => $cart['totals'],
        ]);
    }

    public function placeOrder(Request $request): RedirectResponse
    {
        $cart = $this->hydrateCart($request);
        $order = null;
        $gatewayInitError = null;

        if (empty($cart['items'])) {
            return redirect()->route('store.products')->withErrors(['checkout' => 'Cart is empty.']);
        }

        $validated = $request->validate([
            'ship_name' => 'required|string|max:150',
            'ship_phone' => 'required|string|max:25',
            'ship_address' => 'required|string|max:255',
            'ship_city' => 'required|string|max:120',
            'ship_state' => 'required|string|max:120',
            'ship_pincode' => 'required|string|max:20',
            'payment_method' => 'required|in:upi,card,cod,netbanking,razorpay,stripe',
            'coupon_code' => 'nullable|string|max:32',
            'notes' => 'nullable|string|max:1200',
        ]);

        DB::transaction(function () use ($request, $validated, $cart, &$order, &$gatewayInitError) {
            $user = $request->user();
            $isFirstOrder = $user->orders()->doesntExist();
            $discounts = $this->discountService->resolve(
                $user,
                (float) $cart['totals']['subtotal'],
                $validated['coupon_code'] ?? null,
            );

            $payableTotal = max(
                0,
                ((float) $cart['totals']['subtotal'] - (float) $discounts['total_discount']) + (float) $cart['totals']['shipping_fee']
            );

            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'subtotal' => $cart['totals']['subtotal'],
                'discount' => $discounts['total_discount'],
                'shipping_fee' => $cart['totals']['shipping_fee'],
                'total' => $payableTotal,
                'payment_method' => in_array($validated['payment_method'], ['razorpay', 'upi'], true) ? 'upi' : (in_array($validated['payment_method'], ['stripe', 'card'], true) ? 'card' : $validated['payment_method']),
                'payment_gateway' => in_array($validated['payment_method'], ['razorpay', 'stripe'], true) ? $validated['payment_method'] : null,
                'payment_status' => 'pending',
                'coupon_code' => $discounts['coupon']?->code,
                'ship_name' => $validated['ship_name'],
                'ship_phone' => $validated['ship_phone'],
                'ship_address' => $validated['ship_address'],
                'ship_city' => $validated['ship_city'],
                'ship_state' => $validated['ship_state'],
                'ship_pincode' => $validated['ship_pincode'],
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($cart['items'] as $item) {
                $order->items()->create([
                    'product_id' => $item['product']->id,
                    'product_name' => $item['product']->name,
                    'unit_price' => $item['unit_price'],
                    'quantity' => $item['qty'],
                    'line_total' => $item['line_total'],
                ]);

                $item['product']->decrement('stock', $item['qty']);
            }

            if ($discounts['coupon']) {
                $discounts['coupon']->increment('used_count');
            }

            PaymentEvent::create([
                'order_id' => $order->id,
                'gateway' => $order->payment_gateway ?: 'manual',
                'event_type' => 'order.created',
                'external_reference' => $order->payment_reference,
                'status' => $order->payment_status,
                'amount' => $order->total,
                'currency' => 'INR',
                'signature_valid' => true,
                'note' => 'Order created via storefront checkout',
                'payload' => [
                    'payment_method_input' => $validated['payment_method'],
                    'coupon_code_input' => $validated['coupon_code'] ?? null,
                ],
            ]);

            if ($isFirstOrder && $user->referred_by) {
                RewardLedger::create([
                    'user_id' => $user->referred_by,
                    'order_id' => $order->id,
                    'type' => 'credit',
                    'amount' => 100,
                    'description' => 'Referral reward for first successful order',
                    'meta' => [
                        'referred_user_id' => $user->id,
                        'order_number' => $order->order_number,
                    ],
                ]);
            }

            if (in_array($order->payment_gateway, ['razorpay', 'stripe'], true)) {
                $gatewayResult = $order->payment_gateway === 'razorpay'
                    ? $this->paymentGatewayService->createRazorpayOrder($order)
                    : $this->paymentGatewayService->createStripePaymentIntent($order);

                PaymentEvent::create([
                    'order_id' => $order->id,
                    'gateway' => $order->payment_gateway,
                    'event_type' => 'checkout.session.created',
                    'external_reference' => $gatewayResult['data']['id'] ?? null,
                    'status' => $gatewayResult['success'] ? 'created' : 'failed',
                    'amount' => $order->total,
                    'currency' => 'INR',
                    'signature_valid' => true,
                    'note' => $gatewayResult['message'] ?? null,
                    'payload' => $gatewayResult,
                ]);

                if ($gatewayResult['success']) {
                    $order->update([
                        'payment_reference' => $gatewayResult['data']['id'] ?? $order->payment_reference,
                        'payment_meta' => $gatewayResult['data'] ?? null,
                    ]);
                } else {
                    $gatewayInitError = $gatewayResult['message'] ?? 'Unable to initialize online payment right now.';
                }
            }
        });

        $request->session()->forget('cart');
        if ($request->user()) {
            CartItem::query()->where('user_id', $request->user()->id)->delete();
        }

        if ($order && in_array($order->payment_gateway, ['razorpay', 'stripe'], true)) {
            if ($gatewayInitError) {
                return redirect()->route('store.account')->withErrors(['payment' => $gatewayInitError]);
            }

            return redirect()->route('store.order.success', $order)->with('status', 'Order created. Payment session initialized for ' . strtoupper($order->payment_gateway) . '.');
        }

        return redirect()->route('store.order.success', $order)->with('status', 'Order placed successfully.');
    }

    public function orderSuccess(Request $request, Order $order)
    {
        abort_unless($order->user_id === $request->user()->id, 403);
        $order->load('items');
        return view('store.order-success', compact('order'));
    }

    public function account(Request $request)
    {
        $user = $request->user();

        $orders = Order::query()
            ->where('user_id', $user->id)
            ->latest('id')
            ->with('items')
            ->take(10)
            ->get();

        $subscriptions = Subscription::query()
            ->where('user_id', $user->id)
            ->latest('id')
            ->take(10)
            ->get();

        $referrals = $user->referrals()->latest('id')->take(10)->get();
        $rewards = $user->rewardLedgers()->latest('id')->take(10)->get();
        $rewardBalance = (float) $user->rewardLedgers()
            ->selectRaw("COALESCE(SUM(CASE WHEN type = 'credit' THEN amount ELSE -amount END), 0) as balance")
            ->value('balance');

        return view('store.account', compact('orders', 'subscriptions', 'referrals', 'rewards', 'rewardBalance'));
    }

    private function hydrateCart(Request $request): array
    {
        $cartLines = $this->resolvedCartLines($request);
        $productIds = collect($cartLines)->pluck('product_id')->filter()->values();

        $products = Product::query()
            ->whereIn('id', $productIds)
            ->where('is_active', true)
            ->get()
            ->keyBy('id');

        $items = [];
        $subtotal = 0.0;

        foreach ($cartLines as $line) {
            $product = $products->get((int) ($line['product_id'] ?? 0));
            if (! $product) {
                continue;
            }

            $qty = max(1, (int) ($line['qty'] ?? 1));
            $unitPrice = (float) ($product->sale_price ?? $product->price);
            $lineTotal = $unitPrice * $qty;

            $items[] = [
                'product' => $product,
                'qty' => $qty,
                'unit_price' => $unitPrice,
                'line_total' => $lineTotal,
            ];

            $subtotal += $lineTotal;
        }

        $shippingFee = $subtotal > 0 && $subtotal < 999 ? 99.0 : 0.0;

        return [
            'items' => $items,
            'totals' => [
                'subtotal' => $subtotal,
                'shipping_fee' => $shippingFee,
                'total' => $subtotal + $shippingFee,
            ],
        ];
    }

    private function resolvedCartLines(Request $request): array
    {
        if (! $request->user()) {
            return array_values($request->session()->get('cart', []));
        }

        $this->syncSessionCartToPersistentCart($request);

        return CartItem::query()
            ->where('user_id', $request->user()->id)
            ->orderBy('id')
            ->get(['product_id', 'qty'])
            ->map(fn(CartItem $item) => [
                'product_id' => (int) $item->product_id,
                'qty' => max(1, (int) $item->qty),
            ])
            ->all();
    }

    private function syncSessionCartToPersistentCart(Request $request): void
    {
        $sessionCart = $request->session()->get('cart', []);

        if (empty($sessionCart)) {
            return;
        }

        foreach ($sessionCart as $line) {
            $productId = (int) ($line['product_id'] ?? 0);
            if ($productId <= 0) {
                continue;
            }

            $qty = max(1, (int) ($line['qty'] ?? 1));

            $item = CartItem::query()->firstOrNew([
                'user_id' => $request->user()->id,
                'product_id' => $productId,
            ]);

            $item->qty = max(0, (int) $item->qty) + $qty;
            $item->save();
        }

        $request->session()->forget('cart');
    }

    public function wishlist(Request $request)
    {
        $products = $request->user()->wishlists()
            ->with('product')
            ->latest('id')
            ->paginate(12)
            ->through(fn($w) => $w->product)
            ->filter();

        return view('store.wishlist', compact('products'));
    }

    public function toggleWishlist(Request $request, Product $product): RedirectResponse
    {
        $existing = Wishlist::where('user_id', $request->user()->id)
            ->where('product_id', $product->id)
            ->first();

        if ($existing) {
            $existing->delete();
            return back()->with('status', 'Removed from wishlist.');
        }

        Wishlist::create([
            'user_id' => $request->user()->id,
            'product_id' => $product->id,
        ]);

        return back()->with('status', 'Added to wishlist!');
    }

    public function storeReview(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:150',
            'body' => 'required|string|min:10|max:2000',
        ]);

        ProductReview::updateOrCreate(
            ['user_id' => $request->user()->id, 'product_id' => $product->id],
            array_merge($validated, [
                'is_approved' => false,
                'moderation_status' => 'pending',
                'moderated_at' => null,
            ])
        );

        return back()->with('status', 'Review submitted! It will appear after moderation.');
    }

    private function legalPageMap(): Collection
    {
        return collect([
            'terms-conditions' => [
                'title' => 'Terms & Conditions',
                'sections' => [
                    ['heading' => 'Usage', 'text' => 'By using NumNam, you agree to purchase and use products according to age guidance and label instructions.'],
                    ['heading' => 'Orders', 'text' => 'Orders may be cancelled only before fulfillment begins. Refund and return terms apply as listed.'],
                    ['heading' => 'Intellectual Property', 'text' => 'All text, visuals, and trademarks are owned by NumNam and may not be copied without permission.'],
                ],
            ],
            'privacy-policy' => [
                'title' => 'Privacy Policy',
                'sections' => [
                    ['heading' => 'What We Collect', 'text' => 'We collect order, account, and delivery details required for commerce operations and support.'],
                    ['heading' => 'How We Use Data', 'text' => 'Data is used for order fulfillment, customer support, product communication, and fraud prevention.'],
                    ['heading' => 'Your Controls', 'text' => 'You can request updates or deletion of personal data by contacting support.'],
                ],
            ],
            'shipping-policy' => [
                'title' => 'Shipping Policy',
                'sections' => [
                    ['heading' => 'Dispatch Window', 'text' => 'Orders are usually processed within 24-48 hours and shipped via trusted courier partners.'],
                    ['heading' => 'Delivery Coverage', 'text' => 'Delivery timelines vary by city and pincode serviceability.'],
                    ['heading' => 'Shipping Charges', 'text' => 'Shipping may be free above threshold order values, with standard fees applied otherwise.'],
                ],
            ],
            'refund-policy' => [
                'title' => 'Refund Policy',
                'sections' => [
                    ['heading' => 'Damaged or Incorrect Item', 'text' => 'Report with photo evidence within the support window to initiate replacement/refund review.'],
                    ['heading' => 'Eligibility', 'text' => 'Opened consumables are generally non-returnable unless quality issues are verified.'],
                    ['heading' => 'Refund Timeline', 'text' => 'Approved refunds are processed to original payment method within standard banking timelines.'],
                ],
            ],
            'cookie-policy' => [
                'title' => 'Cookie Policy',
                'sections' => [
                    ['heading' => 'Essential Cookies', 'text' => 'Used for login sessions, cart integrity, and checkout continuity.'],
                    ['heading' => 'Analytics Cookies', 'text' => 'Help improve UX by measuring navigation and content engagement.'],
                    ['heading' => 'Control', 'text' => 'You can manage cookies from browser settings at any time.'],
                ],
            ],
            'payment-methods' => [
                'title' => 'Payment Methods',
                'sections' => [
                    ['heading' => 'Online Methods', 'text' => 'Razorpay and Stripe support secure cards, UPI, and wallet-friendly flows.'],
                    ['heading' => 'Offline/Alternate', 'text' => 'COD or manual payment options may be available for selected orders.'],
                    ['heading' => 'Security', 'text' => 'Payment credentials are handled by secure gateway providers and never stored in plain text.'],
                ],
            ],
        ]);
    }
}

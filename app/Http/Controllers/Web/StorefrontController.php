<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Mail\NewOrderAdminNotification;
use App\Mail\OrderPlacedCustomerNotification;
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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
        try {
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
        } catch (\Exception $e) {
            Log::error('Home page error: ' . $e->getMessage());
            // Return view with minimal data on error
            return view('store.home', [
                'featuredProducts' => collect([]),
                'bestSellerProducts' => collect([]),
                'recentlyViewedProducts' => collect([]),
                'plans' => collect([]),
                'latestBlogs' => collect([]),
                'topCategories' => collect([]),
                'homepageSections' => collect([]),
                'trustHighlights' => [
                    ['title' => 'Doctor-backed recipes', 'text' => 'Developed with pediatric nutrition intent.'],
                    ['title' => 'No refined sugar', 'text' => 'Balanced taste without unnecessary sweeteners.'],
                    ['title' => 'Stage-wise textures', 'text' => 'Feeding progression tuned for each age group.'],
                    ['title' => 'Subscription friendly', 'text' => 'Predictable recurring delivery for parents.'],
                ],
                'testimonials' => [
                    ['name' => 'Aarohi S.', 'quote' => 'Our baby transitioned beautifully from purees to puffs.'],
                    ['name' => 'Raghav P.', 'quote' => 'Subscription and reorder flow saved us so much time.'],
                    ['name' => 'Meera K.', 'quote' => 'Ingredients and nutrition information are very transparent.'],
                ],
            ]);
        }
    }

    public function products(Request $request)
    {
        try {
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
        } catch (\Exception $e) {
            Log::error('Products page error: ' . $e->getMessage());
            // Return view with empty data on error
            return view('store.products.index', [
                'products' => collect([]),
                'categories' => collect([]),
            ]);
        }
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

        $reviewStats = Product::query()
            ->whereKey($product->id)
            ->withCount('approvedReviews')
            ->withAvg('approvedReviews', 'rating')
            ->first(['id']);

        $product->setAttribute('approved_reviews_count', (int) ($reviewStats?->approved_reviews_count ?? 0));
        $product->setAttribute('approved_reviews_avg_rating', $reviewStats?->approved_reviews_avg_rating);

        $reviews = $product->approvedReviews()
            ->with('user')
            ->latest()
            ->take(6)
            ->get();

        $related = Product::query()
            ->withCount('approvedReviews')
            ->withAvg('approvedReviews', 'rating')
            ->where('is_active', true)
            ->where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->latest('id')
            ->take(4)
            ->get();

        $gallery = collect($product->gallery_urls ?: [])->filter()->values();

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

        return view('store.products.show', compact('product', 'related', 'gallery', 'recentlyViewedProducts', 'reviews'));
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
                'name' => 'Dr. Donuru, Srinath Reddy',
                'role' => 'Managing Director & Research Lead',
                'bio' => 'Cardio-thoracic Surgeon (Germany). Clinical Research Graduate (Harvard Medical School). Bringing German medical precision to infant nutrition.',
            ],
            [
                'name' => 'Dr. Kodeboina, Monika',
                'role' => 'Co-Founder & Head of Recipes',
                'bio' => 'Cardiologist (Germany). MBA (Frankfurt Business School). MSc: Lifestyle Medicine (Europe). The bridge between European standards and Indian palates.',
            ],
            [
                'name' => 'Kian',
                'role' => 'CIO: Chief Inspiration Officer',
                'bio' => 'The little one who started it all. Our first taster and the reason NumNam exists.',
            ],
            [
                'name' => 'Smiti',
                'role' => 'CHH: Chief of Happy Hearts',
                'bio' => 'Spreading joy and keeping the NumNam spirit alive in every pouch we make.',
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
                'q' => 'What is NumNam, really?',
                'a' => 'NumNam is a doctor-founded baby food brand offering nutritious, ready-to-eat purees made with real fruits, veggies, and grains — no added nonsense.',
            ],
            [
                'q' => 'What age group are NumNam products meant for?',
                'a' => 'Perfect for babies starting from 6 months! And to be honest — even curious toddlers and older kids love it.',
            ],
            [
                'q' => 'What\'s in a NumNam pouch?',
                'a' => 'Only the good stuff — fruits, veggies, whole grains, no preservatives, and nothing artificial. All cooked and sealed under strict safety conditions.',
            ],
            [
                'q' => 'How do I serve it?',
                'a' => 'Twist and squeeze! You can feed straight from the pouch or pour into a bowl. Serve at room temperature or slightly warmed (never microwave the pouch).',
            ],
            [
                'q' => 'How do I store NumNam products?',
                'a' => 'Store in a cool place before opening. Once opened, refrigerate and finish within 24 hours.',
            ],
            [
                'q' => 'Are NumNam products safe?',
                'a' => 'Yes! We\'re doctors and parents ourselves. Our pouches are retort processed, lab-tested, and made in certified kitchens.',
            ],
            [
                'q' => 'Where do you deliver?',
                'a' => 'We deliver across India using trusted courier partners. Just check your pin code at checkout.',
            ],
            [
                'q' => 'Do you offer free shipping?',
                'a' => 'Absolutely! Orders above ₹499 ship free. Otherwise, it\'s ₹85.',
            ],
            [
                'q' => 'What if my pouch is damaged or missing?',
                'a' => 'Contact us at customercare@numnam.com within 24–48 hours with photos. We\'ll sort it out quickly.',
            ],
            [
                'q' => 'Still have questions?',
                'a' => 'Email us at customercare@numnam.com or call +91-9014252278. We\'re always happy to chat!',
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

    public function placeOrder(Request $request): RedirectResponse|JsonResponse
    {
        $cart = $this->hydrateCart($request);
        $order = null;
        $gatewayInitError = null;
        $gatewayInitResult = null;

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
            'payment_method' => 'required|in:razorpay,cod',
            'coupon_code' => 'nullable|string|max:32',
            'notes' => 'nullable|string|max:1200',
        ]);

        // Server-side COD validation
        if ($validated['payment_method'] === 'cod') {
            $codSettings = SiteSetting::whereIn('key', [
                'payment_cod_enabled',
                'payment_cod_min_order',
                'payment_cod_max_order',
                'payment_cod_allowed_pincodes',
            ])->pluck('value', 'key');

            if (($codSettings['payment_cod_enabled'] ?? '0') !== '1') {
                return redirect()->back()->withErrors(['payment_method' => 'Cash on Delivery is not available.'])->withInput();
            }

            $codMin = (float) ($codSettings['payment_cod_min_order'] ?? 0);
            $codMax = (float) ($codSettings['payment_cod_max_order'] ?? 0);
            $cartTotal = (float) $cart['totals']['total'];

            if ($codMin > 0 && $cartTotal < $codMin) {
                return redirect()->back()->withErrors(['payment_method' => 'Cash on Delivery requires a minimum order of ₹' . number_format($codMin, 0) . '.'])->withInput();
            }
            if ($codMax > 0 && $cartTotal > $codMax) {
                return redirect()->back()->withErrors(['payment_method' => 'Cash on Delivery is not available for orders above ₹' . number_format($codMax, 0) . '.'])->withInput();
            }

            $codPincodes = array_filter(array_map('trim', explode(',', $codSettings['payment_cod_allowed_pincodes'] ?? '')));
            if (!empty($codPincodes) && !in_array($validated['ship_pincode'], $codPincodes, true)) {
                return redirect()->back()->withErrors(['payment_method' => 'Cash on Delivery is not available for your pincode.'])->withInput();
            }
        }

        DB::transaction(function () use ($request, $validated, $cart, &$order, &$gatewayInitError, &$gatewayInitResult) {
            $user = $request->user();
            $isFirstOrder = $user->orders()->doesntExist();
            $discounts = $this->discountService->resolve(
                $user,
                (float) $cart['totals']['subtotal'],
                $validated['coupon_code'] ?? null,
            );

            // Tax calculation
            $taxSettings = SiteSetting::whereIn('key', ['tax_gst_enabled', 'tax_gst_rate', 'tax_inclusive'])
                ->pluck('value', 'key');
            $gstEnabled = ($taxSettings['tax_gst_enabled'] ?? '0') === '1';
            $gstRate = (float) ($taxSettings['tax_gst_rate'] ?? 0);
            $taxInclusive = ($taxSettings['tax_inclusive'] ?? '1') === '1';
            $taxAmount = 0;

            $subtotalAfterDiscount = max(0, (float) $cart['totals']['subtotal'] - (float) $discounts['total_discount']);

            if ($gstEnabled && $gstRate > 0) {
                if ($taxInclusive) {
                    // Tax is included in prices; extract it for display
                    $taxAmount = round($subtotalAfterDiscount - ($subtotalAfterDiscount / (1 + $gstRate / 100)), 2);
                } else {
                    // Tax is added on top
                    $taxAmount = round($subtotalAfterDiscount * ($gstRate / 100), 2);
                }
            }

            $payableTotal = max(
                0,
                $subtotalAfterDiscount + ($taxInclusive ? 0 : $taxAmount) + (float) $cart['totals']['shipping_fee']
            );

            $isCod = $validated['payment_method'] === 'cod';

            $order = Order::create([
                'user_id' => $user->id,
                'status' => $isCod ? 'processing' : 'pending',
                'subtotal' => $cart['totals']['subtotal'],
                'discount' => $discounts['total_discount'],
                'tax_amount' => $taxAmount,
                'shipping_fee' => $cart['totals']['shipping_fee'],
                'total' => $payableTotal,
                'payment_method' => $isCod ? 'cod' : 'upi',
                'payment_gateway' => $isCod ? null : 'razorpay',
                'payment_status' => $isCod ? 'cod_pending' : 'pending',
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

            if (in_array($order->payment_gateway, ['razorpay'], true)) {
                $gatewayResult = $this->paymentGatewayService->createRazorpayOrder($order);
                $gatewayInitResult = $gatewayResult;

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

        if ($order) {
            $this->sendOrderCreationNotifications($order);
        }

        $request->session()->forget('cart');
        if ($request->user()) {
            CartItem::query()->where('user_id', $request->user()->id)->delete();
        }

        if ($order && in_array($order->payment_gateway, ['razorpay'], true)) {
            if ($request->expectsJson()) {
                if ($gatewayInitError) {
                    return response()->json([
                        'success' => false,
                        'message' => $gatewayInitError,
                        'order_id' => $order->id,
                        'order_number' => $order->order_number,
                    ], 422);
                }

                return response()->json([
                    'success' => true,
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'amount' => (int) round(((float) $order->total) * 100),
                    'currency' => 'INR',
                    'razorpay_key' => (string) config('services.razorpay.key_id'),
                    'razorpay_order_id' => $order->payment_reference,
                    'verify_url' => route('store.checkout.payment.verify', $order),
                    'success_url' => route('store.order.success', $order),
                    'session_result' => $gatewayInitResult,
                ]);
            }

            if ($gatewayInitError) {
                return redirect()->route('store.order.success', $order)
                    ->withErrors(['payment' => $gatewayInitError . ' You can retry payment below.'])
                    ->with('status', 'Order created. Please complete payment to confirm.');
            }

            return redirect()->route('store.order.success', $order)
                ->with('status', 'Order created. Continue to complete your payment.');
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

    public function updateProfile(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|in:male,female,other',
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
        ]);

        $request->user()->fill($validated)->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    public function changePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if (! \Illuminate\Support\Facades\Hash::check($request->current_password, $request->user()->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $request->user()->update(['password' => \Illuminate\Support\Facades\Hash::make($request->new_password)]);

        return back()->with('success', 'Password changed successfully.');
    }

    private function sendOrderCreationNotifications(Order $order): void
    {
        $order->loadMissing(['items', 'user']);

        if ($order->user?->email) {
            try {
                Mail::to($order->user->email)->send(new OrderPlacedCustomerNotification($order));
            } catch (\Throwable $e) {
                Log::warning('Customer order confirmation email failed', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'message' => $e->getMessage(),
                ]);
            }
        }

        $adminRecipient = (string) config('mail.order_recipient', '');
        if ($adminRecipient) {
            try {
                Mail::to($adminRecipient)->send(new NewOrderAdminNotification($order));
            } catch (\Throwable $e) {
                Log::warning('Admin order notification email failed', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'recipient' => $adminRecipient,
                    'message' => $e->getMessage(),
                ]);
            }
        }
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
                    ['heading' => '1. Use of Website', 'text' => 'NumNam grants you a limited, non-exclusive, and revocable license to use this website for personal and non-commercial purposes, in accordance with these Terms. You must not use this site for any unlawful or fraudulent activity, to interfere with or damage the website\'s availability, functionality, or security, to upload viruses, malicious code, or spam, or to scrape or extract data without written consent. This website uses cookies. By using the website, you agree to our use of cookies as outlined in our Privacy Policy.'],
                    ['heading' => '2. Intellectual Property', 'text' => 'Unless otherwise stated, all content on this website — including text, images, logos, and product descriptions — is owned by or licensed to NumNam (Kikudu Corp). All rights are reserved. You may not reuse, republish, or distribute content without written permission.'],
                    ['heading' => '3. User Accounts', 'text' => 'If you create an account on this website, you are responsible for maintaining the confidentiality of your login information. You agree to notify us immediately of any unauthorized use. We reserve the right to suspend or terminate accounts at our discretion.'],
                    ['heading' => '4. Product Orders & Payments', 'text' => 'All products listed remain the property of NumNam until full payment is received. Orders are processed via third-party payment gateways. We are not responsible for delays or issues caused by these services. Prices, availability, and specifications are subject to change without notice.'],
                    ['heading' => '5. User Submissions', 'text' => 'Any content submitted by users (such as reviews or feedback) may be used by NumNam for promotional or informational purposes. You confirm that your submission is original and does not violate the rights of others. We reserve the right to remove user content at our discretion.'],
                    ['heading' => '6. No Warranties', 'text' => 'This website is provided on an "as is" and "as available" basis. NumNam does not guarantee that the website will be error-free or uninterrupted, nor do we make any warranties regarding the accuracy or completeness of the content.'],
                    ['heading' => '7. Limitation of Liability', 'text' => 'To the fullest extent permitted by law, NumNam shall not be liable for any indirect, incidental, or consequential damages arising out of your use of the website or products. Our total liability shall not exceed the amount paid for the product in question.'],
                    ['heading' => '8. Indemnity', 'text' => 'You agree to indemnify and hold harmless NumNam, its parent company Kikudu Corp, employees, and affiliates from any claims, liabilities, or expenses arising from your breach of these Terms or misuse of the website.'],
                    ['heading' => '9. Governing Law', 'text' => 'These Terms are governed by the laws of India. Any disputes shall be subject to the exclusive jurisdiction of the courts located in Hyderabad, India.'],
                    ['heading' => '10. Changes to Terms', 'text' => 'NumNam reserves the right to revise these Terms at any time. Any updates will be posted on this page. Continued use of the website following changes constitutes acceptance of those changes.'],
                    ['heading' => '11. Contact', 'text' => 'For any questions about these Terms, please contact: NumNam (Kikudu Corp) — Email: customercare@numnam.com — Website: www.numnam.com'],
                ],
            ],
            'privacy-policy' => [
                'title' => 'Privacy Policy',
                'sections' => [
                    ['heading' => 'Scope & Consent', 'text' => 'This Privacy Policy applies to all users of the NumNam website. By visiting or using our site, you expressly consent to the collection, use, and disclosure of your personal information in accordance with this policy. NumNam (a brand by Kikudu Corp) reserves the right to update this policy at any time without prior notice. Changes will be effective upon posting. Effective Date: 01.04.2025.'],
                    ['heading' => 'Information We Collect', 'text' => 'We may collect: information you provide when creating an account or placing an order (name, email, phone number, shipping & billing addresses), order and transaction details, communications you send us via email, forms, or social media, and information about your use of our site via cookies and analytics tools. This information helps us process orders, provide support, and enhance your experience.'],
                    ['heading' => 'How We Use Your Information', 'text' => 'Your information may be used to: process and deliver your orders, contact you with order confirmations or updates, respond to customer service requests, send promotional messages (if you opt in), improve website functionality and user experience, and prevent fraud and ensure legal compliance. Only authorized employees or service providers who agree to maintain confidentiality have access to your data.'],
                    ['heading' => 'Data Security', 'text' => 'We take reasonable technical and organizational precautions to prevent unauthorized access, misuse, or disclosure of your personal data. Your information is stored on secure servers and is only accessible to trusted personnel. However, please note that no digital platform is completely secure. We recommend you take standard precautions when sharing sensitive information online.'],
                    ['heading' => 'Cookies & Tracking Tools', 'text' => 'NumNam uses cookies and similar technologies to track website activity and preferences, enhance your browsing experience, and analyze user behavior for website improvement. You can manage cookie settings through your browser. Disabling cookies may affect site functionality.'],
                    ['heading' => 'Sharing of Information', 'text' => 'We do not sell or rent your personal data. We may share information with trusted third parties to process payments and deliveries, to ensure compliance with legal requirements, and in case of mergers, business transfers, or audits. We may also disclose your data if required by law or to protect our rights.'],
                    ['heading' => 'Communication', 'text' => 'You may receive communications from NumNam such as order-related emails, product updates or offers (if you subscribe), and important service announcements. You may opt-out of promotional communications at any time.'],
                    ['heading' => 'Cross-Border Data Transfers', 'text' => 'Your personal data may be stored and processed in countries where we or our partners operate. By using our website, you consent to such transfers.'],
                    ['heading' => 'Changes to This Policy', 'text' => 'We may update this policy from time to time. Material changes will be highlighted on our homepage or via email.'],
                    ['heading' => 'Contact Us', 'text' => 'If you have any questions, concerns, or requests regarding this Privacy Policy, please contact: NumNam (Kikudu Corp) — Email: customercare@numnam.com — Website: www.numnam.com. Your trust matters to us. We are committed to protecting your data and providing a safe, joyful experience for your family.'],
                ],
            ],
            'shipping-policy' => [
                'title' => 'Shipping Policy',
                'sections' => [
                    ['heading' => 'Shipping Destination', 'text' => 'We currently ship across India — from big cities to tiny towns. International shipping is not available yet, but we\'re working on it!'],
                    ['heading' => 'Order Processing Time', 'text' => 'Orders are processed within 1–2 business days (excluding weekends and public holidays). You\'ll receive a confirmation email once your order is placed. If we experience delays, we\'ll let you know via email or phone.'],
                    ['heading' => 'Shipping Time & Delivery Estimates', 'text' => 'Metro Cities: 3–5 business days. Tier 2 & Tier 3 Cities: 5–7 business days. Remote Locations: 7–10 business days. Delivery timelines are estimates and may vary due to weather, courier delays, or unexpected hiccups.'],
                    ['heading' => 'Shipping Charges', 'text' => 'Orders above ₹499: Free Shipping! Orders ₹499 & below: Standard shipping fee of ₹85 applies.'],
                    ['heading' => 'Order Tracking', 'text' => 'As soon as your order ships, you\'ll receive an email with tracking details. You can follow its journey from our nest to yours!'],
                    ['heading' => 'Shipping Partners', 'text' => 'We team up with Shiprocket, which works with India\'s most trusted courier services. Depending on your location, your delivery might be made by one of their verified partners.'],
                    ['heading' => 'Undelivered Packages', 'text' => 'If your parcel can\'t be delivered due to incorrect address or repeated delivery failures, we\'ll attempt re-delivery if possible, offer re-shipment after additional shipping fee, or issue a refund after deducting shipping charges.'],
                    ['heading' => 'Damaged or Missing Items', 'text' => 'If your order arrives damaged, email us within 24 hours with photos. If anything\'s missing, report within 48 hours. We\'ll provide a solution — be it a replacement or refund.'],
                    ['heading' => 'Change of Address Requests', 'text' => 'Entered the wrong address? Contact us ASAP at customercare@numnam.com and we\'ll do our best to update it before shipping.'],
                    ['heading' => 'Contact Us', 'text' => 'For any shipping-related help, contact: Email: customercare@numnam.com — Phone: +91-9014252278'],
                ],
            ],
            'refund-policy' => [
                'title' => 'Refund Policy',
                'sections' => [
                    ['heading' => '1. Order Cancellation by Customer', 'text' => 'Orders can be cancelled within 12 hours of placing the order or before the order has been shipped, whichever comes first. Once your order is dispatched, it cannot be cancelled due to food safety and logistics constraints. To request a cancellation, please email us at customercare@numnam.com with your Order ID and cancellation reason.'],
                    ['heading' => '2. Order Cancellation by NumNam', 'text' => 'We reserve the right to cancel orders due to out-of-stock items, logistical limitations or delivery constraints, or unforeseen operational issues. In such cases, you will receive a full refund to the original payment method within 7–10 business days.'],
                    ['heading' => '3. Non-Cancellable Orders', 'text' => 'For food safety and operational efficiency, the following orders cannot be cancelled once processed or dispatched: Ready-to-Eat or pureed food pouches, and customized or bulk orders.'],
                    ['heading' => '4. Refund Processing', 'text' => 'If your order is cancelled before shipment, a full refund will be processed within 7–10 business days.'],
                    ['heading' => '5. Need Help?', 'text' => 'For any order-related queries, feel free to contact us: Email: customercare@numnam.com — Phone: +91-9014252278. We appreciate your trust in NumNam and are committed to delivering a delightful and dependable shopping experience for every family.'],
                ],
            ],
            'cookie-policy' => [
                'title' => 'Cookie Policy',
                'sections' => [
                    ['heading' => 'What Are Cookies?', 'text' => 'Cookies are small text files placed on your device when you visit a website. They help us understand how users interact with our site, enhance your browsing experience, and enable basic website functionality.'],
                    ['heading' => 'Why We Use Cookies', 'text' => 'We use cookies to remember your preferences (like language or cart items), understand user behavior and site usage, enable secure sign-ins and order functionality, improve speed and performance, and deliver relevant content and product recommendations.'],
                    ['heading' => 'Types of Cookies We Use', 'text' => 'Essential Cookies — Necessary for core website functions like navigation and checkout. Performance Cookies — Help us analyze how visitors use the site and improve our services. Functional Cookies — Store preferences to personalize your experience. Marketing Cookies — Track activity to offer relevant ads and promotions (we keep it minimal and never creepy).'],
                    ['heading' => 'Managing Your Cookie Preferences', 'text' => 'You can control or delete cookies through your browser settings. Most browsers allow you to block all cookies, allow only selected ones, or clear cookies when you close the browser. However, disabling cookies may affect certain features (like saving your cart or auto-login).'],
                    ['heading' => 'Your Privacy Matters', 'text' => 'Cookies are just one part of how we ensure a smooth and delightful experience on NumNam. All cookie data is handled securely and in compliance with our Privacy Policy.'],
                    ['heading' => 'Contact Us', 'text' => 'If you have questions about this Cookie Policy, feel free to reach out: customercare@numnam.com'],
                ],
            ],
            'payment-methods' => [
                'title' => 'Payment Methods',
                'sections' => [
                    ['heading' => 'Accepted Payment Methods', 'text' => 'We accept credit/debit cards, UPI, net banking, and wallet payments through our secure payment gateway.'],
                    ['heading' => 'Online Payments', 'text' => 'All online payments are processed securely through Razorpay. We support Visa, Mastercard, RuPay, UPI (Google Pay, PhonePe, Paytm), Net Banking, and popular digital wallets.'],
                    ['heading' => 'Cash on Delivery', 'text' => 'Cash on Delivery (COD) is available for eligible orders based on location and order value.'],
                    ['heading' => 'Security', 'text' => 'Payment credentials are handled by secure gateway providers and never stored in plain text. All transactions are encrypted and PCI-DSS compliant.'],
                ],
            ],
        ]);
    }
}

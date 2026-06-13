@extends('store.layouts.app')

@section('title', 'NumNam Shop - Baby Products & Foods')

@section('content')
<section class="section py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold tracking-tight text-slate-900">NumNam Shop</h1>
        <p class="mt-2 text-lg text-slate-600">Carefully curated products and organic foods for your baby's weaning journey.</p>
    </div>

    {{-- Category Filter --}}
    <div class="mb-8 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
        <div class="flex flex-wrap gap-2">
            <button class="category-filter active rounded-full border-2 border-numnam-600 bg-numnam-50 px-4 py-2 font-semibold text-numnam-700" data-category="all">
                All Products
            </button>
            <button class="category-filter rounded-full border-2 border-slate-200 bg-white px-4 py-2 font-semibold text-slate-600 transition hover:border-slate-300" data-category="organic-foods">
                🥕 Organic Foods
            </button>
            <button class="category-filter rounded-full border-2 border-slate-200 bg-white px-4 py-2 font-semibold text-slate-600 transition hover:border-slate-300" data-category="feeding-gear">
                🍽️ Feeding Gear
            </button>
            <button class="category-filter rounded-full border-2 border-slate-200 bg-white px-4 py-2 font-semibold text-slate-600 transition hover:border-slate-300" data-category="supplements">
                💊 Supplements
            </button>
            <button class="category-filter rounded-full border-2 border-slate-200 bg-white px-4 py-2 font-semibold text-slate-600 transition hover:border-slate-300" data-category="books">
                📚 Books
            </button>
        </div>
    </div>

    @auth
    {{-- Products Grid --}}
    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4" id="products-grid">
        <div class="col-span-full text-center py-12">
            <p class="text-slate-500">Loading products...</p>
        </div>
    </div>
    @else
    <div class="rounded-3xl border border-slate-200 bg-white p-10 text-center shadow-sm">
        <p class="text-lg text-slate-600">Please log in to view products and make purchases.</p>
        <a href="{{ route('store.login') }}" class="mt-4 inline-flex rounded-full bg-numnam-600 px-6 py-2.5 font-semibold text-white transition hover:bg-numnam-700">Log In</a>
    </div>
    @endauth
</section>

@endsection

@section('scripts')
@auth
<script>
    let allProducts = [];

    // Load products from API or show sample
    async function loadProducts() {
        try {
            // For now, show sample products since API might not be set up
            const sampleProducts = [{
                    id: 1,
                    name: "Organic Apple Puree",
                    category: "organic-foods",
                    price: 249,
                    image: "🍎",
                    description: "100% organic, no added sugar"
                },
                {
                    id: 2,
                    name: "Stainless Steel Baby Spoon Set",
                    category: "feeding-gear",
                    price: 399,
                    image: "🥄",
                    description: "Soft, ergonomic handles"
                },
                {
                    id: 3,
                    name: "Silicone High Chair Placemat",
                    category: "feeding-gear",
                    price: 599,
                    image: "🎨",
                    description: "Non-slip, easy to clean"
                },
                {
                    id: 4,
                    name: "Vitamin D3 Drops",
                    category: "supplements",
                    price: 799,
                    image: "💉",
                    description: "Specially formulated for babies"
                },
                {
                    id: 5,
                    name: "Baby Led Weaning Guide",
                    category: "books",
                    price: 349,
                    image: "📚",
                    description: "Complete guide & recipes"
                },
                {
                    id: 6,
                    name: "Organic Sweet Potato Puree",
                    category: "organic-foods",
                    price: 279,
                    image: "🍠",
                    description: "Rich in vitamins and minerals"
                },
                {
                    id: 7,
                    name: "BPA-Free Food Storage Containers",
                    category: "feeding-gear",
                    price: 649,
                    image: "📦",
                    description: "Set of 6, stackable design"
                },
                {
                    id: 8,
                    name: "Probiotics for Babies",
                    category: "supplements",
                    price: 899,
                    image: "🦠",
                    description: "Supports digestive health"
                }
            ];

            allProducts = sampleProducts;
            displayProducts('all');
        } catch (error) {
            console.error('Error loading products:', error);
        }
    }

    function displayProducts(category) {
        const filtered = category === 'all' ?
            allProducts :
            allProducts.filter(p => p.category === category);

        const grid = document.getElementById('products-grid');
        grid.innerHTML = filtered.map(product => `
            <div class="rounded-2xl border border-slate-200 bg-white overflow-hidden shadow-sm hover:shadow-md transition">
                <div class="h-40 bg-slate-100 flex items-center justify-center text-5xl">
                    ${product.image}
                </div>
                <div class="p-4">
                    <h3 class="font-bold text-slate-900 mb-1">${product.name}</h3>
                    <p class="text-sm text-slate-500 mb-3">${product.description}</p>
                    <div class="flex items-center justify-between">
                        <span class="text-lg font-bold text-numnam-600">Rs ${product.price}</span>
                        <button class="rounded-lg bg-numnam-600 px-3 py-2 text-sm font-semibold text-white transition hover:bg-numnam-700 add-to-cart-btn" data-id="${product.id}">
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>
        `).join('');

        // Attach cart event listeners
        document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const productId = this.dataset.id;
                const product = allProducts.find(p => p.id == productId);
                addToCart(product);
            });
        });
    }

    function addToCart(product) {
        const successMsg = document.createElement('div');
        successMsg.className = 'fixed top-4 right-4 rounded-lg bg-emerald-500 text-white px-4 py-3 shadow-lg';
        successMsg.textContent = `✓ ${product.name} added to cart!`;
        document.body.appendChild(successMsg);
        setTimeout(() => successMsg.remove(), 3000);

        // TODO: Implement actual cart functionality
    }

    // Category filter
    document.querySelectorAll('.category-filter').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.category-filter').forEach(b => {
                b.classList.remove('active', 'border-numnam-600', 'bg-numnam-50', 'text-numnam-700');
                b.classList.add('border-slate-200', 'bg-white', 'text-slate-600');
            });

            this.classList.add('active', 'border-numnam-600', 'bg-numnam-50', 'text-numnam-700');
            this.classList.remove('border-slate-200', 'bg-white', 'text-slate-600');

            displayProducts(this.dataset.category);
        });
    });

    // Load products on page load
    loadProducts();
</script>
@endauth
@endsection
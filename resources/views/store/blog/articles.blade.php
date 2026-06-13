@extends('store.layouts.app')

@section('title', 'NumNam Blog - Weaning Tips, Recipes & Advice')

@section('content')
<section class="section py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold tracking-tight text-slate-900">NumNam Blog</h1>
        <p class="mt-2 text-lg text-slate-600">Expert tips, recipes, and guidance for your baby's weaning journey.</p>
    </div>

    {{-- Category Filter --}}
    <div class="mb-8 flex flex-wrap gap-2 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
        <button class="blog-filter active rounded-full border-2 border-numnam-600 bg-numnam-50 px-4 py-2 font-semibold text-numnam-700" data-category="all">
            All Articles
        </button>
        <button class="blog-filter rounded-full border-2 border-slate-200 bg-white px-4 py-2 font-semibold text-slate-600 transition hover:border-slate-300" data-category="recipes">
            🍽️ Recipes
        </button>
        <button class="blog-filter rounded-full border-2 border-slate-200 bg-white px-4 py-2 font-semibold text-slate-600 transition hover:border-slate-300" data-category="nutrition">
            🥗 Nutrition
        </button>
        <button class="blog-filter rounded-full border-2 border-slate-200 bg-white px-4 py-2 font-semibold text-slate-600 transition hover:border-slate-300" data-category="health">
            ❤️ Health & Safety
        </button>
        <button class="blog-filter rounded-full border-2 border-slate-200 bg-white px-4 py-2 font-semibold text-slate-600 transition hover:border-slate-300" data-category="development">
            🧠 Development
        </button>
    </div>

    {{-- Articles Grid --}}
    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3" id="articles-grid">
        <div class="col-span-full py-12 text-center">
            <p class="text-slate-500">Loading articles...</p>
        </div>
    </div>
</section>

@endsection

@section('scripts')
<script>
    let allArticles = [];

    async function loadArticles() {
        const sampleArticles = [{
                id: 1,
                title: "6-Month-Old: Signs Your Baby is Ready for Solids",
                category: "development",
                excerpt: "Learn the key developmental signs that indicate your baby is ready to start solids.",
                image: "👶",
                date: "2024-01-15",
                author: "Dr. Sarah Johnson"
            },
            {
                id: 2,
                title: "5 Nutritious First Foods to Introduce",
                category: "nutrition",
                excerpt: "Start your baby's journey with these easy-to-digest, nutritious first foods.",
                image: "🥕",
                date: "2024-01-12",
                author: "Nutritionist Emma"
            },
            {
                id: 3,
                title: "Easy Homemade Baby Food Recipes",
                category: "recipes",
                excerpt: "Make delicious, healthy baby food at home with simple ingredients.",
                image: "👨‍🍳",
                date: "2024-01-10",
                author: "Chef Marcus"
            },
            {
                id: 4,
                title: "Choking Hazards: Foods to Avoid",
                category: "health",
                excerpt: "Complete guide to foods that pose choking risks and safe alternatives.",
                image: "⚠️",
                date: "2024-01-08",
                author: "Dr. Sarah Johnson"
            },
            {
                id: 5,
                title: "Understanding Allergies in Babies",
                category: "health",
                excerpt: "Recognize signs of food allergies and how to introduce allergenic foods safely.",
                image: "🔍",
                date: "2024-01-05",
                author: "Dr. Michael Chen"
            },
            {
                id: 6,
                title: "Breakfast Ideas for 9-12 Month Olds",
                category: "recipes",
                excerpt: "Quick, nutritious breakfast ideas your toddler will love.",
                image: "🥣",
                date: "2024-01-03",
                author: "Chef Marcus"
            },
            {
                id: 7,
                title: "Iron-Rich Foods for Healthy Development",
                category: "nutrition",
                excerpt: "Ensure your baby gets enough iron for optimal brain and physical development.",
                image: "💪",
                date: "2024-01-01",
                author: "Nutritionist Emma"
            },
            {
                id: 8,
                title: "From Purées to Finger Foods: Progression Guide",
                category: "development",
                excerpt: "Step-by-step guide to transitioning from purées to self-feeding.",
                image: "📈",
                date: "2023-12-29",
                author: "Dr. Sarah Johnson"
            }
        ];

        allArticles = sampleArticles;
        displayArticles('all');
    }

    function displayArticles(category) {
        const filtered = category === 'all' ?
            allArticles :
            allArticles.filter(a => a.category === category);

        const grid = document.getElementById('articles-grid');
        grid.innerHTML = filtered.map(article => `
            <article class="rounded-2xl border border-slate-200 bg-white overflow-hidden shadow-sm hover:shadow-md transition">
                <div class="h-40 bg-slate-100 flex items-center justify-center text-5xl">
                    ${article.image}
                </div>
                <div class="p-4">
                    <p class="text-xs font-semibold uppercase tracking-wider text-numnam-600 mb-2">${article.category.replace('-', ' ')}</p>
                    <h3 class="text-lg font-bold text-slate-900 mb-2 line-clamp-2">${article.title}</h3>
                    <p class="text-sm text-slate-600 mb-4">${article.excerpt}</p>
                    <div class="flex items-center justify-between text-xs text-slate-500">
                        <span>${article.author}</span>
                        <span>${new Date(article.date).toLocaleDateString()}</span>
                    </div>
                </div>
            </article>
        `).join('');
    }

    // Category filter
    document.querySelectorAll('.blog-filter').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.blog-filter').forEach(b => {
                b.classList.remove('active', 'border-numnam-600', 'bg-numnam-50', 'text-numnam-700');
                b.classList.add('border-slate-200', 'bg-white', 'text-slate-600');
            });

            this.classList.add('active', 'border-numnam-600', 'bg-numnam-50', 'text-numnam-700');
            this.classList.remove('border-slate-200', 'bg-white', 'text-slate-600');

            displayArticles(this.dataset.category);
        });
    });

    // Load articles on page load
    loadArticles();
</script>
@endsection
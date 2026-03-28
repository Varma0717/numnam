<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Coupon;
use App\Models\PricingPlan;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Admin user
        $adminUser = User::updateOrCreate(['email' => 'admin@numnam.com'], [
            'name'     => 'NumNam Admin',
            'password' => Hash::make(env('ADMIN_DEFAULT_PASSWORD', 'ChangeMe123!')),
            'is_admin' => true,
            'referral_code' => 'ADMINNN',
        ]);

        // Categories
        $allProducts = Category::updateOrCreate(['slug' => 'all-products'], ['name' => 'All Products', 'image' => '', 'is_active' => true]);
        $fruitie = Category::updateOrCreate(['slug' => 'fruitie-packs'], ['name' => 'Fruitie Packs', 'image' => '', 'is_active' => true]);
        $puffs8 = Category::updateOrCreate(['slug' => 'puffs-for-8month-babies'], ['name' => 'Puffs for 8+ Month Babies', 'image' => '', 'is_active' => true]);
        $puffs2 = Category::updateOrCreate(['slug' => 'puffs-for-2-year-old-babies'], ['name' => 'Puffs for 2+ Year Old Babies', 'image' => '', 'is_active' => true]);

        // Products aligned to website content map
        $products = [
            [
                'category_id' => $fruitie->id,
                'name'        => 'Berry Swush',
                'slug'        => 'berry-swush',
                'short_description' => 'Berry-rich smooth puree made for early weaning routines.',
                'description' => 'A berry-rich vegetable puree with clean ingredients and no refined sugar. Designed for tiny tummies and spoon-friendly feeding.',
                'ingredients' => 'Pumpkin, Apple, Pear, Berry blend, Lime Juice, Cold-pressed Rapeseed Oil',
                'age_group'   => '6M+',
                'type'        => 'puree',
                'price'       => 150.00,
                'sale_price'  => 135.00,
                'stock'       => 100,
                'is_featured' => true,
                'badges'      => ['Bestseller'],
                'nutrition_facts' => ['fiber' => 'High', 'sugar_added' => 'No', 'texture' => 'Smooth'],
            ],
            [
                'category_id' => $fruitie->id,
                'name'        => 'Appi Pooch',
                'slug'        => 'appi-pooch',
                'short_description' => 'Apple-forward veggie puree with naturally mild sweetness.',
                'description' => 'Apple and vegetable blended puree built for first tastes and parent-friendly convenience.',
                'ingredients' => 'Pumpkin, Apple, Pear, Mango, Lime Juice, Cold-pressed Rapeseed Oil',
                'age_group'   => '6M+',
                'type'        => 'puree',
                'price'       => 150.00,
                'sale_price'  => 135.00,
                'stock'       => 80,
                'is_featured' => true,
                'badges'      => ['Bestseller'],
                'nutrition_facts' => ['fiber' => 'Moderate', 'sugar_added' => 'No', 'texture' => 'Smooth'],
            ],
            [
                'category_id' => $fruitie->id,
                'name'        => 'Brocco Pop',
                'slug'        => 'brocco-pop',
                'short_description' => 'Broccoli-forward puree for stage-wise feeding progression.',
                'description' => 'A broccoli and fruit blend crafted to support palate expansion while maintaining smooth consistency.',
                'ingredients' => 'Broccoli, Apple, Pear, Mango, Lime Juice, Cold-pressed Rapeseed Oil',
                'age_group'   => '6M+',
                'type'        => 'puree',
                'price'       => 150.00,
                'sale_price'  => 135.00,
                'stock'       => 120,
                'is_featured' => true,
                'badges'      => ['New'],
                'nutrition_facts' => ['fiber' => 'High', 'sugar_added' => 'No', 'texture' => 'Smooth'],
            ],
            [
                'category_id' => $fruitie->id,
                'name'        => 'Mangy Chewy',
                'slug'        => 'mangy-chewy',
                'short_description' => 'Mango and veggie puree with balanced tang for flavor learning.',
                'description' => 'Tangy mango-led puree that helps expand flavor acceptance without additives.',
                'ingredients' => 'Mango, Carrot, Apple, Pear, Lime Juice, Cold-pressed Rapeseed Oil',
                'age_group'   => '6M+',
                'type'        => 'puree',
                'price'       => 150.00,
                'sale_price'  => 135.00,
                'stock'       => 90,
                'is_featured' => true,
                'badges'      => [],
                'nutrition_facts' => ['fiber' => 'Moderate', 'sugar_added' => 'No', 'texture' => 'Smooth'],
            ],
            [
                'category_id' => $puffs8->id,
                'name' => 'Banani Bubbles',
                'slug' => 'banani-bubbles',
                'short_description' => 'Banana and spinach puffs that melt quickly for little hands.',
                'description' => 'Soft baked puffs combining banana, spinach, and sprouted millet for clean snacking.',
                'ingredients' => 'Banana, Spinach, Sprouted Millet, Chickpea Flour',
                'age_group' => '8M+',
                'type' => 'puffs',
                'price' => 30.00,
                'sale_price' => null,
                'stock' => 0,
                'is_featured' => false,
                'badges' => ['Coming Soon'],
                'nutrition_facts' => ['protein' => '13%', 'sugar_added' => 'No', 'baked' => 'Yes'],
            ],
            [
                'category_id' => $puffs8->id,
                'name' => 'Jeery Pumpos',
                'slug' => 'jeery-pumpos',
                'short_description' => 'Pumpkin and apple puffs with cumin for gentle savory notes.',
                'description' => 'Heart-shaped puffs with a mild spice profile and toddler-safe crunch.',
                'ingredients' => 'Pumpkin, Apple, Cumin, Millet Flour',
                'age_group' => '8M+',
                'type' => 'puffs',
                'price' => 30.00,
                'sale_price' => null,
                'stock' => 0,
                'is_featured' => false,
                'badges' => ['Coming Soon'],
                'nutrition_facts' => ['protein' => '12%', 'sugar_added' => 'No', 'baked' => 'Yes'],
            ],
            [
                'category_id' => $puffs8->id,
                'name' => 'Mangy Munchos',
                'slug' => 'mangy-munchos',
                'short_description' => 'Tangy mango-carrot ring puffs made for active nibblers.',
                'description' => 'Flavor-forward ring puffs with fruit and veggie blend for exploratory eaters.',
                'ingredients' => 'Mango, Carrot, Sprouted Grains, Chickpea Flour',
                'age_group' => '8M+',
                'type' => 'puffs',
                'price' => 30.00,
                'sale_price' => null,
                'stock' => 0,
                'is_featured' => false,
                'badges' => ['Coming Soon'],
                'nutrition_facts' => ['protein' => '12%', 'sugar_added' => 'No', 'baked' => 'Yes'],
            ],
            [
                'category_id' => $puffs8->id,
                'name' => 'Appu Puffies',
                'slug' => 'appu-puffies',
                'short_description' => 'Soft star-shaped puffs with fruit-veg and super grain blend.',
                'description' => 'A toddler-friendly puff designed for self-feeding and clean ingredient snacking.',
                'ingredients' => 'Apple, Pear, Veggie Blend, Super Grain Mix',
                'age_group' => '8M+',
                'type' => 'puffs',
                'price' => 30.00,
                'sale_price' => null,
                'stock' => 0,
                'is_featured' => false,
                'badges' => ['Coming Soon'],
                'nutrition_facts' => ['protein' => '11%', 'sugar_added' => 'No', 'baked' => 'Yes'],
            ],
        ];

        foreach ($products as $data) {
            Product::updateOrCreate(['slug' => $data['slug']], array_merge($data, [
                'is_active' => true,
                'status' => 'published',
                'published_at' => now(),
                'category_id' => $data['category_id'] ?? $allProducts->id,
            ]));
        }

        $plans = [
            [
                'name' => 'NumNam Puree Saver - 3 Month Value',
                'slug' => 'numnam-puree-saver-3-month-value',
                'description' => 'Get 4 assorted NumNam purees every week. Designed as a 3-month value plan with 10% savings. Flexible, cancel anytime.',
                'price' => 486,
                'duration' => 'Valid for 12 weeks',
                'billing_cycle' => 'weekly',
                'features' => ['Best Value', '4 assorted purees every week', '10% savings'],
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'NumNam Puree Smart - 6 Month Value',
                'slug' => 'numnam-puree-smart-6-month-value',
                'description' => '4 packs of our doctor-designed purees delivered every month. Optimized for 6-month consistent nutrition with 15% savings. Cancel anytime.',
                'price' => 459,
                'duration' => 'Valid for 24 weeks',
                'billing_cycle' => 'weekly',
                'features' => ['Best Value', '4 purees every week', '15% savings'],
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'NumNam Puree Pro - 12 Month Value',
                'slug' => 'numnam-puree-pro-12-month-value',
                'description' => 'Weekly box of 4 NumNam purees for year-round healthy eating habits. Best value with 20% savings. You can cancel anytime.',
                'price' => 432,
                'duration' => 'Valid for 48 weeks',
                'billing_cycle' => 'weekly',
                'features' => ['Best Value', '4 purees every week', '20% savings'],
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'NumNam Puffs Saver - 3 Month Value',
                'slug' => 'numnam-puffs-saver-3-month-value',
                'description' => '16 packs of multi-grain puffs every month. Ideal starter plan with 10% savings over 3 months. Cancel anytime.',
                'price' => 432,
                'duration' => 'Valid for 3 months',
                'billing_cycle' => 'monthly',
                'features' => ['Best Value', '16 puffs packs every month', '10% savings'],
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'NumNam Puffs Pro - 12 Month Value',
                'slug' => 'numnam-puffs-pro-12-month-value',
                'description' => 'Lock in a year of healthy snacking with 16 puffs packs every month. Maximum 20% savings. Cancel anytime.',
                'price' => 384,
                'duration' => 'Valid for 12 months',
                'billing_cycle' => 'monthly',
                'features' => ['Best Value', '16 puffs packs every month', '20% savings'],
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'NumNam Puffs Smart - 6 Month Value',
                'slug' => 'numnam-puffs-smart-6-month-value',
                'description' => 'Monthly delivery of 16 wholesome puffs packs. Great for 6-month routines with 15% savings. Cancel anytime.',
                'price' => 408,
                'duration' => 'Valid for 6 months',
                'billing_cycle' => 'monthly',
                'features' => ['Best Value', '16 puffs packs every month', '15% savings'],
                'sort_order' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($plans as $plan) {
            PricingPlan::updateOrCreate(['slug' => $plan['slug']], $plan);
        }

        $blogCategory = BlogCategory::updateOrCreate(
            ['slug' => 'parents-corner'],
            ['name' => 'Parents Corner', 'description' => 'Nutrition and feeding guidance for parents.']
        );

        $posts = [
            [
                'title' => 'How to Feed Babies: Starting Weaning with Carrot Puree at 6 Months',
                'slug' => 'how-to-feed-babies-starting-weaning-with-carrot-puree-at-6-months',
                'excerpt' => 'A practical first-weaning guide with preparation and feeding tips for carrot puree.',
            ],
            [
                'title' => 'Top Online Baby Food Stores for Your Little One in India',
                'slug' => 'top-online-baby-food-stores-for-your-little-one-in-india',
                'excerpt' => 'How to evaluate online baby food stores for safety, transparency, and convenience.',
            ],
            [
                'title' => 'Discover the Benefits of NumNam: Boost Your Baby\'s Health Naturally',
                'slug' => 'discover-the-benefits-of-numnam-boost-your-babys-health-naturally',
                'excerpt' => 'A breakdown of nutrient density, ingredient transparency, and age-friendly options.',
            ],
            [
                'title' => 'Understanding the Nutritional Benefits of NumNam Nutrition Facts',
                'slug' => 'understanding-the-nutritional-benefits-of-numnam-nutrition-facts',
                'excerpt' => 'How to read nutrition labels and build a balanced feeding rhythm for your child.',
            ],
            [
                'title' => 'Discover the Amazing NumNam Baby Food Benefits for Your Little One',
                'slug' => 'discover-the-amazing-numnam-baby-food-benefits-for-your-little-one',
                'excerpt' => 'Why clean-label baby foods make transition stages easier for both babies and parents.',
            ],
            [
                'title' => 'Three Top Platforms for Baby Food Online: Making Mealtime Joyful and Easy',
                'slug' => 'three-top-platforms-for-baby-food-online-making-mealtime-joyful-and-easy',
                'excerpt' => 'A practical comparison of online shopping channels for infant and toddler food.',
            ],
            [
                'title' => 'Delicious and Nutritious Toddler Friendly Recipes to Try Today',
                'slug' => 'delicious-and-nutritious-toddler-friendly-recipes-to-try-today',
                'excerpt' => 'Easy recipes and prep tips to keep toddler mealtime varied and enjoyable.',
            ],
        ];

        foreach ($posts as $post) {
            Blog::updateOrCreate(
                ['slug' => $post['slug']],
                [
                    'blog_category_id' => $blogCategory->id,
                    'author_id' => $adminUser->id,
                    'title' => $post['title'],
                    'excerpt' => $post['excerpt'],
                    'content' => '<p>' . $post['excerpt'] . ' This guide in Parents Corner walks through practical nutrition choices, feeding progression, and simple kitchen-ready methods.</p><h2>What Parents Should Focus On</h2><ul><li>Ingredient transparency</li><li>Stage-wise texture progression</li><li>Consistent feeding schedule</li></ul><p>Always consult your pediatrician for personalized feeding decisions.</p>',
                    'status' => 'published',
                    'published_at' => now()->subDays(rand(1, 90)),
                    'meta_title' => $post['title'],
                    'meta_description' => $post['excerpt'],
                ]
            );
        }

        SiteSetting::updateOrCreate(
            ['key' => 'hero_title'],
            ['value' => 'Vegetable-rich baby food inspired by parent reality and pediatric standards.', 'type' => 'string', 'group' => 'homepage', 'is_public' => true, 'autoload' => true]
        );
        SiteSetting::updateOrCreate(
            ['key' => 'hero_subtitle'],
            ['value' => 'Explore purees, puffs, subscriptions, recipes, and referral rewards in one seamless storefront.', 'type' => 'string', 'group' => 'homepage', 'is_public' => true, 'autoload' => true]
        );

        Coupon::updateOrCreate(['code' => 'WELCOME10'], [
            'type' => 'percent',
            'value' => 10,
            'min_subtotal' => 299,
            'usage_limit' => 10000,
            'is_active' => true,
        ]);
    }
}

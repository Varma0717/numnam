<?php

namespace Database\Seeders;

use App\Models\CommunityRoom;
use App\Models\NumNamRecipe;
use App\Models\NumNamProduct;
use Illuminate\Database\Seeder;

class NumNamSeeder extends Seeder
{
    public function run(): void
    {
        // Create Community Rooms
        $rooms = [
            [
                'slug' => 'general',
                'name' => 'General Chat 💬',
                'description' => 'All things weaning — ask anything!',
                'icon' => '💬',
                'color' => '#6BA375',
                'display_order' => 1,
            ],
            [
                'slug' => 'poop',
                'name' => 'Poop & Gut Health 💩',
                'description' => 'No judgment — the poop chat we all need',
                'icon' => '💩',
                'color' => '#E8835A',
                'display_order' => 2,
            ],
            [
                'slug' => 'milk',
                'name' => 'Milk & Fussy Eaters 🍼',
                'description' => 'When baby refuses food or drops milk',
                'icon' => '🍼',
                'color' => '#7AB8CC',
                'display_order' => 3,
            ],
            [
                'slug' => 'india',
                'name' => 'Weaning in India 🇮🇳',
                'description' => 'Desi recipes, climate tips & local advice',
                'icon' => '🍛',
                'color' => '#C5B8D9',
                'display_order' => 4,
            ],
        ];

        foreach ($rooms as $room) {
            CommunityRoom::updateOrCreate(['slug' => $room['slug']], $room);
        }

        // Create NumNam Recipes
        $recipes = [
            [
                'emoji' => '🥕',
                'name' => 'Carrot & Ginger Purée',
                'description' => 'Rich in beta-carotene. Perfect first food.',
                'min_age_months' => 6,
                'texture' => 'Smooth purée',
                'food_type' => 'veggie',
                'hearts_count' => 42,
                'is_featured' => true,
            ],
            [
                'emoji' => '🥦',
                'name' => 'Broccoli & Apple Mash',
                'description' => 'Bitter-sweet combo for palate tuning.',
                'min_age_months' => 7,
                'texture' => 'Thick purée',
                'food_type' => 'veggie',
                'hearts_count' => 38,
            ],
            [
                'emoji' => '🍠',
                'name' => 'Sweet Potato & Coconut',
                'description' => 'Healthy fat + complex carbs. Soothing.',
                'min_age_months' => 6,
                'texture' => 'Smooth purée',
                'food_type' => 'veggie',
                'hearts_count' => 61,
                'is_featured' => true,
            ],
            [
                'emoji' => '🍌',
                'name' => 'Banana & Oat Porridge',
                'description' => 'Natural binder — great for loose stools.',
                'min_age_months' => 7,
                'texture' => 'Mashed',
                'food_type' => 'grain',
                'hearts_count' => 55,
            ],
            [
                'emoji' => '🫘',
                'name' => 'Lentil & Spinach Dhal',
                'description' => 'Iron-rich. Perfect for Indian weaning.',
                'min_age_months' => 7,
                'texture' => 'Thick purée',
                'food_type' => 'protein',
                'hearts_count' => 66,
                'is_featured' => true,
            ],
        ];

        foreach ($recipes as $recipe) {
            NumNamRecipe::updateOrCreate(['name' => $recipe['name']], $recipe);
        }

        // Create NumNam Products
        $products = [
            [
                'emoji' => '🥭',
                'name' => 'Mango Chewy',
                'description' => 'Mango purée — rich in Vitamin A & C. Perfect first fruit for 6+ months.',
                'category' => 'purées',
                'price' => 299,
                'badge_type' => 'new',
                'badge_label' => 'Stage 1 · 6m+',
                'stage' => 1,
                'display_order' => 1,
            ],
            [
                'emoji' => '🥦',
                'name' => 'Brocco Pop',
                'description' => 'Broccoli purée — iron-rich, European veggie-forward weaning.',
                'category' => 'purées',
                'price' => 299,
                'badge_type' => 'hot',
                'badge_label' => 'Stage 1 · 6m+',
                'stage' => 1,
                'display_order' => 2,
            ],
            [
                'emoji' => '🍳',
                'name' => 'Tikka Puffies',
                'description' => 'Mild tikka-spiced rice puffs — age-appropriate Indian flavours.',
                'category' => 'snacks',
                'price' => 249,
                'badge_type' => 'hot',
                'badge_label' => 'Stage 2 · 8m+',
                'stage' => 2,
                'display_order' => 3,
            ],
            [
                'emoji' => '🎁',
                'name' => 'First Taste Kit',
                'description' => 'Trial pack — 1 of each Stage 1 purée. Try before you subscribe.',
                'category' => 'bundle',
                'price' => 99,
                'badge_type' => 'hot',
                'badge_label' => 'Best starter',
                'stage' => 1,
                'display_order' => 4,
            ],
            [
                'emoji' => '🏫',
                'name' => 'NumNam Experience Center',
                'description' => 'Hyderabad\'s first toddler destination — age-appropriate play zones.',
                'category' => 'experience',
                'price' => 0,
                'badge_type' => 'popular',
                'badge_label' => 'Coming soon',
                'stage' => 0,
                'display_order' => 5,
            ],
        ];

        foreach ($products as $product) {
            NumNamProduct::updateOrCreate(['name' => $product['name']], $product);
        }
    }
}

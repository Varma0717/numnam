<?php

namespace App\Support;

class HomepageSectionBlueprint
{
    public const TYPES = [
        'hero_banner',
        'about_brand',
        'product_highlights',
        'nutrition_benefits',
        'pricing_plans',
        'testimonials',
        'call_to_action',
        'newsletter_signup',
    ];

    public static function defaults(): array
    {
        return [
            'hero_banner' => [
                'heading' => 'Nourish Tiny Tummies with NumNam',
                'subheading' => 'Clean, delicious nutrition for every growth stage.',
                'background_image' => null,
                'primary_cta_label' => 'Shop Now',
                'primary_cta_url' => '/products',
                'secondary_cta_label' => 'Learn More',
                'secondary_cta_url' => '/about',
            ],
            'about_brand' => [
                'heading' => 'Built by Parents, Backed by Nutrition',
                'body' => 'NumNam crafts nutrient-dense meals designed for growing kids.',
                'image' => null,
                'highlights' => [
                    'No artificial additives',
                    'Fresh ingredients',
                    'Pediatrician-friendly recipes',
                ],
            ],
            'product_highlights' => [
                'heading' => 'Featured Products',
                'product_ids' => [],
                'limit' => 6,
            ],
            'nutrition_benefits' => [
                'heading' => 'Why Nutrition Matters',
                'items' => [
                    'Supports healthy growth and immunity',
                    'Balanced macros and micronutrients',
                    'Made for infant and toddler needs',
                ],
            ],
            'pricing_plans' => [
                'heading' => 'Flexible Plans for Every Family',
                'plan_ids' => [],
            ],
            'testimonials' => [
                'heading' => 'What Parents Say',
                'items' => [
                    [
                        'quote' => 'Our little one loves every flavor.',
                        'name' => 'Ava M.',
                        'role' => 'Parent',
                    ],
                ],
            ],
            'call_to_action' => [
                'heading' => 'Ready to Start Healthy Eating?',
                'text' => 'Join thousands of parents choosing NumNam.',
                'button_label' => 'Start Today',
                'button_url' => '/products',
            ],
            'newsletter_signup' => [
                'heading' => 'Get Feeding Tips and Offers',
                'text' => 'Subscribe for updates, recipes, and exclusive deals.',
                'placeholder' => 'Enter your email',
                'button_label' => 'Subscribe',
                'success_message' => 'Thanks for subscribing!',
            ],
        ];
    }

    public static function normalize(string $sectionType, ?array $data): array
    {
        $defaults = self::defaults()[$sectionType] ?? [];

        return array_replace_recursive($defaults, $data ?? []);
    }
}

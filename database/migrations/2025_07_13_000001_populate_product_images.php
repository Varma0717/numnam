<?php

use App\Models\Product;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $appUrl = rtrim(config('app.url'), '/');

        $imageMap = [
            'berry-swush' => [
                'image'   => $appUrl . '/assets/images/Purees/berry%20swush%201.png',
                'gallery' => [
                    $appUrl . '/assets/images/Purees/berry%20swush%202.png',
                    $appUrl . '/assets/images/Purees/berry%20swush%203.png',
                ],
            ],
            'appi-pooch' => [
                'image'   => $appUrl . '/assets/images/Purees/appi%20pooch%201.png',
                'gallery' => [
                    $appUrl . '/assets/images/Purees/appi%20pooch%202.png',
                    $appUrl . '/assets/images/Purees/appi%20pooch%203.png',
                ],
            ],
            'brocco-pop' => [
                'image'   => $appUrl . '/assets/images/Purees/brocco%20pop%201.png',
                'gallery' => [
                    $appUrl . '/assets/images/Purees/brocco%20pop%202.png',
                    $appUrl . '/assets/images/Purees/brocco%20pop%203.png',
                ],
            ],
            'mangy-chewy' => [
                'image'   => $appUrl . '/assets/images/Purees/mangy%20chewy%201.png',
                'gallery' => [
                    $appUrl . '/assets/images/Purees/mangy%20chewy%202.png',
                    $appUrl . '/assets/images/Purees/mangy%20chewy%203.png',
                ],
            ],
            'cheezy-bubbles' => [
                'image'   => $appUrl . '/assets/images/Puffs/Cheezy%20Bubbles/front.jpg',
                'gallery' => [
                    $appUrl . '/assets/images/Puffs/Cheezy%20Bubbles/1.jpg',
                    $appUrl . '/assets/images/Puffs/Cheezy%20Bubbles/2.jpg',
                    $appUrl . '/assets/images/Puffs/Cheezy%20Bubbles/3.jpg',
                    $appUrl . '/assets/images/Puffs/Cheezy%20Bubbles/4.jpg',
                ],
            ],
            'tomaty-pumpos' => [
                'image'   => $appUrl . '/assets/images/Puffs/Tomaty%20Pumpos/front.jpg',
                'gallery' => [
                    $appUrl . '/assets/images/Puffs/Tomaty%20Pumpos/3.png',
                    $appUrl . '/assets/images/Puffs/Tomaty%20Pumpos/4.png',
                    $appUrl . '/assets/images/Puffs/Tomaty%20Pumpos/5.png',
                    $appUrl . '/assets/images/Puffs/Tomaty%20Pumpos/6.jpg',
                ],
            ],
            'manchurian-munchos' => [
                'image'   => $appUrl . '/assets/images/Puffs/Manchurian%20Munchos/front.jpg',
                'gallery' => [
                    $appUrl . '/assets/images/Puffs/Manchurian%20Munchos/1.jpg',
                    $appUrl . '/assets/images/Puffs/Manchurian%20Munchos/2.jpg',
                    $appUrl . '/assets/images/Puffs/Manchurian%20Munchos/3.jpg',
                    $appUrl . '/assets/images/Puffs/Manchurian%20Munchos/4.png',
                ],
            ],
            'tikka-puffies' => [
                'image'   => $appUrl . '/assets/images/Puffs/Tikka%20Puffies/front.jpg',
                'gallery' => [
                    $appUrl . '/assets/images/Puffs/Tikka%20Puffies/1.png',
                    $appUrl . '/assets/images/Puffs/Tikka%20Puffies/2.png',
                    $appUrl . '/assets/images/Puffs/Tikka%20Puffies/3.png',
                    $appUrl . '/assets/images/Puffs/Tikka%20Puffies/4.jpg',
                ],
            ],
        ];

        foreach ($imageMap as $slug => $data) {
            Product::where('slug', $slug)->update([
                'image'   => $data['image'],
                'gallery' => json_encode($data['gallery']),
            ]);
        }
    }

    public function down(): void
    {
        Product::whereIn('slug', [
            'berry-swush', 'appi-pooch', 'brocco-pop', 'mangy-chewy',
            'cheezy-bubbles', 'tomaty-pumpos', 'manchurian-munchos', 'tikka-puffies',
        ])->update(['image' => null, 'gallery' => null]);
    }
};

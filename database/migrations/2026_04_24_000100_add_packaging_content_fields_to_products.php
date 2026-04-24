<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('products')) {
            return;
        }

        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'label_copy')) {
                $table->longText('label_copy')->nullable()->after('ingredients');
            }

            if (! Schema::hasColumn('products', 'specifications')) {
                $table->json('specifications')->nullable()->after('nutrition_info');
            }

            if (! Schema::hasColumn('products', 'storage_instructions')) {
                $table->text('storage_instructions')->nullable()->after('specifications');
            }

            if (! Schema::hasColumn('products', 'safety_advice')) {
                $table->text('safety_advice')->nullable()->after('storage_instructions');
            }

            if (! Schema::hasColumn('products', 'allergen_note')) {
                $table->text('allergen_note')->nullable()->after('safety_advice');
            }

            if (! Schema::hasColumn('products', 'customer_care')) {
                $table->json('customer_care')->nullable()->after('allergen_note');
            }

            if (! Schema::hasColumn('products', 'regulatory_info')) {
                $table->json('regulatory_info')->nullable()->after('customer_care');
            }

            if (! Schema::hasColumn('products', 'barcode_url')) {
                $table->string('barcode_url')->nullable()->after('regulatory_info');
            }

            if (! Schema::hasColumn('products', 'qr_code_url')) {
                $table->string('qr_code_url')->nullable()->after('barcode_url');
            }
        });

        $this->normalizeAssetPaths();
        $this->seedPackagingContent();
    }

    public function down(): void
    {
        if (! Schema::hasTable('products')) {
            return;
        }

        Schema::table('products', function (Blueprint $table) {
            $columns = [
                'label_copy',
                'specifications',
                'storage_instructions',
                'safety_advice',
                'allergen_note',
                'customer_care',
                'regulatory_info',
                'barcode_url',
                'qr_code_url',
            ];

            $existing = array_values(array_filter($columns, fn ($column) => Schema::hasColumn('products', $column)));

            if ($existing !== []) {
                $table->dropColumn($existing);
            }
        });
    }

    private function normalizeAssetPaths(): void
    {
        DB::table('products')
            ->select(['id', 'image', 'gallery'])
            ->orderBy('id')
            ->chunkById(50, function ($products) {
                foreach ($products as $product) {
                    $image = $this->stripAssetHost($product->image);
                    $gallery = $this->decodeJsonArray($product->gallery);
                    $normalizedGallery = array_values(array_filter(array_map(
                        fn ($path) => $this->stripAssetHost($path),
                        $gallery
                    )));

                    DB::table('products')
                        ->where('id', $product->id)
                        ->update([
                            'image' => $image,
                            'gallery' => $normalizedGallery === [] ? null : json_encode($normalizedGallery, JSON_UNESCAPED_SLASHES),
                        ]);
                }
            });
    }

    private function seedPackagingContent(): void
    {
        $commonPuffStorage = 'Keep it in a cool, dry place. Once opened, transfer to an airtight container and consume within 2-3 days.';
        $commonPuffSafety = 'When feeding a child, please ensure they are sitting down and are supervised to reduce the risk of choking.';
        $commonCustomerCare = [
            'email' => 'customercare@numnam.com',
            'phone' => '+91 9014252278',
            'label' => 'For queries or feedback',
        ];
        $commonRegulatoryInfo = [
            'marketed_by' => 'Smikudoo Pvt Ltd., Hyderabad',
            'fssai_license_number' => '13625011000859',
            'fssai_category' => 'SNACKS',
        ];

        $updates = [
            'manchurian-munchos' => [
                'label_copy' => 'A savory street-food vibe featuring green cabbage, carrot, and sweet potato in a baked multi-grain puff format.',
                'specifications' => [
                    'serving_size' => '20g',
                    'net_weight' => '20g',
                    'protein_per_100g' => '13.52g',
                    'energy_per_100g' => '402.8 kcal',
                    'product_format' => 'Baked savory puffs',
                ],
                'storage_instructions' => $commonPuffStorage,
                'safety_advice' => $commonPuffSafety,
                'allergen_note' => 'Made in a facility that processes wheat and soyabean. May contain trace elements.',
                'customer_care' => $commonCustomerCare,
                'regulatory_info' => $commonRegulatoryInfo,
            ],
            'tikka-puffies' => [
                'label_copy' => 'A mild roasted cauliflower-led puff with a sweet-smoky tikka profile designed for playful, easy snacking.',
                'specifications' => [
                    'serving_size' => '20g',
                    'net_weight' => '20g',
                    'product_format' => 'Baked savory puffs',
                    'primary_vegetable_notes' => ['cauliflower', 'carrot', 'sweet potato'],
                ],
                'storage_instructions' => $commonPuffStorage,
                'safety_advice' => $commonPuffSafety,
                'customer_care' => $commonCustomerCare,
                'regulatory_info' => $commonRegulatoryInfo,
            ],
            'tomaty-pumpos' => [
                'label_copy' => 'Heart-shaped puffs powered by pumpkin, carrot, and sweet potato with a tomato-forward flavor profile.',
                'specifications' => [
                    'serving_size' => '20g',
                    'net_weight' => '20g',
                    'product_format' => 'Baked savory puffs',
                    'primary_vegetable_notes' => ['pumpkin', 'carrot', 'sweet potato'],
                ],
                'storage_instructions' => $commonPuffStorage,
                'safety_advice' => $commonPuffSafety,
                'customer_care' => $commonCustomerCare,
                'regulatory_info' => $commonRegulatoryInfo,
            ],
            'cheezy-bubbles' => [
                'label_copy' => 'A creamy sweet-cheese-style puff paired with mango and spinach for a playful flavor toddlers enjoy.',
                'specifications' => [
                    'serving_size' => '20g',
                    'net_weight' => '20g',
                    'product_format' => 'Baked savory puffs',
                    'primary_vegetable_notes' => ['spinach', 'mango', 'sweet potato'],
                ],
                'storage_instructions' => $commonPuffStorage,
                'safety_advice' => $commonPuffSafety,
                'customer_care' => $commonCustomerCare,
                'regulatory_info' => $commonRegulatoryInfo,
            ],
        ];

        foreach ($updates as $slug => $data) {
            DB::table('products')
                ->where('slug', $slug)
                ->update([
                    'label_copy' => $data['label_copy'],
                    'specifications' => json_encode($data['specifications'], JSON_UNESCAPED_SLASHES),
                    'storage_instructions' => $data['storage_instructions'],
                    'safety_advice' => $data['safety_advice'],
                    'allergen_note' => $data['allergen_note'] ?? null,
                    'customer_care' => json_encode($data['customer_care'], JSON_UNESCAPED_SLASHES),
                    'regulatory_info' => json_encode($data['regulatory_info'], JSON_UNESCAPED_SLASHES),
                ]);
        }
    }

    private function stripAssetHost(?string $path): ?string
    {
        if (! is_string($path) || $path === '') {
            return $path;
        }

        $parts = parse_url($path);
        if (! is_array($parts) || ! isset($parts['path'])) {
            return $path;
        }

        if (str_contains($parts['path'], '/assets/')) {
            return ltrim(substr($parts['path'], strpos($parts['path'], '/assets/')), '/');
        }

        return $path;
    }

    private function decodeJsonArray(mixed $value): array
    {
        if (is_array($value)) {
            return $value;
        }

        if (! is_string($value) || $value === '') {
            return [];
        }

        $decoded = json_decode($value, true);

        return is_array($decoded) ? $decoded : [];
    }
};

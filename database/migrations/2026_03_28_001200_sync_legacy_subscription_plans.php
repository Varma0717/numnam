<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('pricing_plans')) {
            return;
        }

        $this->ensureWeeklyBillingCycleSupport();

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

        $knownSlugs = array_map(fn(array $plan) => $plan['slug'], $plans);
        $now = now();

        foreach ($plans as $plan) {
            $payload = [
                'name' => $plan['name'],
                'description' => $plan['description'],
                'price' => $plan['price'],
                'duration' => $plan['duration'],
                'billing_cycle' => $plan['billing_cycle'],
                'features' => json_encode($plan['features']),
                'sort_order' => $plan['sort_order'],
                'is_active' => $plan['is_active'],
                'updated_at' => $now,
            ];

            $existing = DB::table('pricing_plans')->where('slug', $plan['slug'])->first();
            if ($existing) {
                DB::table('pricing_plans')->where('id', $existing->id)->update($payload);
                continue;
            }

            DB::table('pricing_plans')->insert(array_merge($payload, [
                'slug' => $plan['slug'],
                'created_at' => $now,
            ]));
        }

        DB::table('pricing_plans')
            ->whereNotIn('slug', $knownSlugs)
            ->update([
                'is_active' => false,
                'updated_at' => $now,
            ]);
    }

    public function down(): void
    {
        if (! Schema::hasTable('pricing_plans')) {
            return;
        }

        DB::table('pricing_plans')
            ->whereIn('slug', [
                'numnam-puree-saver-3-month-value',
                'numnam-puree-smart-6-month-value',
                'numnam-puree-pro-12-month-value',
                'numnam-puffs-saver-3-month-value',
                'numnam-puffs-pro-12-month-value',
                'numnam-puffs-smart-6-month-value',
            ])
            ->update([
                'is_active' => false,
                'updated_at' => now(),
            ]);
    }

    private function ensureWeeklyBillingCycleSupport(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::statement(
            "ALTER TABLE pricing_plans MODIFY billing_cycle ENUM('weekly','monthly','quarterly','yearly','one_time') NOT NULL DEFAULT 'monthly'"
        );
    }
};

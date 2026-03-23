<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->extendProductsTable();
        $this->extendPricingPlansTable();
        $this->createPricingPlanProductsTable();
    }

    public function down(): void
    {
        if (Schema::hasTable('pricing_plan_products')) {
            Schema::dropIfExists('pricing_plan_products');
        }

        if (Schema::hasTable('pricing_plans')) {
            Schema::table('pricing_plans', function (Blueprint $table) {
                if (Schema::hasColumn('pricing_plans', 'duration')) {
                    $table->dropColumn('duration');
                }
            });
        }

        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (Schema::hasColumn('products', 'nutrition_info')) {
                    $table->dropColumn('nutrition_info');
                }
            });
        }
    }

    private function extendProductsTable(): void
    {
        if (!Schema::hasTable('products')) {
            return;
        }

        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'ingredients')) {
                $table->longText('ingredients')->nullable()->after('description');
            }

            if (!Schema::hasColumn('products', 'gallery')) {
                $table->json('gallery')->nullable()->after('image');
            }

            if (!Schema::hasColumn('products', 'nutrition_info')) {
                $table->json('nutrition_info')->nullable()->after('nutrition_facts');
            }
        });
    }

    private function extendPricingPlansTable(): void
    {
        if (!Schema::hasTable('pricing_plans')) {
            return;
        }

        Schema::table('pricing_plans', function (Blueprint $table) {
            if (!Schema::hasColumn('pricing_plans', 'duration')) {
                $table->string('duration', 100)->default('1 month')->after('price');
            }
        });
    }

    private function createPricingPlanProductsTable(): void
    {
        if (Schema::hasTable('pricing_plan_products')) {
            return;
        }

        Schema::create('pricing_plan_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pricing_plan_id')->constrained('pricing_plans')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->unsignedInteger('quantity')->default(1);
            $table->timestamps();

            $table->unique(['pricing_plan_id', 'product_id']);
        });
    }
};

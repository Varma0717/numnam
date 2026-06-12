<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('num_nam_recipes', function (Blueprint $table) {
            $table->id();
            $table->string('emoji');
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('min_age_months')->default(6);
            $table->string('texture');
            $table->enum('food_type', ['veggie', 'fruit', 'protein', 'grain', 'dairy', 'mixed']);
            $table->text('preparation')->nullable();
            $table->json('ingredients')->nullable();
            $table->text('notes')->nullable();
            $table->integer('hearts_count')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();

            $table->index('min_age_months');
            $table->index('food_type');
            $table->index('is_featured');
        });

        Schema::create('recipe_likes', function (Blueprint $table) {
            $table->unsignedBigInteger('recipe_id');
            $table->unsignedBigInteger('user_id');
            $table->primary(['recipe_id', 'user_id']);

            $table->foreign('recipe_id')->references('id')->on('num_nam_recipes')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('num_nam_products', function (Blueprint $table) {
            $table->id();
            $table->string('emoji');
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('category', ['purées', 'snacks', 'bundle', 'experience']);
            $table->decimal('price', 8, 2)->default(0);
            $table->enum('badge_type', ['new', 'hot', 'popular'])->nullable();
            $table->string('badge_label')->nullable();
            $table->integer('stage')->default(1);
            $table->boolean('is_active')->default(true);
            $table->integer('display_order')->default(0);
            $table->timestamps();

            $table->index('category');
            $table->index('is_active');
            $table->index('display_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('num_nam_products');
        Schema::dropIfExists('recipe_likes');
        Schema::dropIfExists('num_nam_recipes');
    }
};

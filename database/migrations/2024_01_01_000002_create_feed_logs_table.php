<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feed_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('baby_profile_id');
            $table->enum('type', ['milk', 'solid', 'water', 'poop']);
            $table->integer('volume_ml')->nullable();
            $table->enum('milk_type', ['breast', 'formula'])->nullable();
            $table->string('food_name')->nullable();
            $table->enum('food_type', ['veggie', 'fruit', 'protein', 'grain', 'dairy', 'mixed'])->nullable();
            $table->string('texture')->nullable();
            $table->enum('finish_level', ['all', 'most', 'half', 'few', 'floor', 'refused'])->nullable();
            $table->string('poop_type')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('logged_at')->useCurrent();
            $table->timestamps();

            $table->foreign('baby_profile_id')->references('id')->on('baby_profiles')->onDelete('cascade');
            $table->index(['baby_profile_id', 'logged_at']);
            $table->index(['type', 'logged_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feed_logs');
    }
};

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
            $table->foreignId('baby_profile_id')->constrained('baby_profiles')->onDelete('cascade');
            $table->enum('type', ['milk', 'solid', 'water', 'poop']);
            $table->integer('volume_ml')->nullable();
            $table->string('milk_type')->nullable();
            $table->string('food_name')->nullable();
            $table->string('texture')->nullable();
            $table->string('finish_level')->nullable();
            $table->string('poop_type')->nullable();
            $table->dateTime('logged_at')->default(now());
            $table->timestamps();

            $table->index('baby_profile_id');
            $table->index('logged_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feed_logs');
    }
};

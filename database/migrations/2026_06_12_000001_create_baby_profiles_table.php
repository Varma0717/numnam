<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('baby_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('baby_name')->nullable();
            $table->integer('age_months')->default(6);
            $table->decimal('weight_kg', 5, 2)->nullable();
            $table->enum('milk_type', ['breast', 'formula'])->default('formula');
            $table->timestamps();

            $table->unique(['user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('baby_profiles');
    }
};

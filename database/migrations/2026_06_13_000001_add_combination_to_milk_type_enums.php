<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Alter baby_profiles table
        Schema::table('baby_profiles', function (Blueprint $table) {
            $table->enum('milk_type', ['breast', 'formula', 'combination'])->default('formula')->change();
        });

        // Alter feed_logs table
        Schema::table('feed_logs', function (Blueprint $table) {
            $table->enum('milk_type', ['breast', 'formula', 'combination'])->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('baby_profiles', function (Blueprint $table) {
            $table->enum('milk_type', ['breast', 'formula'])->default('formula')->change();
        });

        Schema::table('feed_logs', function (Blueprint $table) {
            $table->enum('milk_type', ['breast', 'formula'])->nullable()->change();
        });
    }
};

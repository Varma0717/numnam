<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Alter baby_profiles table using raw SQL to avoid Doctrine requirement
        DB::statement("ALTER TABLE baby_profiles MODIFY COLUMN milk_type ENUM('breast', 'formula', 'combination') DEFAULT 'formula'");

        // Alter feed_logs table using raw SQL
        DB::statement("ALTER TABLE feed_logs MODIFY COLUMN milk_type ENUM('breast', 'formula', 'combination') NULL");
    }

    public function down(): void
    {
        // Revert baby_profiles table
        DB::statement("ALTER TABLE baby_profiles MODIFY COLUMN milk_type ENUM('breast', 'formula') DEFAULT 'formula'");

        // Revert feed_logs table
        DB::statement("ALTER TABLE feed_logs MODIFY COLUMN milk_type ENUM('breast', 'formula') NULL");
    }
};

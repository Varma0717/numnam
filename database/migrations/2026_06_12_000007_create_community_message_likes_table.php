<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('community_message_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('community_message_id')->constrained('community_messages')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['user_id', 'community_message_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('community_message_likes');
    }
};

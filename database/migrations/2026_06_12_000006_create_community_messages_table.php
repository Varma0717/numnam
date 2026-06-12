<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('community_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('community_room_id')->constrained('community_rooms')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('message');
            $table->integer('likes_count')->default(0);
            $table->timestamps();

            $table->index('community_room_id');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('community_messages');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add performance indexes to chat_messages
        Schema::table('chat_messages', function (Blueprint $table) {
            // Check if indexes don't already exist
            $table->index('room_id');
            $table->index(['room_id', 'created_at']);
        });

        // Add indexes to chat_message_likes for faster lookups
        if (Schema::hasTable('chat_message_likes')) {
            Schema::table('chat_message_likes', function (Blueprint $table) {
                // Prevent duplicate likes (unique constraint)
                if (!Schema::hasIndex('chat_message_likes', 'chat_message_likes_message_id_user_id_unique')) {
                    $table->unique(['message_id', 'user_id']);
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropIndex(['room_id']);
            $table->dropIndex(['room_id', 'created_at']);
        });

        if (Schema::hasTable('chat_message_likes')) {
            Schema::table('chat_message_likes', function (Blueprint $table) {
                $table->dropUnique(['message_id', 'user_id']);
            });
        }
    }
};

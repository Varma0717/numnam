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
        // Add performance indexes to chat_messages (if they don't exist)
        Schema::table('chat_messages', function (Blueprint $table) {
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $indexes = $sm->listTableIndexes('chat_messages');

            if (!isset($indexes['chat_messages_room_id_index'])) {
                $table->index('room_id');
            }

            if (!isset($indexes['chat_messages_room_id_created_at_index'])) {
                $table->index(['room_id', 'created_at']);
            }
        });

        // Add indexes to chat_message_likes for faster lookups
        if (Schema::hasTable('chat_message_likes')) {
            Schema::table('chat_message_likes', function (Blueprint $table) {
                $sm = Schema::getConnection()->getDoctrineSchemaManager();
                $indexes = $sm->listTableIndexes('chat_message_likes');

                // Prevent duplicate likes (unique constraint)
                if (!isset($indexes['chat_message_likes_message_id_user_id_unique'])) {
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

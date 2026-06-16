<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add indexes using raw SQL to avoid Doctrine issues
        DB::statement('ALTER TABLE chat_messages ADD INDEX IF NOT EXISTS chat_messages_room_id_index(room_id)');
        DB::statement('ALTER TABLE chat_messages ADD INDEX IF NOT EXISTS chat_messages_room_id_created_at_index(room_id, created_at)');

        // Add unique constraint for message likes if table exists
        if ($this->hasTable('chat_message_likes')) {
            DB::statement('ALTER TABLE chat_message_likes ADD UNIQUE INDEX IF NOT EXISTS chat_message_likes_message_id_user_id_unique(message_id, user_id)');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE chat_messages DROP INDEX IF EXISTS chat_messages_room_id_index');
        DB::statement('ALTER TABLE chat_messages DROP INDEX IF EXISTS chat_messages_room_id_created_at_index');

        if ($this->hasTable('chat_message_likes')) {
            DB::statement('ALTER TABLE chat_message_likes DROP INDEX IF EXISTS chat_message_likes_message_id_user_id_unique');
        }
    }

    /**
     * Check if table exists
     */
    private function hasTable($table): bool
    {
        return DB::getSchemaBuilder()->hasTable($table);
    }
};

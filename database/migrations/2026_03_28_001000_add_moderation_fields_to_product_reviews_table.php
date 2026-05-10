<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('product_reviews')) {
            return;
        }

        Schema::table('product_reviews', function (Blueprint $table) {
            if (! Schema::hasColumn('product_reviews', 'moderation_status')) {
                $table->string('moderation_status', 20)->default('pending')->after('is_approved');
                $table->index(['product_id', 'moderation_status', 'created_at'], 'product_reviews_product_status_created_idx');
            }

            if (! Schema::hasColumn('product_reviews', 'moderated_at')) {
                $table->timestamp('moderated_at')->nullable()->after('moderation_status');
            }
        });

        DB::table('product_reviews')
            ->where('is_approved', true)
            ->update(['moderation_status' => 'approved']);
    }

    public function down(): void
    {
        if (! Schema::hasTable('product_reviews')) {
            return;
        }

        Schema::table('product_reviews', function (Blueprint $table) {
            if (Schema::hasColumn('product_reviews', 'moderated_at')) {
                $table->dropColumn('moderated_at');
            }

            if (Schema::hasColumn('product_reviews', 'moderation_status')) {
                $table->dropIndex('product_reviews_product_status_created_idx');
                $table->dropColumn('moderation_status');
            }
        });
    }
};

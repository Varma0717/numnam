<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('page_sections')) {
            return;
        }

        Schema::table('page_sections', function (Blueprint $table) {
            if (!Schema::hasColumn('page_sections', 'section_type')) {
                $table->string('section_type')->nullable()->after('section_key');
            }

            if (!Schema::hasColumn('page_sections', 'data')) {
                $table->json('data')->nullable()->after('settings');
            }
        });

        DB::table('page_sections')
            ->whereNull('section_type')
            ->update(['section_type' => DB::raw('section_key')]);

        Schema::table('page_sections', function (Blueprint $table) {
            if (!Schema::hasColumn('page_sections', 'section_type')) {
                return;
            }

            $table->index(['page_id', 'section_type'], 'page_sections_page_type_idx');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('page_sections')) {
            return;
        }

        Schema::table('page_sections', function (Blueprint $table) {
            try {
                $table->dropIndex('page_sections_page_type_idx');
            } catch (Throwable $e) {
                // Ignore if index does not exist.
            }

            if (Schema::hasColumn('page_sections', 'data')) {
                $table->dropColumn('data');
            }

            if (Schema::hasColumn('page_sections', 'section_type')) {
                $table->dropColumn('section_type');
            }
        });
    }
};

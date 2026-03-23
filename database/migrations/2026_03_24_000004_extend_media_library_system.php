<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->extendMediaLibraryTable();
        $this->createMediaLibraryLinksTable();
    }

    public function down(): void
    {
        Schema::dropIfExists('media_library_links');

        if (Schema::hasTable('media_library')) {
            Schema::table('media_library', function (Blueprint $table) {
                if (Schema::hasColumn('media_library', 'folder')) {
                    $table->dropColumn('folder');
                }

                if (Schema::hasColumn('media_library', 'collection')) {
                    $table->dropColumn('collection');
                }

                if (Schema::hasColumn('media_library', 'is_public')) {
                    $table->dropColumn('is_public');
                }
            });
        }
    }

    private function extendMediaLibraryTable(): void
    {
        if (!Schema::hasTable('media_library')) {
            return;
        }

        Schema::table('media_library', function (Blueprint $table) {
            if (!Schema::hasColumn('media_library', 'folder')) {
                $table->string('folder')->default('general')->after('disk');
            }

            if (!Schema::hasColumn('media_library', 'collection')) {
                $table->string('collection')->default('default')->after('folder');
            }

            if (!Schema::hasColumn('media_library', 'is_public')) {
                $table->boolean('is_public')->default(true)->after('metadata');
            }

            $table->index(['folder', 'collection'], 'media_library_folder_collection_idx');
        });
    }

    private function createMediaLibraryLinksTable(): void
    {
        if (Schema::hasTable('media_library_links')) {
            return;
        }

        Schema::create('media_library_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('media_library_id')->constrained('media_library')->cascadeOnDelete();
            $table->enum('entity_type', ['page', 'product']);
            $table->unsignedBigInteger('entity_id');
            $table->string('role')->default('gallery');
            $table->timestamps();

            $table->index(['entity_type', 'entity_id'], 'media_links_entity_idx');
            $table->unique(['media_library_id', 'entity_type', 'entity_id', 'role'], 'media_links_unique_idx');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('contact_messages')) {
            Schema::create('contact_messages', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email');
                $table->string('phone')->nullable();
                $table->string('subject')->nullable();
                $table->longText('message');
                $table->enum('status', ['new', 'read', 'replied', 'archived'])->default('new');
                $table->string('source')->default('website');
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->timestamp('read_at')->nullable();
                $table->timestamp('replied_at')->nullable();
                $table->timestamp('notified_at')->nullable();
                $table->timestamps();

                $table->index(['status', 'created_at']);
            });

            return;
        }

        Schema::table('contact_messages', function (Blueprint $table) {
            if (!Schema::hasColumn('contact_messages', 'source')) {
                $table->string('source')->default('website')->after('status');
            }

            if (!Schema::hasColumn('contact_messages', 'notified_at')) {
                $table->timestamp('notified_at')->nullable()->after('replied_at');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('contact_messages')) {
            return;
        }

        Schema::table('contact_messages', function (Blueprint $table) {
            if (Schema::hasColumn('contact_messages', 'notified_at')) {
                $table->dropColumn('notified_at');
            }

            if (Schema::hasColumn('contact_messages', 'source')) {
                $table->dropColumn('source');
            }
        });
    }
};

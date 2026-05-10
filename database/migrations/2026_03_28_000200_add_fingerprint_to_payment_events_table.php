<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('payment_events')) {
            return;
        }

        Schema::table('payment_events', function (Blueprint $table) {
            if (! Schema::hasColumn('payment_events', 'fingerprint')) {
                $table->string('fingerprint', 64)->nullable()->after('external_reference');
                $table->unique('fingerprint');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('payment_events')) {
            return;
        }

        Schema::table('payment_events', function (Blueprint $table) {
            if (Schema::hasColumn('payment_events', 'fingerprint')) {
                $table->dropUnique(['fingerprint']);
                $table->dropColumn('fingerprint');
            }
        });
    }
};

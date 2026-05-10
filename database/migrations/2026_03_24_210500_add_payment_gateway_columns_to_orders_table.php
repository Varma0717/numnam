<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'payment_gateway')) {
                $table->string('payment_gateway', 40)->nullable()->after('payment_method');
            }

            if (! Schema::hasColumn('orders', 'payment_meta')) {
                $table->json('payment_meta')->nullable()->after('payment_reference');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'payment_meta')) {
                $table->dropColumn('payment_meta');
            }

            if (Schema::hasColumn('orders', 'payment_gateway')) {
                $table->dropColumn('payment_gateway');
            }
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('subscriptions')) {
            return;
        }

        Schema::table('subscriptions', function (Blueprint $table) {
            if (! Schema::hasColumn('subscriptions', 'billing_retry_count')) {
                $table->unsignedTinyInteger('billing_retry_count')->default(0)->after('next_billing_date');
            }

            if (! Schema::hasColumn('subscriptions', 'last_billing_attempt_at')) {
                $table->timestamp('last_billing_attempt_at')->nullable()->after('billing_retry_count');
            }

            if (! Schema::hasColumn('subscriptions', 'last_billing_error')) {
                $table->string('last_billing_error', 255)->nullable()->after('last_billing_attempt_at');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('subscriptions')) {
            return;
        }

        Schema::table('subscriptions', function (Blueprint $table) {
            $columns = [];

            if (Schema::hasColumn('subscriptions', 'last_billing_error')) {
                $columns[] = 'last_billing_error';
            }

            if (Schema::hasColumn('subscriptions', 'last_billing_attempt_at')) {
                $columns[] = 'last_billing_attempt_at';
            }

            if (Schema::hasColumn('subscriptions', 'billing_retry_count')) {
                $columns[] = 'billing_retry_count';
            }

            if (! empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};

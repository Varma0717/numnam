<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('payment_events')) {
            return;
        }

        Schema::create('payment_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->string('gateway', 40);
            $table->string('event_type', 120);
            $table->string('external_reference', 191)->nullable();
            $table->string('status', 40)->nullable();
            $table->unsignedDecimal('amount', 10, 2)->nullable();
            $table->string('currency', 10)->nullable();
            $table->boolean('signature_valid')->default(false);
            $table->string('note')->nullable();
            $table->json('payload')->nullable();
            $table->timestamps();

            $table->index(['gateway', 'external_reference']);
            $table->index(['order_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_events');
    }
};

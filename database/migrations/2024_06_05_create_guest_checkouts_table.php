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
        Schema::create('guest_checkouts', function (Blueprint $table) {
            $table->id();
            $table->string('email')->index();
            $table->string('phone')->nullable();
            $table->string('name');
            $table->text('address')->nullable();
            $table->string('razorpay_order_id')->nullable()->unique();
            $table->string('razorpay_payment_id')->nullable()->unique();
            $table->string('payment_status')->default('pending'); // pending, completed, failed
            $table->json('payment_meta')->nullable();
            $table->string('source')->nullable(); // tools_tracker, etc
            $table->timestamps();

            $table->index(['email', 'phone']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guest_checkouts');
    }
};

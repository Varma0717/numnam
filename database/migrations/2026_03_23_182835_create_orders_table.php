<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('order_number')->unique();
            $table->enum('status', ['pending','processing','shipped','delivered','cancelled','refunded'])->default('pending');
            $table->unsignedDecimal('subtotal', 10, 2);
            $table->unsignedDecimal('discount', 10, 2)->default(0);
            $table->unsignedDecimal('shipping_fee', 10, 2)->default(0);
            $table->unsignedDecimal('total', 10, 2);
            $table->string('coupon_code')->nullable();
            $table->enum('payment_method', ['upi','card','cod','netbanking'])->default('cod');
            $table->enum('payment_status', ['pending','paid','failed','refunded'])->default('pending');
            $table->string('payment_reference')->nullable();
            // Shipping address (snapshot at order time)
            $table->string('ship_name');
            $table->string('ship_phone');
            $table->string('ship_address');
            $table->string('ship_city');
            $table->string('ship_state');
            $table->string('ship_pincode');
            $table->string('tracking_number')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};

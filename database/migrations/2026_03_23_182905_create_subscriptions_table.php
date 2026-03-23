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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('plan_name');   // e.g. Puree Saver, Puffs Pro
            $table->string('plan_type');   // puree | puffs
            $table->string('duration');    // 3M, 6M, 12M
            $table->string('frequency');   // weekly | monthly
            $table->unsignedDecimal('price_per_cycle', 8, 2);
            $table->unsignedInteger('discount_percent')->default(0);
            $table->enum('status', ['active','paused','cancelled','expired'])->default('active');
            $table->date('next_billing_date')->nullable();
            $table->date('ends_at')->nullable();
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
        Schema::dropIfExists('subscriptions');
    }
};

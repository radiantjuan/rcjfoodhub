<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderPromoCodeSetupTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('order_promo_code_setup', function (Blueprint $table) {
            $table->id();
            $table->string('promo_code');
            $table->unsignedBigInteger('promo_code_id')->nullable()->index();
            $table->unsignedBigInteger('order_id')->index();
            $table->json('promo_code_coverage');
            $table->json('promo_code_exceptions');
            $table->float('new_amount_to_be_paid');
            $table->float('promo_code_total_discount');
            $table->float('previous_amount_to_be_paid');
            $table->foreign('order_id')->references('id')->on('orders');
            $table->foreign('promo_code_id')->references('id')->on('promo_codes')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('order_promo_code_setup');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBasketItemsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('basket_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('franchise_id')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->json('cart_total')->nullable();
            $table->json('cart_items')->nullable();
            $table->json('promo_code_setup')->nullable();
            $table->boolean('is_active');
            $table->timestamps();
            $table->foreign('franchise_id')->references('id')->on('franchisees');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('basket_items');
    }
}

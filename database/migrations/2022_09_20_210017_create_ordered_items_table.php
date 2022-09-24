<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderedItemsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('ordered_items', function (Blueprint $table) {
            $table->id();
            $table->integer('grams')->nullable();
            $table->float('price');
            $table->string('name');
            $table->integer('quantity');
            $table->float('total_cost');
            $table->unsignedBigInteger('supply_id')->nullable()->index();
            $table->unsignedBigInteger('order_id')->index();
            $table->timestamps();
            $table->foreign('supply_id')->references('id')->on('supplies')->nullOnDelete();
            $table->foreign('order_id')->references('id')->on('orders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('ordered_items');
    }
}

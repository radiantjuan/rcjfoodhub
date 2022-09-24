<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Supplies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('supplies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('categories_id')->index('INDEX_categories_id');
            $table->float('gram');
            $table->float('price');
            $table->integer('stock_count')->default(0);
            $table->integer('stock_warning_count')->default(0);
            $table->boolean('available_soon')->default(false);
            $table->boolean('out_of_stock')->default(false);
            $table->string('img_url')->nullable();
            $table->string('sku')->nullable()->index();
            $table->string('product_franchise_category')->nullable();
            $table->softDeletes();
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
        //
        Schema::dropIfExists('supplies');
    }
}

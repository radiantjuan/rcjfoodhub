<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromoCodes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type',['Fixed Amount','Percentage']);
            $table->enum('coverage',['All Items','Individual Discount']);
            $table->json('items_exception')->nullable();
            $table->json('items_list')->nullable();
            $table->string('code');
            $table->float('value');
            $table->json('franchisees')->nullable();
            $table->dateTime('start_date');
            $table->boolean('use_end_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->boolean('is_limited')->nullable();
            $table->integer('number_of_use')->nullable();
            $table->json('number_of_use_per_branch')->nullable();
            $table->boolean('is_inactive')->nullable();
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
        Schema::dropIfExists('promo_codes');
    }
}

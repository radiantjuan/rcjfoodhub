<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrders extends Migration
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
      $table->bigInteger('order_id')->index('recent_order_order_id_index')->nullable();
      $table->unsignedBigInteger('franchisee_id')->nullable()->index();
      $table->unsignedBigInteger('user_id')->nullable()->index();
      $table->string('customer_email');
      $table->json('ordered_items');
      $table->enum('order_status', [
        'UNDELIVERED-PAID',
        'UNDELIVERED-UNPAID',
        'PROCESSING',
        'COMPLETED',
        'CANCELLED',
      ]);
      $table->json('promo_code_setup')->nullable();
      $table->json('order_total');
      $table->string('payment_method');
      $table->string('special_instructions')->nullable();
      $table->string('shipping_option')->nullable();
      $table->string('shipping_address_1')->nullable();
      $table->string('shipping_address_2')->nullable();
      $table->string('shipping_city')->nullable();
      $table->string('shipping_barangay')->nullable();
      $table->string('shipping_zip_code')->nullable();
      $table->string('proof_of_payment')->nullable();
      $table->string('payment_transaction_number')->nullable();
      $table->softDeletes();
      $table->timestamps();
      $table->foreign('franchisee_id')->references('id')->on('franchisees')->nullOnDelete();
      $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
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
}

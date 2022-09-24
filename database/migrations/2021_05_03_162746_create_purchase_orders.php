<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseOrders extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('purchase_orders', function (Blueprint $table) {
      $table->id();
      $table->string('po_number');
      $table->string('warehouse');
      $table->json('supplies_list')->nullable();
      $table->dateTime('date_completed')->nullable();
      $table->enum('status', ['TO BE RECEIVED', 'PENDING', 'APPLIED', 'CANCELLED']);
      $table->json('received_qty')->nullable();
      $table->string('reason_for_cancelling')->nullable();
      $table->integer('created_by')->nullable();
      $table->softDeletes();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('purchase_orders');
  }
}

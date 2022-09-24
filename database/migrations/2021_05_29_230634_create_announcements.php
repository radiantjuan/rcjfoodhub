<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnnouncements extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('announcements', function (Blueprint $table) {
      $table->id();
      $table->string('title', 255);
      $table->string('img_url');
      $table->text('excerpt');
      $table->text('content');
      $table->enum('status', ['draft', 'published', 'not_published']);
      $table->dateTime('date_published')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('announcements');
  }
}

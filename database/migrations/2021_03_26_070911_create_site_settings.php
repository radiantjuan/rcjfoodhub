<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiteSettings extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('site_settings', function (Blueprint $table) {
      //
      $table->id();
      $table->string('name');
      $table->string('machine_name')->unique('UNI_machine_name_site_settings');
      $table->string('value');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('site_settings');
  }
}

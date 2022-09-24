<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->unsignedBigInteger('role_id')->index();
            $table->unsignedBigInteger('franchisees_id')->index();
            $table->string('api_token')->nullable();
            $table->boolean('is_inactive')->default(false);
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('role_id')->references('id')->on('roles');
            $table->foreign('franchisees_id')->references('id')->on('franchisees');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}

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
            $table->string('role')->default('user');

            // $table->string('mobile')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // $table->bigInteger('zero_user_id');
            // $table->text('zero_password');
            // $table->string('zero_totp');
            // $table->text('zero_api_key');
            // $table->text('zero_access_token');
            // $table->text('fb_token')->nullable();

            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}

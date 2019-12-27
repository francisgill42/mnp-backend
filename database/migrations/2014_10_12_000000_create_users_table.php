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
            $table->bigIncrements('id');
            $table->bigInteger('role_id')->nullable()->default(null);
            $table->bigInteger('customer_category_id')->nullable()->default(null);
            $table->boolean('master')->default(null);   
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone_number')->nullable()->default(null);
            $table->string('mobile_number')->nullable()->default(null);
            $table->string('ntn')->nullable()->default(null);
            $table->string('address')->nullable()->default(null);
            $table->string('city_id')->nullable()->default(null);
            $table->string('state_id')->nullable()->default(null);
            $table->string('password');
            $table->boolean('IsActive')->default(0);
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

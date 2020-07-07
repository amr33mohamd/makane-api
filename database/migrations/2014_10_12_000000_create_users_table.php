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
        //type 2-> resturant / 1-> user / 3->cafe
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->integer('type')->default('1');
            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();
            $table->integer('available')->nullable();
            $table->integer('points')->default('0');
            $table->string('invite_code');
            $table->text('address')->nullable();
            $table->text('phone');
            $table->string('image')->nullable();
            $table->string('website')->nullable();
            $table->text('lng')->nullable();
            $table->text('lat')->nullable();
            $table->date('renew_date')->nullable();
            $table->time('start_working')->nullable();
            $table->time('end_working')->nullable();
            $table->string('country');
            $table->integer('verified')->default(0);
            $table->integer('verify_code');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->integer('place')->default('1');
            $table->string('invited_code')->nullable();
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

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    // type 1 -> normal 2-> spcial
    // status 0 -> going 1-> done 2->ignored 3->canceled
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('store_id')->unsigned();
            $table->foreign('store_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('customer_id')->unsigned();
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('type')->default('1');
            $table->time('time');
            $table->integer('persons');
            $table->integer('kids')->default('0');
            $table->integer('smoking')->default('0');
            $table->integer('outt')->default('0');
            $table->bigInteger('SpecialEvent_id')->nullable()->unsigned();
            $table->foreign('SpecialEvent_id')->nullable()->references('id')->on('special_events')->onDelete('cascade');
            $table->integer('status')->default('0');
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
        Schema::dropIfExists('reservations');
    }
}

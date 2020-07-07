<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpecialEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('special_events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->time('time');
            $table->bigInteger('store_id')->unsigned();
            $table->foreign('store_id')->references('id')->on('users')->onDelete('cascade');
            $table->text('description');
            $table->integer('available');
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
        Schema::dropIfExists('special_events');
    }
}

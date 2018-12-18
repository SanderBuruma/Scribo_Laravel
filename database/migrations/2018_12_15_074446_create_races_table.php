<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('races', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('text_id')->unsigned();
            $table->foreign('text_id')->references('id')->on('texts');
            $table->float('speed',9,6)->unsigned(); //wpm
            $table->integer('mistakes')->unsigned(); //1 is max, 0 is min
            $table->float('time_taken',9,3)->unsigned(); //1 is max, 0 is min
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
        Schema::dropIfExists('races');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServerStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //this table is supposed to contain information like when the races leaderboard was last updated and other precious information for which I couldn't find a place in the tavern.
        Schema::create('server_statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('val_int')->nullable(); # generic integer value 
            $table->float ('val_float',11,9)->nullable(); # generic float value 
            $table->string('val_string',11,9)->nullable(); # generic string value 
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
        Schema::dropIfExists('server_statuses');
    }
}

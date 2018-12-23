<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserTableStatColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('rank')->nullable()->after('city');
            $table->float  ('time_taken',11,3)->nullable()->after('city');
            $table->integer('races')->nullable()->after('city');
            $table->integer('races_len')->nullable()->after('city'); //total races length
            $table->integer('mistakes')->nullable()->after('city');
            $table->integer('longest_perfect_streak')->nullable()->after('city');
            $table->integer('longest_marathon')->nullable()->after('city');
            $table->integer('stats_updated')->nullable()->after('city'); //raw time value not converted to be human readable
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('rank');
            $table->dropColumn('time_taken');
            $table->dropColumn('races');
            $table->dropColumn('races_len');
            $table->dropColumn('mistakes');
            $table->dropColumn('longest_perfect_streak');
            $table->dropColumn('longest_marathon');
            $table->dropColumn('stats_updated');
        });
    }
}

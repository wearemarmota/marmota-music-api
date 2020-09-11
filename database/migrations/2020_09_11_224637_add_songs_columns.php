<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSongsColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('songs', function (Blueprint $table) {
            $table->string('genre')->nullable();
            $table->float('duration')->unsigned()->default(0);
            $table->integer('position')->unsigned()->default(0);
            $table->integer('position_of')->unsigned()->default(0);
            $table->integer('disk')->unsigned()->default(0);
            $table->integer('disk_of')->unsigned()->default(0);
            $table->integer('bitrate')->unsigned()->default(0);
            $table->integer('samplerate')->unsigned()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('songs', function (Blueprint $table) {
            $table->dropColumn('genre');
            $table->dropColumn('duration');
            $table->dropColumn('position');
            $table->dropColumn('position_of');
            $table->dropColumn('disk');
            $table->dropColumn('disk_of');
            $table->dropColumn('bitrate');
            $table->dropColumn('samplerate');
        });
    }
}

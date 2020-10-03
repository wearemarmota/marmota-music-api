<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUuidToAlbumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('albums', function (Blueprint $table) {
            $table->string('uuid', 32)->after('artist_id');
        });

        Schema::table('albums', function (Blueprint $table) {
            $albums = App\Album::all();
            foreach($albums as $album) {
                $album->uuid = md5(uniqid(null, true));
                $album->save();
            }

            $table->string('uuid', 32)->nullable(false)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('albums', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
}

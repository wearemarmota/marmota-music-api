<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUuidToArtistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('artists', function (Blueprint $table) {
            $table->string('uuid', 32)->after('name');
        });

        Schema::table('artists', function (Blueprint $table) {
            $artists = App\Artist::all();
            foreach($artists as $artist) {
                $artist->uuid = md5(uniqid(null, true));
                $artist->save();
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
        Schema::table('artists', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenamingAuthors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('authors', 'artists');
        Schema::table('albums', function (Blueprint $table) {
            $table->renameColumn('name', 'title');
            $table->renameColumn('author_id', 'artist_id');
            $table->foreign('artist_id')
                ->references('id')
                ->on('artists')
                ->constrained()
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('artists', 'authors');
        Schema::table('albums', function (Blueprint $table) {
            $table->renameColumn('title', 'name');
            $table->renameColumn('artist_id', 'author_id');
            $table->foreign('author_id')
                ->references('id')
                ->on('authors')
                ->constrained()
                ->onDelete('cascade');
        });
    }
}

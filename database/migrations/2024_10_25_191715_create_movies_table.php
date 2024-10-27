<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->string('imdbID')->primary();
            $table->string('Title');
            $table->string('Year');
            $table->string('Rated')->nullable();
            $table->date('Released')->nullable();
            $table->string('Runtime')->nullable();
            $table->string('Genre')->nullable();
            $table->string('Director')->nullable();
            $table->string('Writer')->nullable();
            $table->string('Actors')->nullable();
            $table->text('Plot')->nullable();
            $table->string('Language')->nullable();
            $table->string('Country')->nullable();
            $table->string('Awards')->nullable();
            $table->string('Poster')->nullable();
            $table->json('Ratings')->nullable(); // Store ratings as JSON
            $table->string('Metascore')->nullable();
            $table->string('imdbRating')->nullable();
            $table->string('imdbVotes')->nullable();
            $table->string('Type')->nullable();
            $table->date('DVD')->nullable();
            $table->string('BoxOffice')->nullable();
            $table->string('Production')->nullable();
            $table->string('Website')->nullable();
            $table->string('Response')->nullable();
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
        Schema::dropIfExists('movies');
    }
}

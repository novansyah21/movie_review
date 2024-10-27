<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropMoviesTable extends Migration
{
    public function up()
    {
        Schema::dropIfExists('movies');
    }

    public function down()
    {
        // Recreate the movies table if needed
        Schema::create('movies', function (Blueprint $table) {
            $table->string('imdbID')->primary();
            $table->string('Title');
            $table->year('Year');
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
            $table->text('Ratings')->nullable();
            $table->string('Metascore')->nullable();
            $table->string('imdbRating')->nullable();
            $table->string('imdbVotes')->nullable();
            $table->string('Type')->nullable();
            $table->string('DVD')->nullable();
            $table->string('BoxOffice')->nullable();
            $table->string('Production')->nullable();
            $table->string('Website')->nullable();
            $table->string('Response')->default('True'); // Assuming default response is True
            $table->timestamps();
        });
    }
}

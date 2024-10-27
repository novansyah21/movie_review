<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    // Specify the table name if it's not the plural form of the model name
    protected $table = 'movies'; // Ensure this matches your table name
    protected $fillable = ['title', 'year', 'imdbRating', 'imdbID', 'poster'];

    // Define any relationships if needed, e.g., for favorites
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
}

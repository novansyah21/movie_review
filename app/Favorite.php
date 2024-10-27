<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model {
    protected $table = 'favorites'; // Specify the table name if different
    protected $fillable = ['user_id', 'imdbID']; // Adjust this to your needs
}

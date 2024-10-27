<?php

namespace Database\Seeds; // Make sure this matches the directory structure

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UsersTableSeeder::class, // Ensure this class is in the same namespace
        ]);
    }
}

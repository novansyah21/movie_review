<?php

namespace Database\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            'username' => 'aldmic',
            'name' => 'Aldmic Name', // Add a default name value here
            'password' => bcrypt('123abc123'), // Hash the password
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

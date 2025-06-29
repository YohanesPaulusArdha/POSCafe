<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{

    public function run()
    {
        //agar username tidak ada yang terduplikat 
        DB::table('users')->truncate();

        //insert user
        DB::table('users')->insert([
            'name' => 'ardha',
            'email' => 'ardhacandidate@gmail.com',
            'password' => Hash::make('ardha123'), 
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
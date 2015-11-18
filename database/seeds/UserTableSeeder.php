<?php

use Illuminate\Database\Seeder;
use App\User as User;

  
class UserTableSeeder extends Seeder {
  
    public function run() {
        //User::truncate();
        DB::Table('users')->delete();
  
        User::create([
            'email' => 'craig9@gmail.com',
            'password' => Hash::make('password'),
            'name' => 'craig'
        ]);

        User::create([
            'email' => 'art@franklindirect.com.au',
            'password' => Hash::make('password'),
            'name' => 'david'
        ]);

    }
}


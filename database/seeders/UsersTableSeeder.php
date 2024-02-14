<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Seed regular users
        User::create([
            'name' => 'User1',
            'email' => 'user1@gmail.com',
            'password' => Hash::make('user1234'), // User password
            'role' => 0, // User role
        ]);
    }
}

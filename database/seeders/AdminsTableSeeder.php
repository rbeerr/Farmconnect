<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminsTableSeeder extends Seeder
{
    public function run()
    {
        // Seed admin users
        User::create([
            'name' => 'Admin1',
            'email' => 'admin1@gmail.com',
            'password' => Hash::make('admin123'), // Admin password
            'role' => 1, // Admin role
        ]);

    }
}


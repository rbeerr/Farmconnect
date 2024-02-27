<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class FarmOwnerSeeder extends Seeder
{
    public function run()
    {
        // Add FarmOwner seed data
        DB::table('users')->insert([
            'firstName' => 'Farm',
            'lastName' => 'Owner',
            'email' => 'farmowner@example.com',
            'password' => Hash::make('password'), // Hash the password
            'role' => 'Farm-Owner',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Add more FarmOwner data if needed
    }
}

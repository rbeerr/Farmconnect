<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class FarmWorkerSeeder extends Seeder
{
    public function run()
    {
        // Add FarmWorker seed data
        DB::table('users')->insert([
            'firstName' => 'Farm',
            'lastName' => 'Worker',
            'email' => 'farmworker@example.com',
            'password' => Hash::make('password'), // Hash the password
            'role' => 'Farm-Worker',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Add more FarmWorker data if needed
    }
}

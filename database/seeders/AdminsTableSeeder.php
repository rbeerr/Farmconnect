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
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'role' => 'Admin', // Update role to match the enum values in the migration
            'firstName' => 'Admin',
            'lastName' => 'User',
            'contactNumber' => '1234567890',
            'dateOfBirth' => '1990-01-01',
            'province' => 'Sample Province',
            'municipality' => 'Sample Municipality',
            'barangay' => 'Sample Barangay',
        ]);

    }
}


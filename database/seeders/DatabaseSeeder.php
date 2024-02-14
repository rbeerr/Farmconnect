<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(\Database\Seeders\UsersTableSeeder::class);
        $this->call(\Database\Seeders\AdminsTableSeeder::class);
    }
}

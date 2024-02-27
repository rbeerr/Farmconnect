<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Seed the admin users
        $this->call(AdminsTableSeeder::class);

        // Seed FarmWorker and FarmOwner
        $this->call(FarmWorkerSeeder::class);
        $this->call(FarmOwnerSeeder::class);
    }
}

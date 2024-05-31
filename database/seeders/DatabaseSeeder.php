<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Database\Seeders\RolesTableSeeder as SeedersRolesTableSeeder;
use Illuminate\Database\Seeder;
use RolesTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            SeedersRolesTableSeeder::class,
            PermissionsTableSeeder::class,
            CreateUsersSeeder::class,
            AssignPermissionsSeeder::class,
            // BusStationsSeeder::class,
            // //AdminBusStationSeeder::class
            // BusSeeder::class,
            // BusRouteSeeder::class,
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Buat peran Root
        Role::create(['name' => 'Root']);

        // Buat peran Upt
        Role::create(['name' => 'Upt']);

        // Buat peran Admin
        Role::create(['name' => 'Admin']);

        // Roles for Perusahaan Otobus
        Role::create(['name' => 'PO']);

        // Buat peran Driver
        Role::create(['name' => 'Driver']);

        // Buat peran Bus_Conductor
        Role::create(['name' => 'Bus_Conductor']);

        // Buat peran Passenger
        Role::create(['name' => 'Passenger']);
    }
}

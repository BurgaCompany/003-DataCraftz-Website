<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // // Izin untuk melihat dashboard
        // Permission::create(['name' => 'view dashboard']);

        // // Izin untuk mengedit profil
        // Permission::create(['name' => 'edit profile']);

        // // Izin CRUD Upt
        // Permission::create(['name' => 'create upt']);
        // Permission::create(['name' => 'read upt']);
        // Permission::create(['name' => 'update upt']);
        // Permission::create(['name' => 'delete upt']);

        // // Izin CRUD Admin
        // Permission::create(['name' => 'create admin']);
        // Permission::create(['name' => 'read admin']);
        // Permission::create(['name' => 'update admin']);
        // Permission::create(['name' => 'delete admin']);

        // // Izin CRUD Bus
        // Permission::create(['name' => 'create bus']);
        // Permission::create(['name' => 'read bus']);
        // Permission::create(['name' => 'update bus']);
        // Permission::create(['name' => 'delete bus']);

        // // Izin CRUD Driver
        // Permission::create(['name' => 'create driver']);
        // Permission::create(['name' => 'read driver']);
        // Permission::create(['name' => 'update driver']);
        // Permission::create(['name' => 'delete driver']);

        // // Izin CRUD Bus Conductor
        // Permission::create(['name' => 'create bus_conductor']);
        // Permission::create(['name' => 'read bus_conductor']);
        // Permission::create(['name' => 'update bus_conductor']);
        // Permission::create(['name' => 'delete bus_conductor']);

        // // Izin CRUD Schedule
        // Permission::create(['name' => 'create schedule']);
        // Permission::create(['name' => 'read schedule']);
        // Permission::create(['name' => 'update schedule']);
        // Permission::create(['name' => 'delete schedule']);

        // // Izin CRUD Bus Station
        // Permission::create(['name' => 'create bus_station']);
        // Permission::create(['name' => 'read bus_station']);
        // Permission::create(['name' => 'update bus_station']);
        // Permission::create(['name' => 'delete bus_station']);

        // // Izin untuk melihat riwayat reservasi
        // Permission::create(['name' => 'read reservation_history']);
    }
}
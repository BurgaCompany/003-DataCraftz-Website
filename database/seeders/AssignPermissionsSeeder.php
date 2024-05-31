<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AssignPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Temukan pengguna dengan ID 1
        $root = User::find(1);

        // Temukan peran 'Root' jika sudah ada
        $rootRole = Role::where('name', 'Root')->first();
        $root->assignRole($rootRole);

        // // Temukan pengguna dengan ID 2, 3, dan 4
        // $upt = User::whereIn('id', [2, 3, 4])->get();

        // Temukan pengguna dengan ID 2, 3, dan 4
        $upt = User::whereIn('id', [2])->get();

        $uptRole = Role::where('name', 'Upt')->first();
        $upt->each(function ($user) use ($uptRole) {
            $user->assignRole($uptRole);
        });

        // // Assign role 'Admin' to 30 users from each 'id_upt'
        // for ($id_upt = 2; $id_upt <= 4; $id_upt++) {
        //     $users = User::where('id_upt', $id_upt)->take(30)->get();
        //     $adminRole = Role::where('name', 'Admin')->first();
        //     $users->each(function ($user) use ($adminRole) {
        //         $user->assignRole($adminRole);
        //     });
        // }

        // // Assign role 'Driver' to 20 users from each 'id_upt'
        // for ($id_upt = 2; $id_upt <= 4; $id_upt++) {
        //     $users = User::where('id_upt', $id_upt)->skip(30)->take(20)->get();
        //     $driverRole = Role::where('name', 'Driver')->first();
        //     $users->each(function ($user) use ($driverRole) {
        //         $user->assignRole($driverRole);
        //     });
        // }

        // // Assign role 'Bus_Conductor' to 20 users from each 'id_upt'
        // for ($id_upt = 2; $id_upt <= 4; $id_upt++) {
        //     $users = User::where('id_upt', $id_upt)->skip(50)->take(20)->get();
        //     $busConductorRole = Role::where('name', 'Bus_Conductor')->first();
        //     $users->each(function ($user) use ($busConductorRole) {
        //         $user->assignRole($busConductorRole);
        //     });
        // }

        // // Assign role 'Passenger' to the remaining users
        // $passengers = User::whereNotIn('id', [1, 2, 3, 4])
        //     ->whereNull('id_upt')
        //     ->get();

        // $passengerRole = Role::where('name', 'Passenger')->first();
        // $passengers->each(function ($user) use ($passengerRole) {
        //     $user->assignRole($passengerRole);
        // });
    }
}

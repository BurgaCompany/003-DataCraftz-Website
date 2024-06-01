<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // Root user
        DB::table('users')->insert([
            'name' => 'root',
            'email' => 'root1@gmail.com', // Ubah email agar unik
            'password' => bcrypt('password1234'),
            'address' => 'Root Address',
            'gender' => 'male',
            'phone_number' => '08980512945',
            'images' => 'avatars/male.jpg',
            'created_at' => now(),
        ]);

        // UPT PELAYANAN SOSIAL LANJUT USI JEMBER
        DB::table('users')->insert([
            'name' => 'UPT Pengelolaan Prasarana Perhubungan JEMBER',
            'email' => 'upt_jember@gmail.com', // Ubah email agar unik
            'password' => bcrypt('password1234'),
            'address' => 'Jember Address',
            'gender' => 'female',
            'phone_number' => '089803212945',
            'images' => 'avatars/female.jpg',
            'created_at' => now(),
        ]);

        // UPT PELAYANAN SOSIAL LANJUT USI JEMBER
        DB::table('users')->insert([
            'name' => 'PO Haryanto',
            'email' => 'po_haryanto@gmail.com', // Ubah email agar unik
            'password' => bcrypt('password1234'),
            'address' => 'Jember Address',
            'gender' => 'female',
            'phone_number' => '089803212944',
            'images' => 'avatars/female.jpg',
            'created_at' => now(),
        ]);

        // // UPT Pengelolaan Prasarana Perhubungan PASURUAN
        // DB::table('users')->insert([
        //     'name' => 'UPT Pengelolaan Prasarana Perhubungan PASURUAN',
        //     'email' => 'upt_pasuruan@gmail.com', // Ubah email agar unik
        //     'password' => bcrypt('password1234'),
        //     'address' => 'Pasuruan Address',
        //     'gender' => 'female',
        //     'phone_number' => '08932112945',
        //     'images' => 'avatars/female.jpg',
        //     'created_at' => now(),
        // ]);

        // // UPT Pengelolaan Prasarana Perhubungan BONDOWOSO
        // DB::table('users')->insert([
        //     'name' => 'UPT Pengelolaan Prasarana Perhubungan BONDOWOSO',
        //     'email' => 'upt_bondowoso@gmail.com', // Ubah email agar unik
        //     'password' => bcrypt('password1234'),
        //     'address' => 'Bondowoso Address',
        //     'gender' => 'female',
        //     'phone_number' => '08980513215',
        //     'images' => 'avatars/female.jpg',
        //     'created_at' => now(),
        // ]);

        // $faker = Faker::create('id_ID');
        // $usedEmails = [];

        // for ($i = 5; $i <= 220; $i++) {
        //     // Generate unique Gmail address
        //     do {
        //         $emailUsername = $faker->userName();
        //         $email = $emailUsername . '@gmail.com';
        //     } while (in_array($email, $usedEmails));
        //     $usedEmails[] = $email;

        //     // Generate other fields
        //     $name = $faker->name();
        //     $address = $faker->address();
        //     $gender = $faker->randomElement(['male', 'female']);
        //     $images = $gender === 'male' ? 'avatars/male.jpg' : 'avatars/female.jpg';
        //     $phone_number = '08' . $faker->numerify('########');

        //     // Determine id_upt based on range
        //     if ($i >= 5 && $i <= 75) {
        //         $id_upt = 2;
        //     } elseif ($i >= 76 && $i <= 146) {
        //         $id_upt = 3;
        //     } elseif ($i >= 147 && $i <= 217) {
        //         $id_upt = 4;
        //     } else {
        //         // For remaining values
        //         $id_upt = null; // Adjust this as needed
        //     }

        //     // Insert into the database
        //     DB::table('users')->insert([
        //         'name' => $name,
        //         'email' => $email,
        //         'password' => Hash::make('password1234'),
        //         'address' => $address,
        //         'gender' => $gender,
        //         'phone_number' => $phone_number,
        //         'images' => $images,
        //         'id_upt' => $id_upt,
        //         'created_at' => now(),
        //     ]);
        // }
    }
}

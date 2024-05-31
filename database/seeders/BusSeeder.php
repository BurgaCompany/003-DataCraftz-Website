<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bus; // Ensure to import the Bus model
use App\Models\Buss;
use Faker\Factory as Faker;

class BusSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');
        $busNames = [
            'TransJakarta', 'Mitra Rahayu', 'Damri', 'Harapan Jaya', 'Lorena', 'Agra Mas', 'Karina',
            'Sinar Jaya', 'Kramat Djati', 'Pahala Kencana', 'Primajasa', 'Putera Mulya', 'Rosalia Indah',
            'Safari Dharma Raya', 'Sumber Alam', 'Sumber Kencono', 'Efisiensi', 'Handoyo', 'Haryanto',
            'Kencana', 'Laju Prima', 'Mayasari Bakti', 'Murni Jaya', 'New Shantika', 'Safari Mandala',
            'Sindoro Satriamas', 'SM Prima', 'Sumber Selamat', 'Tami Jaya', 'Wahana'
        ];

        $uptIds = [2, 3, 4];
        $uptIndex = 0;

        foreach ($busNames as $index => $name) {
            Buss::create([
                'name' => $name,
                'license_plate_number' => strtoupper($faker->bothify('?? #### ??')),
                'chair' => rand(50, 60),
                'class' => $faker->randomElement(['ekonomi', 'bisnis']),
                'status' => 1,
                'information' => null,
                'images' => 'avatars/bus.jpg',
                'id_upt' => $uptIds[$uptIndex % 3] // Cycle through 2, 3, 4
            ]);

            $uptIndex++;
        }
    }
}

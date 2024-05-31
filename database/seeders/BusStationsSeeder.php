<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BusStationsSeeder extends Seeder
{
    public function run()
    {
        $terminalNames = [
            'Terminal Kediri',
            'Terminal Nganjuk',
            'Terminal Jombang',
            'Terminal Mojokerto',
            'Terminal Surabaya',
            'Terminal Gresik',
            'Terminal Lamongan',
            'Terminal Tuban',
            'Terminal Bojonegoro',
            'Terminal Ngawi',
            'Terminal Magetan',
            'Terminal Madiun',
        ];

        $now = Carbon::now();

        // Insert bus stations
        foreach ($terminalNames as $terminalName) {
            DB::table('bus_stations')->insert([
                'name' => $terminalName,
                'address' => \Faker\Factory::create('id_ID')->address(),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // Get all bus stations
        $busStations = DB::table('bus_stations')->get();

        // Slice terminal names into chunks for each user ID
        $chunks = $busStations->chunk(4); // Slice into chunks of 4

        $userIdValues = [
            2 => $chunks[0],
            3 => $chunks[1],
            4 => $chunks[2]
        ];

        // Loop through and assign terminals to each user ID
        foreach ($userIdValues as $userId => $busStationsChunk) {
            foreach ($busStationsChunk as $busStation) {
                DB::table('user_bus_station')->insert([
                    'user_id' => $userId,
                    'bus_station_id' => $busStation->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }
}

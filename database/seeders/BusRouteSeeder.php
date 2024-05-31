<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BusRoute;
use App\Models\Bus;
use App\Models\Buss;
use App\Models\BusStation;
use App\Models\Schedule;
use Faker\Factory as Faker;

class BusRouteSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');
        $busIds = Buss::pluck('id')->toArray();
        $stationIds = BusStation::pluck('id')->toArray();

        for ($i = 0; $i < 30; $i++) {
            $hours = $faker->numberBetween(0, 23); // Hours from 0 to 23
            $minutes = $faker->numberBetween(0, 59); // Minutes from 0 to 59

            $timeStart = sprintf('%02d:%02d:00', $hours, $minutes); // Formatted as HH:MM:SS

            $fromStationId = $faker->randomElement($stationIds);
            $toStationId = $faker->randomElement($stationIds);

            // Ensure from_station_id and to_station_id are not the same
            while ($toStationId == $fromStationId) {
                $toStationId = $faker->randomElement($stationIds);
            }

            Schedule::create([
                'bus_id' => $faker->randomElement($busIds),
                'from_station_id' => $fromStationId,
                'to_station_id' => $toStationId,
                'price' => $faker->randomElement([10000, 15000, 20000, 25000, 30000]),
                'time_start' => $timeStart,
                'pwt' => $faker->numberBetween(120, 300) // Estimated travel time in minutes
            ]);
        }
    }
}

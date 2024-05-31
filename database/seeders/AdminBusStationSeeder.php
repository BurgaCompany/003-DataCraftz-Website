<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\AdminBusStation;
use App\Models\UserBusStation;
use Illuminate\Support\Facades\DB;

class AdminBusStationSeeder extends Seeder
{
    public function run()
    {
        // Ambil total stasiun bus yang tersedia
        $totalBusStations = UserBusStation::count();

        // Jumlah admin per ID_UPT
        $adminCount = 30;

        for ($id_upt = 2; $id_upt <= 4; $id_upt++) {
            $adminIndex = 0; // Reset admin index for each ID_UPT

            while ($adminIndex < $adminCount) {
                // Ambil semua admin dengan 'id_upt' yang sesuai dan memiliki peran 'Admin'
                $admin = User::where('id_upt', $id_upt)
                    ->whereHas('roles', function ($query) {
                        $query->where('name', 'Admin');
                    })
                    ->skip($adminIndex) // Skip admins already processed
                    ->first();

                if (!$admin) {
                    break; // No more admins for this ID_UPT
                }

                // Tentukan rentang bus_station_id untuk admin saat ini
                $startStationIndex = ($adminIndex * 4) % $totalBusStations + 1;
                $endStationIndex = min(($adminIndex + 1) * 4, $totalBusStations);

                for ($stationIndex = $startStationIndex; $stationIndex <= $endStationIndex; $stationIndex++) {
                    // Ambil satu user bus station sesuai dengan indeks
                    $userBusStation = UserBusStation::where('id', $stationIndex)->first();

                    // Jika user bus station ditemukan dan tidak ada rekaman yang ada, sisipkan rekaman baru
                    if ($userBusStation) {
                        $existingRecord = DB::table('admin_bus_station')
                            ->where('user_id', $admin->id)
                            ->where('bus_station_id', $userBusStation->bus_station_id)
                            ->exists();

                        if (!$existingRecord) {
                            DB::table('admin_bus_station')->insert([
                                'user_id' => $admin->id,
                                'bus_station_id' => $userBusStation->bus_station_id,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    }
                }

                $adminIndex++;
            }
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\AdminBusStation;
use App\Models\Reservation;
use App\Models\UserBusStation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransitController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $upt_ids = UserBusStation::where('user_id', $user->id)->pluck('bus_station_id')->toArray();
        //dd($upt_ids);

        // Query dasar dengan relasi yang diperlukan
        $query = Reservation::with([
            'schedule.bus' => function ($query) {
                $query->withTrashed();
            },
            'user'
        ]);

        $query_on = clone $query;
        $query_off = clone $query;

        // Initialize $busStationIds
        $busStationIds = null;

        // Kondisi berdasarkan peran pengguna
        if ($user->hasRole('root')) {
            // Jika pengguna memiliki peran root, tidak perlu filter tambahan
            $busStationIds = null;
        } elseif ($user->hasRole('Admin')) {
            $busStationIds = DB::table('admin_bus_station')
                ->where('user_id', $user->id)
                ->pluck('bus_station_id')->toArray();
        } elseif ($user->hasRole('Upt')) {
            $busStationIds = DB::table('user_bus_station')
                ->where('user_id', $user->id)
                ->pluck('bus_station_id')->toArray();
        }

        if ($request->has('terminal_id')) {
            $terminalId = $request->terminal_id;

            // Tambahkan kondisi berdasarkan terminal_id
            $query->where(function ($query) use ($terminalId) {
                $query->whereHas('schedule.fromStation', function ($query) use ($terminalId) {
                    $query->where('id', $terminalId);
                })->orWhereHas('schedule.toStation', function ($query) use ($terminalId) {
                    $query->where('id', $terminalId);
                });
            });

            $query_on->whereHas('schedule.fromStation', function ($query) use ($terminalId) {
                $query->where('id', $terminalId);
            });

            $query_off->whereHas('schedule.toStation', function ($query) use ($terminalId) {
                $query->where('id', $terminalId);
            });
        } elseif ($busStationIds !== null) {
            // Tambahkan kondisi default jika terminal_id tidak diberikan
            $query->where(function ($query) use ($busStationIds) {
                $query->whereHas('schedule.fromStation', function ($query) use ($busStationIds) {
                    $query->whereIn('id', $busStationIds);
                })->orWhereHas('schedule.toStation', function ($query) use ($busStationIds) {
                    $query->whereIn('id', $busStationIds);
                });
            });

            $query_on->whereHas('schedule.fromStation', function ($query) use ($busStationIds) {
                $query->whereIn('id', $busStationIds);
            });

            $query_off->whereHas('schedule.toStation', function ($query) use ($busStationIds) {
                $query->whereIn('id', $busStationIds);
            });
        }

        $transits = $query->get();
        $transits_on = $query_on->get();
        $transits_off = $query_off->get();

        // Menghitung jumlah penumpang on dan off untuk setiap schedule_id
        $passengerCounts = [];

        foreach ($transits_on as $transit_on) {
            if (!isset($passengerCounts[$transit_on->schedule_id])) {
                $passengerCounts[$transit_on->schedule_id] = [
                    'passengers_on' => 0,
                    'passengers_off' => 0,
                ];
            }
            $passengerCounts[$transit_on->schedule_id]['passengers_on'] += $transit_on->tickets_booked;
        }

        foreach ($transits_off as $transit_off) {
            if (!isset($passengerCounts[$transit_off->schedule_id])) {
                $passengerCounts[$transit_off->schedule_id] = [
                    'passengers_on' => 0,
                    'passengers_off' => 0,
                ];
            }
            $passengerCounts[$transit_off->schedule_id]['passengers_off'] += $transit_off->tickets_booked;
        }

        // Menggabungkan data penumpang ke dalam $transits
        foreach ($transits as $transit) {
            $transit->passengers_on = $passengerCounts[$transit->schedule_id]['passengers_on'] ?? 0;
            $transit->passengers_off = $passengerCounts[$transit->schedule_id]['passengers_off'] ?? 0;
        }



        return view('transits.index', compact('transits', 'upt_ids'));
    }
}

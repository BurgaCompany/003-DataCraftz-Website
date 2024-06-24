<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransitController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Reservation::with([
            'schedule.bus' => function ($query) {
                $query->withTrashed();
            },
            'user'
        ]);

        $query_on = Reservation::with([
            'schedule.bus' => function ($query) {
                $query->withTrashed();
            },
            'user'
        ]);

        $query_off = Reservation::with([
            'schedule.bus' => function ($query) {
                $query->withTrashed();
            },
            'user'
        ]);

        if ($user->hasRole('Admin')) {
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
        }

        $transits = $query->get();
        $transits_on = $query_on->get();
        $transits_off = $query_off->get();

        foreach ($transits_on as $transit_on) {
            $transit_on->passengers_on = Reservation::where('schedule_id', $transit_on->schedule_id)
                ->sum('tickets_booked');
        }

        foreach ($transits_off as $transit_off) {
            $transit_off->passengers_off = Reservation::where('schedule_id', $transit_off->schedule_id)
                ->sum('tickets_booked');
        }

        return view('transits.index', compact('transits', 'transits_on', 'transits_off'));
    }
}

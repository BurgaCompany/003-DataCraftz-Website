<?php

namespace App\Http\Controllers;

use App\Models\AdminBusStation;
use App\Models\UserBusStation;
use App\Models\Reservation;
use App\Models\BusStation;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardUptController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $totalAdmins = User::role('Admin')
            ->where('id_upt', $user->id)
            ->count();

        // Terminals assigned to the user
        $terminals = UserBusStation::where('user_id', $user->id)->count();

        // Query bus station IDs assigned to the user
        $busStationIds = UserBusStation::where('user_id', $user->id)->pluck('bus_station_id')->toArray();

        // Fetch reservations where from_station_id or to_station_id matches user's bus station IDs
        $reservations = Reservation::with(['schedule.fromStation', 'schedule.toStation'])
            ->whereHas('schedule', function ($query) use ($busStationIds) {
                $query->whereHas('fromStation', function ($subquery) use ($busStationIds) {
                    $subquery->whereIn('id', $busStationIds);
                })->orWhereHas('toStation', function ($subquery) use ($busStationIds) {
                    $subquery->whereIn('id', $busStationIds);
                });
            })
            ->get();

        // Calculate total passengers on and off
        $passengersOn = 0;
        $passengersOff = 0;

        // Arrays to store terminal labels and passenger counts
        $terminalLabels = [];
        $passengerCounts = [];

        foreach ($reservations as $reservation) {
            if (in_array($reservation->schedule->from_station_id, $busStationIds)) {
                $passengersOn += $reservation->tickets_booked;
            } elseif (in_array($reservation->schedule->to_station_id, $busStationIds)) {
                $passengersOff += $reservation->tickets_booked;
            }

            // Populate terminal labels and passenger counts
            $fromStation = $reservation->schedule->fromStation;
            $toStation = $reservation->schedule->toStation;

            if (!in_array($fromStation->name, $terminalLabels)) {
                $terminalLabels[] = $fromStation->name;
                $passengerCounts[$fromStation->name] = 0;
            }
            if (!in_array($toStation->name, $terminalLabels)) {
                $terminalLabels[] = $toStation->name;
                $passengerCounts[$toStation->name] = 0;
            }

            $passengerCounts[$fromStation->name] += $reservation->tickets_booked;
            $passengerCounts[$toStation->name] += $reservation->tickets_booked;
        }

        // Convert passengerCounts to an array of values for chart
        $passengerCounts = array_values($passengerCounts);

        // Fetch terminal locations
        $terminalsLocations = BusStation::whereIn('id', $busStationIds)->get(['name', 'latitude', 'longitude']);

        return view('dashboard_upt', compact('user', 'totalAdmins', 'terminals', 'passengersOn', 'passengersOff', 'terminalLabels', 'passengerCounts', 'terminalsLocations'));
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Schedule;
use App\Models\BusStation; // tambahkan ini untuk model BusStation
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardAdminController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('Admin')) {
            // Fetch bus station IDs assigned to the admin user
            $busStationIds = DB::table('admin_bus_station')
                ->where('user_id', $user->id)
                ->pluck('bus_station_id')->toArray();

            // Query reservations where the admin's bus station is the departure station
            $query_on = Reservation::with(['schedule.fromStation', 'schedule.toStation'])
                ->whereHas('schedule.fromStation', function ($query) use ($busStationIds) {
                    $query->whereIn('id', $busStationIds);
                });

            // Query reservations where the admin's bus station is the arrival station
            $query_off = Reservation::with(['schedule.fromStation', 'schedule.toStation'])
                ->whereHas('schedule.toStation', function ($query) use ($busStationIds) {
                    $query->whereIn('id', $busStationIds);
                });

            // Calculate total passengers on and off
            $passengersOn = $query_on->sum('tickets_booked');
            $passengersOff = $query_off->sum('tickets_booked');

            // Query bus station names related to the admin
            $adminBusStations = BusStation::whereIn('id', $busStationIds)->pluck('name')->toArray();

            // Get the first bus station for this example (you can change the logic as needed)
            $firstBusStationId = $busStationIds[0] ?? null;

            $admins = [];
            $adminBusStation = '';

            if ($firstBusStationId) {
                // Get bus station name
                $adminBusStation = BusStation::where('id', $firstBusStationId)->value('name');

                // Fetch admins assigned to this bus station
                $adminIds = DB::table('admin_bus_station')
                    ->where('bus_station_id', $firstBusStationId)
                    ->pluck('user_id')->toArray();

                $admins = User::whereIn('id', $adminIds)->get();
            }

            $query = Schedule::with([
                'bus' => function ($query) {
                    $query->withTrashed();
                }
            ]);

            // Filter the schedules by the bus station IDs for both from_station and to_station
            $query->where(function ($query) use ($busStationIds) {
                $query->whereIn('from_station_id', $busStationIds)
                    ->orWhereIn('to_station_id', $busStationIds);
            });

            // Get the filtered or unfiltered schedules
            $schedules = $query->get();

            return view('dashboard_admin', compact('user', 'passengersOn', 'passengersOff', 'schedules', 'adminBusStations', 'admins', 'adminBusStation'));
        }

        // If the user is not an admin, just return the view with the user information
        return view('dashboard_admin', compact('user'));
    }
}

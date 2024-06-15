<?php

namespace App\Http\Controllers;

use App\Models\Buss;
use App\Models\Reservation;
use App\Models\User;
use App\Models\UserBusStation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $uptId = $user->hasRole('Upt') ? $user->id : ($user->hasRole('Admin') ? $user->id_upt : null);

        $roles = $user->getRoleNames();
        $permissions = $user->getPermissionNames();
        $showDashboard = !$user->hasRole('Root');

        $now = Carbon::now();
        $sevenDaysAgo = Carbon::now()->subDays(6);

        $userRegistrations = Reservation::join('busses', 'reservations.bus_id', '=', 'busses.id')
            ->select(DB::raw('DATE(reservations.date_departure) as date'), DB::raw('COUNT(*) as reservations'))
            ->whereBetween('reservations.date_departure', [$sevenDaysAgo->format('Y-m-d'), $now->format('Y-m-d')])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $dates = $userRegistrations->pluck('date');
        $reservationsCount = $userRegistrations->pluck('reservations');

        $totalAdmins = 0;
        $totalBusses = 0;
        if ($uptId) {
            $totalAdmins = User::role('Admin')
                ->where('id_upt', $uptId)
                ->count();
        }
        $totalBusses = Buss::All()->count();
        $totalBusStations = 0;
        if ($uptId) {
            $totalBusStations = UserBusStation::where('user_id', $uptId)
                ->distinct('bus_station_id')
                ->count('bus_station_id');
        }

        $status = $request->input('status');
        $query = Buss::leftJoin('driver_conductor_bus', 'busses.id', '=', 'driver_conductor_bus.bus_id')
            ->leftJoin('users as drivers', 'driver_conductor_bus.driver_id', '=', 'drivers.id')

            ->select(
                'busses.*',
                'drivers.name as driver_name',

            );

        if ($status) {
            $query->where('busses.status', $status);
        }

        $busses = $query->orderBy('busses.id', 'asc')->get();

        if ($request->ajax()) {
            return view('partials.bus_table_body', compact('busses'))->render();
        }

        return view('dashboard', compact(
            'user',
            'roles',
            'permissions',
            'showDashboard',
            'dates',
            'reservationsCount',
            'totalAdmins',
            'totalBusStations',
            'totalBusses',
            'busses'
        ));
    }
}

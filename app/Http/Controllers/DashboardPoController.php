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

class DashboardPoController extends Controller
{
    /**
     * Display the dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;

        // Determine the poId based on the user's role
        $poId = $user->hasRole('PO') ? $userId : null;

        // Getting current date and date 7 days ago
        $now = Carbon::now();
        $sevenDaysAgo = Carbon::now()->subDays(6); // Subtract 6 days to include today as the 7th day

        // Fetch user registrations with the appropriate conditions
        $userRegistrations = DB::table('reservations')
            ->join('busses', 'reservations.bus_id', '=', 'busses.id')
            ->select(DB::raw('DATE(reservations.date_departure) as date'), DB::raw('COUNT(*) as reservations'))
            ->when($poId, function ($query, $poId) {
                return $query->where('busses.id_po', $poId);
            })
            ->whereBetween('reservations.date_departure', [$sevenDaysAgo->format('Y-m-d'), $now->format('Y-m-d')])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $dates = $userRegistrations->pluck('date');
        $reservationsCount = $userRegistrations->pluck('reservations');

        if ($poId) {
            // Fetch total drivers and conductors if the user is PO
            $totalDriver = User::role('Driver')->where('id_po', $poId)->count();
            $totalConductor = User::role('Bus_conductor')->where('id_po', $poId)->count();

            // Fetch total busses for the PO
            $totalBusses = Buss::where('id_po', $poId)->count();
        } else {
            // If not PO, set totals to 0
            $totalDriver = 0;
            $totalConductor = 0;
            $totalBusses = 0;
        }

        $status = $request->input('status');
        $query = DB::table('busses')
            ->leftJoin('driver_conductor_bus', 'busses.id', '=', 'driver_conductor_bus.bus_id')
            ->leftJoin('users as drivers', 'driver_conductor_bus.driver_id', '=', 'drivers.id')
            ->leftJoin('users as conductors', 'driver_conductor_bus.bus_conductor_id', '=', 'conductors.id')
            ->select(
                'busses.*',
                'drivers.name as driver_name',
                'conductors.name as conductor_name'
            )
            ->when($poId, function ($query, $poId) {
                return $query->where('busses.id_po', $poId);
            });

        if ($status) {
            $query->where('busses.status', $status);
        }

        $busses = $query->orderBy('busses.id', 'asc')->get();

        if ($request->ajax()) {
            return view('partials.bus_table_body', compact('busses'))->render();
        }

        return view('dashboard_po', compact(
            'user',
            'dates',
            'reservationsCount',
            'totalDriver',
            'totalConductor',
            'totalBusses',
            'busses'
        ));
    }
}

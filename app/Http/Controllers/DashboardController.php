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

        $userId = Auth::id();

        // Mendapatkan tanggal sekarang dan 7 hari yang lalu
        $now = Carbon::now();
        $sevenDaysAgo = Carbon::now()->subDays(6); // Subtract 6 days to include today as the 7th day

        // Mengambil data registrasi user dengan kondisi id_upt yang sesuai
        $userRegistrations = DB::table('reservations')
            ->join('busses', 'reservations.bus_id', '=', 'busses.id')
            ->select(DB::raw('DATE(reservations.date_departure) as date'), DB::raw('COUNT(*) as reservations'))
            ->where('busses.id_po', $userId)
            ->whereBetween('reservations.date_departure', [$sevenDaysAgo->format('Y-m-d'), $now->format('Y-m-d')])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $dates = $userRegistrations->pluck('date');
        $reservationsCount = $userRegistrations->pluck('reservations');

        //dd($sevenDaysAgo);

        if ($uptId) {
            // Mendapatkan total admins yang memiliki ID upt yang sesuai
            $totalAdmins = User::role('Admin')
                ->where('id_upt', $uptId)
                ->count();

            // Mendapatkan total busses yang sesuai dengan ID upt
            $totalBusses = DB::table('busses')
                ->where('id_upt', $uptId)
                ->count();
        } else {
            // Jika ID upt tidak ditemukan, set total admins dan total busses menjadi 0 atau lakukan penanganan lainnya
            $totalAdmins = 0;
            $totalBusses = 0;
        }

        $userId = $user->id;
        $totalBusStations = UserBusStation::where('user_id', $userId)
            ->distinct('bus_station_id') // Memastikan hanya menghitung entri yang unik berdasarkan bus_station_id
            ->count('bus_station_id');

        // Atau jika Anda ingin mengambil data bus station yang terkait dengan pengguna tertentu
        $userBusStations = UserBusStation::where('user_id', $userId)->get();
        $totalBusStations = $userBusStations->unique('bus_station_id')->count();


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
            ->when($uptId, function ($query, $uptId) {
                return $query->where('busses.id_upt', $uptId);
            });

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

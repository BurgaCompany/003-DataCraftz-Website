<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Role;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReservationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = Reservation::with([
            'schedule.bus' => function ($query) {
                $query->withTrashed();
            },
            'user'
        ]);

        if ($user->hasRole('Admin')) {
            $busStationIds = DB::table('admin_bus_station')
                ->where('user_id', $user->id)
                ->pluck('bus_station_id')->toArray();

            $scheduleIds = DB::table('schedules')
                ->whereIn('from_station_id', $busStationIds)
                ->pluck('id')
                ->toArray();

            $query->where(function ($query) use ($scheduleIds) {
                $query->whereIn('schedule_id', $scheduleIds);
            });
        } elseif ($user->hasRole('PO')) {
            $busStationIds = DB::table('busses')
                ->where('id_po', $user->id)
                ->pluck('id')->toArray();

            $query->whereIn('bus_id', $busStationIds);
        } elseif ($user->hasRole('Upt')) {
            $busStationIds = DB::table('user_bus_station')
                ->where('user_id', $user->id)
                ->pluck('bus_station_id')->toArray();

            $scheduleIds = DB::table('schedules')
                ->whereIn('from_station_id', $busStationIds)
                ->pluck('id')
                ->toArray();

            $query->where(function ($query) use ($scheduleIds) {
                $query->whereIn('schedule_id', $scheduleIds);
            });
        }

        $reservations = $query->get();
        //dd($reservations);

        foreach ($reservations as $reservation) {
            $reservation->saldo = Reservation::where('schedule_id', $reservation->schedule_id)
                ->where('payment_method', 'Offline');
        }

        // Menghitung total saldo
        $totalSaldo = $reservations->sum('total_price');

        return view('reservations.index', compact('reservations', 'totalSaldo'));
    }

    public function searchUser(Request $request)
    {
        $phoneNumber = $request->query('phone_number');
        $name = $request->query('name');

        $query = User::query();

        if ($phoneNumber) {
            $query->where('phone_number', $phoneNumber);
        }
        if ($name) {
            $query->where('name', 'like', '%' . $name . '%');
        }
        $user = $query->first();

        if ($user) {
            return response()->json([
                'id' => $user->id,
                'name' => $user->name,
                'address' => $user->address,
                'phone_number' => $user->phone_number,
            ]);
        } else {
            return response()->json('');
        }
    }

    public function create(Request $request)
    {
        $roles = Role::all();
        $scheduleId = $request->query('schedule_id');

        $schedule = Schedule::with('bus')->find($scheduleId);

        if (!$schedule) {
            abort(404, 'Schedule not found');
        }

        $fromStation = DB::table('bus_stations')->where('id', $schedule->from_station_id)->value('name');
        $toStation = DB::table('bus_stations')->where('id', $schedule->to_station_id)->value('name');

        $today = Carbon::today()->toDateString();

        $reservedCount = Reservation::where('schedule_id', $schedule->id)
            ->where('date_departure', $today)
            ->sum('tickets_booked');

        $availableChairs = $schedule->bus->chair - $reservedCount;
        return view('reservations.create', [
            'roles' => $roles,
            'scheduleId' => $scheduleId,
            'fromStation' => $fromStation,
            'toStation' => $toStation,
            'price' => $schedule->price,
            'availableChairs' => $availableChairs,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone_number' => 'required',
            'address' => 'required|string|max:255',
            'total_price' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $scheduleId = $request->input('schedule_id');
        if (!$scheduleId) {
            return redirect()->back()->with('error', 'Schedule ID is missing.')->withInput();
        }

        $schedule = Schedule::find($scheduleId);

        if (!$schedule) {
            return redirect()->back()->with('error', 'Schedule not found.')->withInput();
        }
        $user = User::where('phone_number', $request->input('phone_number'))->first();

        if (!$user) {
            $user = User::create([
                'name' => $request->input('name'),
                'phone_number' => $request->input('phone_number'),
                'address' => $request->input('address'),
            ]);
            $user->assignRole('Passenger');
        }

        $dateNow = Carbon::now();
        $stationId = $schedule->from_station_id;
        $yearLastTwoDigits = substr($dateNow->year, -2);
        $orderId = "{$user->id}{$yearLastTwoDigits}{$dateNow->format('dmHi')}{$stationId}";
        $total_price = $request->input('total_price');

        $total_price_cleaned = str_replace('.', '', $total_price);

        $total_price_int = intval($total_price_cleaned);

        $reservation = Reservation::create([
            'order_id' => $orderId,
            'user_id' => $user->id,
            'bus_id' => $schedule->bus_id,
            'schedule_id' => $schedule->id,
            'tickets_booked' => $request->input('tickets_booked'),
            'date_departure' => Carbon::now()->toDateString(),
            'total_price' => $total_price_int,
            'status' => 'Berhasil Dibayar',
            'payment_method' => 'Offline',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        return redirect()->route('reservations.print', $reservation->id)->with('success', 'Reservasi berhasil dibuat.');
    }

    public function print($id)
    {
        $reservation = Reservation::with(['user', 'schedule.bus'])->findOrFail($id);

        $fromStation = DB::table('bus_stations')->where('id', $reservation->schedule->from_station_id)->value('name');
        $toStation = DB::table('bus_stations')->where('id', $reservation->schedule->to_station_id)->value('name');

        return view('reservations.print', [
            'reservation' => $reservation,
            'fromStation' => $fromStation,
            'toStation' => $toStation,
        ]);
    }
}

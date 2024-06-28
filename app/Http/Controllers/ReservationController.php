<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Deposits;
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

        // Menentukan schedule IDs berdasarkan peran pengguna
        if ($user->hasRole('Admin')) {
            $busStationIds = DB::table('admin_bus_station')
                ->where('user_id', $user->id)
                ->pluck('bus_station_id')
                ->toArray();

            $scheduleIds = DB::table('schedules')
                ->whereIn('from_station_id', $busStationIds)
                ->pluck('id')
                ->toArray();

            $query->whereIn('schedule_id', $scheduleIds);
        } elseif ($user->hasRole('PO')) {
            $busIds = DB::table('busses')
                ->where('id_po', $user->id)
                ->pluck('id')
                ->toArray();

            $query->whereIn('bus_id', $busIds);
        } elseif ($user->hasRole('Upt')) {
            $busStationIds = DB::table('user_bus_station')
                ->where('user_id', $user->id)
                ->pluck('bus_station_id')
                ->toArray();

            $scheduleIds = DB::table('schedules')
                ->whereIn('from_station_id', $busStationIds)
                ->pluck('id')
                ->toArray();

            $query->whereIn('schedule_id', $scheduleIds);
        }

        $reservations = $query->get();

        $totalSaldo = 0; // Inisialisasi totalSaldo
        $amountCount = 0; // Inisialisasi amountCount untuk peran PO

        $scheduleIds = $reservations->pluck('schedule_id')->unique(); // Collect unique schedule IDs

        foreach ($scheduleIds as $scheduleId) {
            if ($user->hasRole('PO')) {
                // Calculate balance for each schedule based on conditions
                $saldo = Reservation::where('schedule_id', $scheduleId)
                    ->where('deposit_status', 'Done')
                    ->where('reqs_status', 'Pending')
                    ->sum('total_price');

                $amountCount += $saldo;
            } else {
                $saldo = Reservation::where('schedule_id', $scheduleId)
                    ->where('payment_method', 'Offline')
                    ->where('deposit_status', 'Pending')
                    ->sum('total_price');

                // Accumulate balance into totalSaldo
                $totalSaldo += $saldo;
            }
        }

        // Jika pengguna bukan Admin, ambil balance dari tabel users
        if ($user->hasAnyRole(['PO', 'Upt', 'Root'])) {
            $totalSaldo = $user->balance;
        }

        $banks = [];
        if ($user->hasRole('Admin')) {
            // Ambil ID PO dari akun yang sedang login
            $id_upt = $user->id_upt;
            $banks = Bank::where('user_id', $id_upt)->get();
        } elseif ($user->hasRole('PO') || $user->hasRole('Upt')) {
            $banks = Bank::where('user_id', 1)->get();
        }

        return view('reservations.index', compact('reservations', 'totalSaldo', 'amountCount', 'banks'));
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
            'status' => 1,
            'payment_method' => 'Offline',
            'deposit_status' => 'Pending',
            'reqs_status' => 'Pending',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        return redirect()->route('reservations.print', $reservation->id)->with('success', 'Reservasi berhasil dibuat.');
    }

    public function depo_up(Request $request)
    {
        // Validasi request
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'bank_account' => 'required|exists:banks,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ubah sesuai kebutuhan
        ]);

        // Handle file upload if there's an image in the request
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('deposits', 'public');
        } else {
            $imagePath = null;
        }

        $user = auth()->user();
        $busStationIds = $user->adminBusStations()->pluck('id')->toArray();

        // Pilih salah satu stasiun yang akan digunakan dalam deposit
        $busStationId = reset($busStationIds); // Misalnya, ambil stasiun pertama dari array

        // Buat objek Deposit baru
        $deposit = new Deposits();
        $deposit->user_id = Auth::id(); // Ambil ID user yang sedang login
        $deposit->bank_id = $request->bank_account;
        $deposit->amount = $request->amount;

        if ($user->hasRole('PO')) {
            $deposit->type = 'req';
        } else {
            $deposit->type = 'send';
        }


        $deposit->images = $imagePath ? $imagePath : null;

        $user = auth()->user();

        if ($user->hasRole('Admin')) {
            // Jika role Admin, atur bus_station_id
            $deposit->bus_station_id = $busStationId;
        }

        // Simpan data deposit ke dalam database
        $deposit->save();


        if ($user->hasRole('Admin')) {
            $id_upt = $user->id_upt;

            // Update balance user yang terkait dengan ID UPT
            $uptUser = User::find($id_upt);

            if ($uptUser) {
                $uptUser->balance += $request->amount; // Tambahkan nilai amount ke balance
                $uptUser->save();
            } else {
                // Handle jika user dengan ID UPT tidak ditemukan
                return redirect()->back()->with('error', 'User dengan ID UPT tidak ditemukan.');
            }
        } elseif ($user->hasRole('Upt')) {
            $adminUser = User::find(1); // Mengasumsikan ID user admin adalah 1, sesuaikan dengan kebutuhan

            if ($adminUser) {
                $adminUser->balance += $request->amount; // Tambahkan nilai amount ke balance admin
                $adminUser->save();

                // Kosongkan balance Upt (pengguna saat ini)
                $user->balance = 0;
                $user->save();
            } else {
                // Handle jika user admin tidak ditemukan
                return redirect()->back()->with('error', 'User admin tidak ditemukan.');
            }
        }


        $reservations = Reservation::get();

        // Iterasi dan ubah deposit_status di setiap reservasi
        foreach ($reservations as $reservation) {
            $reservation->deposit_status = 'Done';
            if ($user->hasRole('PO')) {
                $reservation->reqs_status = 'Done';
            }
            $reservation->save();
        }



        // Redirect dengan pesan sukses atau kustomisasi sesuai kebutuhan
        return redirect()->back()->with('success', 'Setoran berhasil diajukan. Menunggu konfirmasi.');

        // Alternatif: Tampilkan view atau response JSON sesuai kebutuhan aplikasi
        return view('reservations.index');
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

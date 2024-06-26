<?php

namespace App\Http\Controllers;

use App\Models\Buss;
use App\Models\BusStation;
use App\Models\DriverConductorBus;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;


class ScheduleController extends Controller
{

    public function index()
    {
        // Get the logged-in user
        $user = auth()->user();

        // Initialize the query with bus schedules and their trashed buses
        $query = Schedule::with([
            'bus' => function ($query) {
                $query->withTrashed();
            }
        ]);

        // Check if the user has the 'Admin' role
        if ($user->hasRole('Admin')) {
            // Get the bus station IDs associated with the user
            $busStationIds = DB::table('admin_bus_station')
                ->where('user_id', $user->id)
                ->pluck('bus_station_id')->toArray();

            // Filter the schedules by the bus station IDs for both from_station and to_station
            $query->where(function ($query) use ($busStationIds) {
                $query->whereIn('from_station_id', $busStationIds)
                    ->orWhereIn('to_station_id', $busStationIds);
            });
        } elseif ($user->hasRole('PO')) {
            // If the user has role 'Po', filter schedules by bus ID associated with Po
            $busStationIds = DB::table('busses')
                ->where('id_po', $user->id) // Assuming the field name is 'po_id' in 'buses' table
                ->pluck('id')->toArray();

            // Filter schedules by bus IDs associated with the Po
            $query->whereIn('bus_id', $busStationIds);
        } elseif ($user->hasRole('Upt')) {
            // If the user has role 'Upt', filter schedules by bus ID associated with Upt
            $busStationIds = DB::table('user_bus_station')
                ->where('user_id', $user->id)
                ->pluck('bus_station_id')->toArray();

            $query->where(function ($query) use ($busStationIds) {
                $query->whereIn('from_station_id', $busStationIds)
                    ->orWhereIn('to_station_id', $busStationIds);
            });
        } else {
            $busStationIds = [];
        }

        // Get the filtered or unfiltered schedules
        $schedules = $query->get();

        // Return the view with the schedules
        return view('schedules.index', compact('schedules', 'busStationIds'));
    }

    public function search(Request $request)
    {
        $search = $request->input('search');

        $query = Schedule::with([
            'bus' => function ($query) {
                $query->withTrashed();
            },
            'fromStation',
            'toStation'
        ]);

        // Pencarian berdasarkan nama bus atau nama terminal tujuan
        if ($search) {
            $query->whereHas('bus', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            })->orWhereHas('toStation', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        $schedules = $query->get();

        return view('schedules.index', compact('schedules'));
    }


    // routes/web.php atau di controller lainnya

    public function create()
    {
        // Ambil data bus dari model Bus
        $busses = Buss::all();
        $busStations = BusStation::all();

        return view('schedules.create', compact('busses', 'busStations'));
    }



    public function store(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'busses.*' => 'required|exists:busses,id',
                'frombusStations.*' => 'required|exists:bus_stations,id',
                'tobusStations.*' => 'required|exists:bus_stations,id|different:frombusStations.*',
                'min_price' => 'required|numeric',
                'max_price' => 'required|numeric',
                'time_start' => 'required|date_format:H:i',
                'time_arrive' => 'required|date_format:H:i',
            ]);

            // Simpan jadwal baru ke database
            foreach ($request->busses as $key => $busId) {
                // Periksa apakah bus_id tidak kosong
                if (!empty($busId)) {
                    $driver_id = DriverConductorBus::where('bus_id', $busId)->first();
                    if (!$driver_id) {
                        return back()->with('error', 'Driver belum ditentukan');
                    }

                    Schedule::create([
                        'bus_id' => $busId,
                        'id_driver' => $driver_id->id,
                        'from_station_id' => $request->frombusStations[$key],
                        'to_station_id' => $request->tobusStations[$key],
                        'min_price' => $request->min_price,
                        'max_price' => $request->max_price,
                        'price' => $request->min_price,
                        'time_start' => $request->time_start,
                        'time_arrive' => $request->time_arrive,
                        'created_at' => Carbon::now(),
                    ]);
                }
            }

            return redirect()->route('schedules.index')->with('message', 'Berhasil menambah data');
        } catch (ValidationException $e) {
            // Tangkap pengecualian jika validasi gagal
            $errors = $e->validator->errors()->messages();
            return back()->withErrors($errors)->withInput();
        }
    }


    public function detail($id)
    {
        $schedules = Schedule::findOrFail($id);
        $busses = Buss::all();
        $busStations = BusStation::all();

        return view('schedules.detail', compact('schedules', 'busses', 'busStations'));
    }

    public function edit($id)
    {
        $schedules = Schedule::findOrFail($id);
        $busses = Buss::all();
        $busStations = BusStation::all();

        return view('schedules.edit', compact('schedules', 'busses', 'busStations'));
    }

    public function update(Request $request, $id)
    {
        try {
            $schedule = Schedule::findOrFail($id);

            // Validasi input
            $request->validate([
                'busses.*' => 'required|exists:busses,id',
                'frombusStations.*' => 'required|exists:bus_stations,id',
                'tobusStations.*' => 'required|exists:bus_stations,id|different:frombusStations.*',
                'min_price' => 'numeric',
                'max_price' => 'numeric',
                'price' => 'nullable|numeric',
                'time_start' => 'date_format:H:i',
                'time_arrive' => 'date_format:H:i',
            ]);

            // Check if the price field is filled
            if ($request->filled('price')) {
                // Update only the price field
                $schedule->update([
                    'price' => $request->input('price'),
                ]);
            } else {
                // Update other fields if price is not filled
                $schedule->update([
                    'bus_id' => $request->input('busses')[0], // Menggunakan hanya nilai pertama dari busses
                    'from_station_id' => $request->input('frombusStations')[0], // Menggunakan hanya nilai pertama dari frombusStations
                    'to_station_id' => $request->input('tobusStations')[0], // Menggunakan hanya nilai pertama dari tobusStations
                    'min_price' => $request->input('min_price'),
                    'max_price' => $request->input('max_price'),
                    'time_start' => $request->input('time_start'),
                    'time_arrive' => $request->input('time_arrive'),
                ]);
            }

            return redirect()->route('schedules.index')->with('message', 'Jadwal berhasil diperbarui');
        } catch (ValidationException $e) {
            // Tangkap pengecualian jika validasi gagal
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            // Tangani kesalahan lainnya
            return back()->with('error', 'Terjadi kesalahan saat memperbarui jadwal')->withInput();
        }
    }


    public function destroyMulti(Request $request)
    {
        // Validasi data yang diterima
        $request->validate([
            'ids' => 'required|array', // Pastikan ids adalah array
            'ids.*' => 'exists:schedules,id', // Pastikan setiap id ada dalam basis data Anda
        ]);

        // Lakukan penghapusan data berdasarkan ID yang diterima
        Schedule::whereIn('id', $request->ids)->delete();

        // Redirect ke halaman sebelumnya atau halaman lain yang sesuai
        return redirect()->route('schedules.index')->with('message', 'Berhasil menghapus data');
    }
}

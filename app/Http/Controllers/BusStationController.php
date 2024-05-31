<?php

namespace App\Http\Controllers;

use App\Models\AdminBusStation;
use App\Models\BusStation;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\UserBusStation;


class BusStationController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $userBusStations = UserBusStation::with('busStation')->where('user_id', $userId)->get();
        return view('bus_stations.index', compact('userBusStations'));
    }

    public function create()
    {
        $userId = Auth::id();

        // Fetch all admins who have the role 'Admin' and meet certain conditions
        $admins = User::role('Admin')
            ->whereDoesntHave('adminBusStations')
            ->where('id_upt', $userId)
            ->get();
        // Pass the fetched data to the view
        return view('bus_stations.create', compact('admins'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'address' => 'required|string',
            'admin' => 'nullable|array', // Bidang 'admin' bisa null atau array
            // Validasi untuk memastikan bahwa setidaknya satu admin dipilih
        ]);

        $userId = Auth::id();

        // Membuat stasiun bus baru
        $busStation = BusStation::create([
            'name' => $request->name,
            'address' => $request->address,
        ]);

        // Menyimpan relasi antara admin (pengguna) yang sedang login dan stasiun bus yang baru dibuat
        UserBusStation::create([
            'user_id' => $userId,
            'bus_station_id' => $busStation->id,
        ]);

        // Menyimpan relasi antara admin yang dipilih dan stasiun bus yang baru dibuat
        if ($request->filled('admin')) {
            foreach ($request->admin as $adminId) {
                AdminBusStation::create([
                    'user_id' => $adminId,
                    'bus_station_id' => $busStation->id,
                ]);
            }
        }


        // Redirect kembali ke halaman indeks stasiun bus dengan pesan sukses
        return redirect()->route('bus_stations.index')->with('message', 'Berhasil menambah data');
    }


    public function detail($id)
    {
        $userId = Auth::id();
        $busStation = BusStation::findOrFail($id);

        // Cek apakah stasiun bus dengan ID yang diberikan terkait dengan pengguna
        $userBusStation = UserBusStation::where('user_id', $userId)
            ->where('bus_station_id', $id)
            ->first();

        // Jika tidak ditemukan, tolak akses atau alihkan pengguna
        if (!$userBusStation) {
            return redirect()->route('bus_stations.index')->with('error', 'Anda tidak memiliki akses ke stasiun bus ini.');
        }

        $adminBusStations = AdminBusStation::where('bus_station_id', $busStation->id)->get();
        $selectedAdmins = $adminBusStations->pluck('user_id')->toArray();

        $admins = User::role('Admin')
            ->where(function ($query) use ($busStation) {
                $query->whereHas('adminBusStations', function ($query) use ($busStation) {
                    $query->where('bus_station_id', $busStation->id);
                })->orWhereDoesntHave('adminBusStations');
            })
            ->where('id_upt', $userId)
            ->get();


        return view('bus_stations.detail', compact('busStation', 'admins', 'selectedAdmins'));
    }

    public function edit($id)
    {
        $busStation = BusStation::findOrFail($id);
        return view('bus_stations.edit', compact('busStation'));
    }

    public function update(Request $request, $id)
    {
        $busStation = BusStation::findOrFail($id);
        $request->validate([
            'name' => 'required|string',
            'address' => 'required|string',
            'admin' => 'nullable|array', // Bidang 'admin' bisa null atau array
            // Validasi untuk memastikan bahwa setidaknya satu admin dipilih
        ]);

        $busStation->name = $request->name;
        $busStation->address = $request->address;


        $busStation->save();

        $previousAdmins = $busStation->admins()->pluck('user_id')->toArray();
        if ($request->has('id_admin')) {
            foreach ($request->id_admin as $adminId) {
                AdminBusStation::updateOrCreate(
                    ['user_id' => $adminId, 'bus_station_id' => $busStation->id],
                    ['user_id' => $adminId, 'bus_station_id' => $busStation->id]
                );
            }
        }

        // Hapus admin yang dihapus dari select
        $removedAdmins = array_diff($previousAdmins, (array)$request->id_admin);
        AdminBusStation::whereIn('user_id', $removedAdmins)->where('bus_station_id', $busStation->id)->delete();

        return redirect()->route('bus_stations.index')->with('success', 'Admin berhasil ditambahkan ke stasiun bus.');
    }

    public function destroyMulti(Request $request)
    {
        // Validasi data yang diterima
        $request->validate([
            'ids' => 'required|array', // Pastikan ids adalah array
            'ids.*' => 'exists:bus_stations,id', // Pastikan setiap id ada dalam basis data Anda
        ]);

        // Lakukan penghapusan data berdasarkan ID yang diterima
        BusStation::whereIn('id', $request->ids)->delete();

        // Redirect ke halaman sebelumnya atau halaman lain yang sesuai
        return redirect()->route('bus_stations.index')->with('message', 'Berhasil menghapus data');
    }
}

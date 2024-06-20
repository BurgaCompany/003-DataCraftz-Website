<?php

namespace App\Http\Controllers;

use App\Models\Buss;
use App\Models\DriverConductorBus;
use App\Models\TrackBus;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BussesController extends Controller
{
    public function getCoordinates($id)
    {
        // Mengambil koordinat terbaru dari tabel 'track_bus' berdasarkan 'bus_id'
        $coordinates = TrackBus::where('bus_id', $id)->orderBy('created_at', 'desc')->first();
        return response()->json($coordinates);
    }

    public function index()
    {
        // Pastikan pengguna telah diautentikasi
        if (Auth::check()) {
            // Ambil peran pengguna yang masuk
            $user = Auth::user();
            // Periksa apakah pengguna memiliki peran PO atau Admin
            if ($user->hasRole('PO') || $user->hasRole('Admin')) {
                // Tentukan ID pengguna yang akan digunakan dalam kueri
                $userId = $user->id;

                // Mulai membangun kueri untuk mendapatkan busses
                $bussesQuery = DB::table('busses')
                    ->leftJoin('driver_conductor_bus', 'busses.id', '=', 'driver_conductor_bus.bus_id')
                    ->leftJoin('users as drivers', 'driver_conductor_bus.driver_id', '=', 'drivers.id')
                    ->select(
                        'busses.*',
                        'drivers.name as driver_name',
                    )
                    ->whereNull('busses.deleted_at'); // Tambahkan klausa whereNull untuk mengecualikan data yang dihapus

                // Tambahkan kondisi where id_po jika pengguna memiliki peran PO
                if ($user->hasRole('PO')) {
                    $bussesQuery->where('busses.id_po', $userId);
                }

                // Tambahkan pengurutan dan paginasi
                $busses = $bussesQuery->orderBy('busses.id', 'asc')->paginate(15);

                // Mengembalikan data tersebut ke view
                return view('busses.index', compact('busses'));
            }
        }

        // Jika pengguna tidak diautentikasi atau tidak memiliki peran yang sesuai, Anda dapat mengarahkan mereka ke halaman lain
        return redirect()->route('login'); // atau halaman lain yang sesuai
    }



    public function search(Request $request)
    {
        // Pastikan pengguna telah diautentikasi
        if (Auth::check()) {
            // Ambil peran pengguna yang masuk
            $user = Auth::user();
            // Periksa apakah pengguna memiliki peran Upt atau Admin
            if ($user->hasRole('PO') || $user->hasRole('Admin')) {
                // Tentukan ID Upt yang akan digunakan dalam kueri

                $searchTerm = $request->input('search');

                $busses = Buss::where(function ($query) use ($searchTerm) {
                    $query->where('name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('license_plate_number', 'like', '%' . $searchTerm . '%');
                })
                    ->paginate(15);

                return view('busses.index', compact('busses'));
            }
        }
    }

    public function create()
    {
        $userId = Auth::id();

        // Fetch all admins who have the role 'Admin' and meet certain conditions
        $drivers = User::role('Driver')
            ->whereDoesntHave('driverBus')
            ->where('id_po', $userId)
            ->get();
        // Pass the fetched data to the view
        return view('busses.create', compact('drivers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'license_plate_number' => [
                'required',
                Rule::unique('busses')
            ],
            'chair' => 'required',
            'class' => 'required',
            'status' => 'required',
            'drivers' => 'nullable|array',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Menghapus spasi dari nomor plat kendaraan
        $licensePlateNumber = str_replace(' ', '', $request->license_plate_number);

        // Handle image upload
        $image = $request->file('image');
        if ($image) {
            // Store the uploaded image in the 'avatars' directory
            $imageName = $image->store('avatars');
        } else {
            // Menentukan jalur gambar default berdasarkan gender
            $defaultImagePath =  'assets/images/avatars/bus.jpg';

            // Cek apakah file gambar default ada
            $defaultImageExists = file_exists(public_path($defaultImagePath));

            // Debugging: Dump hasil pemeriksaan
            // dd($defaultImageExists);

            // Nama file gambar default
            $defaultImageName = basename($defaultImagePath); // Misalnya, 'male.jpg'
            $imageName = 'avatars/' . $defaultImageName;

            // Cek apakah gambar tidak ada di direktori 'avatars'
            if (!Storage::disk('public')->exists($imageName)) {
                // Jalur lengkap ke gambar tujuan di storage publik
                $destinationPath = public_path('storage/' . $imageName);

                // Buat direktori tujuan jika belum ada
                if (!file_exists(dirname($destinationPath))) {
                    mkdir(dirname($destinationPath), 0755, true);
                }

                // Salin gambar default ke direktori 'avatars'
                $copySuccess = copy(public_path($defaultImagePath), $destinationPath);

                // Debugging: Dump hasil penyalinan
                // dd($copySuccess);
            }
        }

        $userId = Auth::id();

        // Mengubah huruf menjadi kapital
        $licensePlateNumber = strtoupper($request->input('license_plate_number'));

        $bus = Buss::create([
            'name' => $request->name,
            'license_plate_number' => $licensePlateNumber,
            'chair' => $request->chair,
            'class' => $request->class,
            'status' => $request->status, // Menambahkan status dari formulir
            'information' => $request->status == 'Terkendala' ? $request->keterangan : null, // Menambahkan keterangan jika status adalah 4 (Terkendala)
            'images' => $imageName,
            'id_po' => $userId, // Menambahkan id_upt dari pengguna yang sedang masuk
            'created_at' => Carbon::now(),
        ]);

        $bus->save();

        // Menyimpan relasi antara driver dan bus conductor yang dipilih dan bus yang baru dibuat
        if ($request->filled('drivers')) {
            foreach ($request->drivers as $driverId) {

                DriverConductorBus::create([
                    'driver_id' => $driverId,
                    'bus_id' => $bus->id,
                ]);
            }
        }

        return redirect()->route('busses.index')->with('message', 'Berhasil menambah data');
    }





    public function detail($id)
    {
        $user = Auth::user();
        $userId = $user->id;
        $bus = Buss::findOrFail($id);
        if ($user->hasRole('PO') && $userId != $bus->id_po) {
            return redirect()->route('busses.index')->with('error', 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        $driveconduc = DriverConductorBus::where('bus_id', $bus->id)->get();
        $assignedDrivers = $driveconduc->pluck('driver_id')->toArray();

        // Retrieve drivers based on user role
        $driversQuery = User::role('Driver')
            ->where(function ($query) use ($bus) {
                $query->whereHas('driverBus', function ($query) use ($bus) {
                    $query->where('bus_id', $bus->id);
                })->orWhereDoesntHave('driverBus');
            });

        // Add additional condition if the user is not an Admin
        if (!$user->hasRole('Admin')) {
            $driversQuery->where('id_po', $userId);
        }
        $drivers = $driversQuery->get();
        return view('busses.detail', [
            'bus' => $bus,
            'drivers' => $drivers,
            'assignedDrivers' => $assignedDrivers,
        ]);
    }


    public function edit($id)
    {
        $user = Auth::user();
        $userId = $user->hasRole('PO') ? $user->id : ($user->hasRole('Admin') ? $user->id_po : null);

        $bus = Buss::findOrFail($id);

        // Periksa apakah ID pengguna yang sedang login sama dengan id_upt dari bus
        if ($userId != $bus->id_po) {
            // Jika tidak sama, redirect atau tampilkan pesan error
            return redirect()->route('busses.index')->with('error', 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        $driveconduc = DriverConductorBus::where('bus_id', $bus->id)->get();

        //dd($driveconduc);
        $assignedDrivers = $driveconduc->pluck('driver_id')->toArray();
        $drivers = User::role('Driver')
            ->where(function ($query) use ($bus) {
                $query->whereHas('driverBus', function ($query) use ($bus) {
                    $query->where('bus_id', $bus->id);
                })->orWhereDoesntHave('driverBus');
            })
            ->where('id_po', $userId)
            ->get();

        return view('busses.edit', compact('bus', 'drivers',  'assignedDrivers'));
    }


    public function update(Request $request, $id)
    {
        $bus = Buss::findOrFail($id);

        // Mencari bus dengan nomor plat kecuali bus yang sedang diupdate
        $bus_license = Buss::where('license_plate_number', $request->input('license_plate_number'))
            ->where('id', '!=', $id)
            ->first();

        // Validasi data yang diterima dari formulir
        $request->validate([
            'name' => 'required',
            'license_plate_number' => [
                'required',
                Rule::unique('busses')->ignore($bus->id),
            ],
            'chair' => 'required',
            'class' => 'required',
            'status' => 'required',
            'images' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image upload
        $image = $request->file('image');
        if ($image) {
            // Store the uploaded image in the 'avatars' directory
            $imageName = $image->store('avatars');
        } else {
            // Menentukan jalur gambar default berdasarkan gender
            $defaultImagePath =  'assets/images/avatars/bus.jpg';

            // Cek apakah file gambar default ada
            $defaultImageExists = file_exists(public_path($defaultImagePath));

            // Debugging: Dump hasil pemeriksaan
            // dd($defaultImageExists);

            // Nama file gambar default
            $defaultImageName = basename($defaultImagePath); // Misalnya, 'male.jpg'
            $imageName = 'avatars/' . $defaultImageName;

            // Cek apakah gambar tidak ada di direktori 'avatars'
            if (!Storage::disk('public')->exists($imageName)) {
                // Jalur lengkap ke gambar tujuan di storage publik
                $destinationPath = public_path('storage/' . $imageName);

                // Buat direktori tujuan jika belum ada
                if (!file_exists(dirname($destinationPath))) {
                    mkdir(dirname($destinationPath), 0755, true);
                }

                // Salin gambar default ke direktori 'avatars'
                $copySuccess = copy(public_path($defaultImagePath), $destinationPath);

                // Debugging: Dump hasil penyalinan
                // dd($copySuccess);
            }
        }



        $bus->name = $request->name;
        $bus->license_plate_number = strtoupper($request->license_plate_number);
        $bus->chair = $request->chair;
        $bus->class = $request->class;
        $bus->status = $request->status;
        $bus->information = $request->status == 'Terkendala' ? $request->keterangan : null;
        $bus->images = $imageName;

        $bus->save();

        $previousDrivers = $bus->drivers()->pluck('driver_id')->toArray();

        // Update pengemudi dan kondektur yang terkait dengan bus
        if ($request->filled('drivers')) {
            foreach ($request->drivers as $driverId) {

                DriverConductorBus::updateOrCreate(
                    ['driver_id' => $driverId, 'bus_id' => $bus->id],

                );
            }
        }

        // Hapus pengemudi dan kondektur yang dihapus dari select
        $removedDrivers = array_diff($previousDrivers, (array)$request->drivers);


        foreach ($removedDrivers as $removedDriverId) {
            DriverConductorBus::where('driver_id', $removedDriverId)->where('bus_id', $bus->id)->delete();
        }



        return redirect()->route('busses.index')->with('message', 'Data berhasil diperbarui');
    }



    public function destroyMulti(Request $request)
    {
        // Validasi data yang diterima
        $request->validate([
            'ids' => 'required|array', // Pastikan ids adalah array
            'ids.*' => 'exists:busses,id', // Pastikan setiap id ada dalam basis data Anda
        ]);

        // Lakukan penghapusan data berdasarkan ID yang diterima
        Buss::whereIn('id', $request->ids)->delete();

        // Redirect ke halaman sebelumnya atau halaman lain yang sesuai
        return redirect()->route('busses.index')->with('message', 'Berhasil menghapus data');
    }
}

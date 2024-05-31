<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // Menampilkan daftar pengguna
    public function index()
    {
        // Mendapatkan ID pengguna yang sedang masuk
        $userId = Auth::id();

        $admins = User::role('Admin')
            ->leftJoin('admin_bus_station', 'users.id', '=', 'admin_bus_station.user_id')
            ->leftJoin('bus_stations', 'admin_bus_station.bus_station_id', '=', 'bus_stations.id')
            ->where('users.id_upt', $userId) // Menambahkan kondisi pengecekan id_upt
            ->select('users.*', 'bus_stations.name as terminal_name')
            ->paginate(15);

        return view('admins.index', compact('admins'));
    }



    public function search(Request $request)
    {
        $userId = Auth::id();
        $searchTerm = $request->input('search');

        $admins = User::role('Admin')
            ->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('address', 'like', '%' . $searchTerm . '%');
            })
            ->where('id_upt', $userId)
            ->paginate(15);

        return view('admins.index', compact('admins'));
    }

    // Menampilkan form untuk membuat pengguna baru
    public function create()
    {
        $roles = Role::all();
        return view('admins.create', ['roles' => $roles]);
    }

    // Menyimpan pengguna baru ke database
    public function store(Request $request)
    {
        // Handle image upload
        $image = $request->file('image');
        if ($image) {
            // Store the uploaded image in the 'avatars' directory
            $imageName = $image->store('avatars');
        } else {
            // Menentukan jalur gambar default berdasarkan gender
            $defaultImagePath = $request->gender == 'Male' ? 'assets/images/avatars/male.jpg' : 'assets/images/avatars/female.jpg';

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
        // Validasi data yang diterima dari form
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users|regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/',
            'password' => 'required|min:8',
            'address' => 'required',
            'gender' => 'required',
            'phone_number' => 'required|unique:users|digits_between:10,13',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $userId = Auth::id();

        // Simpan data pengguna baru ke dalam database
        $admin = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'address' => $request->address,
            'gender' => $request->gender,
            'phone_number' => $request->phone_number,
            'images' => $imageName,
            'id_upt' => $userId, // Menambahkan id_upt dari pengguna yang sedang masuk
            'created_at' => Carbon::now(),
        ]);

        // Cetak variabel $admin
        //dd($admin);

        // Beri peran 'Root' kepada pengguna baru
        $role = Role::findByName('Admin');
        $admin->assignRole($role);

        // Redirect ke halaman daftar pengguna
        return redirect()->route('admins.index')->with('message', 'Berhasil menambah data');
    }


    // Menampilkan form untuk mengedit pengguna
    public function edit($id)
    {
        $userId = Auth::id();

        $admin = User::findOrFail($id);

        // Periksa apakah pengguna memiliki peran 'Driver'
        if (!$admin->hasRole('Admin')) {
            // Jika pengguna bukan seorang 'Driver', redirect atau tampilkan pesan error
            return redirect()->route('admins.index')->with('error', 'Pengguna ini bukan seorang Driver.');
        }

        // Periksa apakah ID pengguna yang sedang login sama dengan id_upt dari admin
        if ($userId != $admin->id_upt) {
            // Jika tidak sama, redirect atau tampilkan pesan error
            return redirect()->route('admins.index')->with('error', 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        $roles = Role::all();
        $genders = [
            'male' => 'Laki-Laki',
            'female' => 'Perempuan'
        ];
        return view('admins.edit', ['admin' => $admin, 'roles' => $roles, 'genders' => $genders]);
    }


    public function detail($id)
    {
        // Mendapatkan ID pengguna yang sedang masuk
        $userId = Auth::id();

        // Mendapatkan data admin berdasarkan ID
        $admin = User::findOrFail($id);

        // Periksa apakah pengguna memiliki peran 'Driver'
        if (!$admin->hasRole('Admin')) {
            // Jika pengguna bukan seorang 'Driver', redirect atau tampilkan pesan error
            return redirect()->route('admins.index')->with('error', 'Pengguna ini bukan seorang Driver.');
        }

        // Periksa apakah ID pengguna yang sedang login sama dengan id_upt dari admin
        if ($userId != $admin->id_upt) {
            // Jika tidak sama, redirect atau tampilkan pesan error
            return redirect()->route('admins.index')->with('error', 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        // Jika sama, lanjutkan dengan proses normal
        $roles = Role::all();
        $genders = [
            'male' => 'Laki-Laki',
            'female' => 'Perempuan'
        ];
        return view('admins.detail', ['admin' => $admin, 'roles' => $roles, 'genders' => $genders]);
    }




    public function update(Request $request, $id)
    {

        // Validasi data yang diterima dari form
        $request->validate([
            'name' => 'required',
            'email' => [
                'required',
                'email',
                'unique:users,email,' . $id,
                'regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/'
            ],
            'password' => 'nullable|min:8',
            'address' => 'required',
            'gender' => 'required',
            'phone_number' => 'required|unique:users,phone_number,' . $id . '|min:10|max:13|regex:/^[0-9]+$/',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);


        // Ambil data pengguna yang akan diupdate
        $admin = User::findOrFail($id);

        // Handle image upload
        $image = $request->file('image');
        if ($image) {
            // Store the uploaded image in the 'avatars' directory
            $imageName = $image->store('avatars');
        } else {
            // Menentukan jalur gambar default berdasarkan gender
            $gender = $request->input('gender');

            $defaultImagePath = $gender == 'male' ? 'assets/images/avatars/male.jpg' : 'assets/images/avatars/female.jpg';

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

        // Update data pengguna
        $admin->name = $request->name;
        $admin->email = $request->email;
        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }
        $admin->address = $request->address;
        $admin->gender = $request->gender;
        $admin->phone_number = $request->phone_number;
        $admin->images = $imageName;
        $admin->save();

        // Redirect ke halaman daftar pengguna dengan pesan sukses
        return redirect()->route('admins.index')->with('message', 'Berhasil mengubah data.');
    }



    public function destroyMulti(Request $request)
    {
        // Validasi data yang diterima
        $request->validate([
            'ids' => 'required|array', // Pastikan ids adalah array
            'ids.*' => 'exists:users,id', // Pastikan setiap id ada dalam basis data Anda
        ]);

        // Lakukan penghapusan data berdasarkan ID yang diterima
        User::whereIn('id', $request->ids)->delete();

        // Redirect ke halaman sebelumnya atau halaman lain yang sesuai
        return redirect()->route('admins.index')->with('message', 'Berhasil menghapus data');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Session;

class OtobusController extends Controller
{
    // Menampilkan daftar pengguna
    public function index()
    {
        $otobuses = User::role('PO')->paginate(15); // Menentukan 10 item per halaman

        return view('otobuses.index', compact('otobuses'));
    }


    public function search(Request $request)
    {
        $searchTerm = $request->input('search');

        $otobuses = User::role('PO')
            ->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('address', 'like', '%' . $searchTerm . '%');
            })
            ->paginate(15);

        return view('otobuses.index', compact('otobuses'));
    }

    // Menampilkan form untuk membuat pengguna baru
    public function create()
    {
        $roles = Role::all();
        return view('otobuses.create', ['roles' => $roles]);
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
            $gender = $request->input('gender');
            // Menentukan jalur gambar default berdasarkan gender
            $defaultImagePath = $gender == 'Male' ? 'assets/images/avatars/male.jpg' : 'assets/images/avatars/female.jpg';

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

        // Simpan data pengguna baru ke dalam database
        $otobus = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'address' => $request->address,
            'gender' => $request->gender,
            'phone_number' => $request->phone_number,
            'images' => $imageName,
            'created_at' => Carbon::now(),
        ]);

        // Beri peran 'Upt' kepada pengguna baru
        $role = Role::findByName('PO');
        $otobus->assignRole($role);

        // Redirect ke halaman daftar pengguna
        return redirect()->route('otobuses.index')->with('message', 'Berhasil menambah data');
    }

    // Menampilkan form untuk mengedit pengguna
    public function edit($id)
    {
        $otobus = User::findOrFail($id);
        $roles = Role::all();
        $genders = [
            'male' => 'Laki-Laki',
            'female' => 'Perempuan'
        ];
        return view('otobuses.edit', ['otobus' => $otobus, 'roles' => $roles, 'genders' => $genders]);
    }


    public function detail($id)
    {
        $otobus = User::findOrFail($id);
        $roles = Role::all();
        $genders = [
            'male' => 'Laki-Laki',
            'female' => 'Perempuan'
        ];
        return view('otobuses.detail', ['otobus' => $otobus, 'roles' => $roles, 'genders' => $genders]);
    }



    public function update(Request $request, $id)
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
        $otobus = User::findOrFail($id);

        // Periksa apakah ada file gambar yang diunggah
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            Storage::delete($otobus->images);

            // Simpan file gambar baru ke dalam penyimpanan yang diinginkan
            $imageName = $request->file('image')->store('avatars');

            // Update nama file gambar dalam database
            $otobus->images = $imageName;
        }

        // Update data pengguna
        $otobus->name = $request->name;
        $otobus->email = $request->email;
        if ($request->filled('password')) {
            $otobus->password = Hash::make($request->password);
        }
        $otobus->address = $request->address;
        $otobus->gender = $request->gender;
        $otobus->phone_number = $request->phone_number;
        $otobus->images = $imageName;
        $otobus->save();

        // Redirect ke halaman daftar pengguna dengan pesan sukses
        return redirect()->route('otobuses.index')->with('message', 'Berhasil mengubah data.');
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
        return redirect()->route('upts.index')->with('message', 'Berhasil menghapus data');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function index()
    {
        // Mendapatkan id_upt dari pengguna yang sedang login
        $userId = Auth::id();

        // Mengambil semua data reservasi beserta bus dan user terkait yang memenuhi kondisi
        $reservations = Reservation::whereHas('bus', function ($query) use ($userId) {
            $query->where('id_upt', $userId);
        })->with('bus', 'user')->get();

        //dd($usereservationsrId);

        // Mengirim data reservasi ke view 'reservations.index'
        return view('reservations.index', compact('reservations'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function index()
    {
        // Mengambil semua data reservasi beserta bus dan user terkait
        $reservations = Reservation::with('bus', 'user')->get();

        // Mengirim data reservasi ke view 'reservations.index'
        return view('reservations.index', compact('reservations'));
    }
}

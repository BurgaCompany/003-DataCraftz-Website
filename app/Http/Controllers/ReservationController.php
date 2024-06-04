<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function index()
    {
        // Mengambil semua data reservasi beserta bus, PO, user, dan schedule terkait
        $reservations = Reservation::with([
            'schedule.bus' => function ($query) {
                $query->withTrashed();
            },
            'user'
        ])->get();

        //dd($reservations);

        // Mengirim data reservasi ke view 'reservations.index'
        return view('reservations.index', compact('reservations'));
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FindScheduleByDateResource;
use App\Http\Resources\FindScheduleResource;
use App\Http\Resources\ReservationHistoryResource;
use App\Models\DriverConductorBus;
use App\Models\Reservation;
use App\Models\Schedule;
use Illuminate\Http\Request;

class CondectureController extends Controller
{
    public function validationId(Request $request)
    {
        $order_id = $request->query('order_id');
        $reservation = Reservation::with(['schedule', 'user', 'bus'])->where('order_id', $order_id)->first();
        if ($reservation == null) {
            return response()->json([
                'statusCode' => 400,
                'message' => 'Data not found!',
            ], 404);
        }
        return response()->json([
            'statusCode' => 200,
            'message' => 'Success!',
            'data_reservation_check' => new ReservationHistoryResource($reservation)
        ], 200);
    }

    public function updateStatusReservation(Request $request)
    {
        $order_id = $request->query('order_id');
        $reservation = Reservation::where('order_id', $order_id)->first();
        $reservation->status = 2;
        $reservation->save();
        return response()->json([
            'statusCode' => 200,
            'message' => 'Berhasil Update Status',
        ], 200);
    }
}

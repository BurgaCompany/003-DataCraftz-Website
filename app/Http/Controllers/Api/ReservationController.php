<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    //create a function to add a reservation
    public function addReservation(Request $request)
    {
        $reservation = new Reservation();
        $reservation->user_id = $request->user_id;
        $reservation->bus_id = $request->bus_id;
        $reservation->schedule_id = $request->schedule_id;
        $reservation->tickets_booked = $request->tickets_booked;
        $reservation->date_departure = $request->date_departure;
        $reservation->status = 1;
        $reservation->save();

        return response()->json([
            'statusCode' => 200,
            'message' => 'Success!',
            'result' => $reservation
        ], 200);
    }

    public function ReservationGoOn(Request $request)
    {
        $user_id = $request->query('id');
        try {
            $reservation = Reservation::where('status', 1)->where('user_id', $user_id)->get();
            if ($reservation->isEmpty()) {
                return response()->json([
                    'statusCode' => 400,
                    'message' => 'Data not found!',
                ], 404);
            }
            return response()->json([
                'statusCode' => 200,
                'message' => 'Success!',
                'result' => $reservation
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'statusCode' => 500,
                'message' => 'Error!',
                'result' => $th->getMessage()
            ], 500);
        }
    }

    public function ReservationHistory(Request $request)
    {
        $user_id = $request->query('id');
        try {
            $reservation = Reservation::where('status', 2)->where('user_id', $user_id)->get();
            if ($reservation->isEmpty()) {
                return response()->json([
                    'statusCode' => 400,
                    'message' => 'Data not found!',
                ], 404);
            }
            return response()->json([
                'statusCode' => 200,
                'message' => 'Success!',
                'result' => $reservation
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'statusCode' => 500,
                'message' => 'Error!',
                'result' => $th->getMessage()
            ], 500);
        }
    }


}

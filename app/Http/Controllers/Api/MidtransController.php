<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;
use Midtrans\Notification;
use Midtrans\Snap;

class MidtransController extends Controller
{
    public function show(Request $request)
    {
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');

        $gross_amount = $request->total_price;
        
        $reservation = new Reservation();
        $reservation->order_id = $request->order_id;
        $reservation->user_id = $request->user_id;
        $reservation->bus_id = $request->bus_id;
        $reservation->schedule_id = $request->schedule_id;
        $reservation->tickets_booked = $request->tickets_booked;
        $reservation->date_departure = $request->date_departure;
        $reservation->total_price = $gross_amount;
        $reservation->status = 1;
        

        $id_user = User::where('id', $request->user_id)->first();

        $value = [
            'transaction_details' => [
                'order_id' => intval($request->order_id),
                'gross_amount' => intval($gross_amount),
            ],
            'customer_details' => [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $id_user->email,
                'phone' => $id_user->phone_number,
            ],
        ];

        $midtrans = Snap::createTransaction($value);
        $reservation->token_payment = $midtrans->token;
        $reservation->save();
        return response()->json([
            'snap_token' => $midtrans
        ]);
    }
}

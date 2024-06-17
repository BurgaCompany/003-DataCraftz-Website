<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Midtrans\getSnapToken;
use Illuminate\Http\Request;
use Midtrans\Snap;

class MidtransController extends Controller
{
    public function show(Request $request)
    {
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        $value = [
            'transaction_details' => [
                'order_id' => intval($request->order_id),
                'gross_amount' => intval($request->gross_amount),
            ],
            'customer_details' => [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
            ],
        ];
        $midtrans = Snap::createTransaction($value);
        return response()->json([
            'snap_token' => $midtrans
        ]);
    }
}

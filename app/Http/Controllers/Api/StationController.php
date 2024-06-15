<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\StationResource;
use App\Models\BusStation;
use Illuminate\Http\Request;

class StationController extends Controller
{
    public function allStation() {
        try {
            $station = BusStation::all();
            if ($station->isEmpty()) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Data not found!',
                ]);
            }
            return response()->json([
                'status' => 200,
                'message' => 'Success',
                'data' => StationResource::collection($station)
                // 'data' => $station
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => 'Error',
                'data' => $th->getMessage()
            ]);
        }

    }
}

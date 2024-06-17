<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Buss;
use App\Models\track_bus;
use Illuminate\Http\Request;

class TrackBusController extends Controller
{
    public function updateLocation(Request $request)
    {
        $request->validate([
            'plat_number' => 'required|string',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
        ]);

        $bus = Buss::where('license_plate_number', $request->plat_number)->first();
        if (!$bus) {
            return response()->json([
                'message' => 'Bus not found'
            ], 404);
        }

        $trackBus = track_bus::where('bus_id', $bus->id)->first();

        if ($trackBus) {
            $trackBus->update([
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);
        } else {
            $track = track_bus::create([
                'bus_id' => $bus->id,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);
            return response()->json([
                'message' => 'Location updated',
                'data' => $track
            ]);
        }

        return response()->json([
            'message' => 'Location updated',
            'data' => $trackBus
        ]);
    }
}

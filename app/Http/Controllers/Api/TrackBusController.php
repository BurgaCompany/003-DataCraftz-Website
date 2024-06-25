<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Buss;
use App\Models\BusStation;
use App\Models\track_bus;
use App\Models\TrackBus;
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
            ], 400);
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

    public function getCoordinates(Request $request)
    {
        $buss_id = $request->query('bus_id');
        $buss = TrackBus::where('bus_id', $buss_id)->first();

        return response()->json([
            'latitude' => $buss->latitude,
            'longitude' => $buss->longitude,
        ]);
    }
}

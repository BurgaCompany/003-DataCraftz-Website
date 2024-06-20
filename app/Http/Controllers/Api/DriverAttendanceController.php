<?php
namespace App\Http\Controllers\Api;

use App\Helpers\HttpResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Buss;
use App\Models\Reservation;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DriverAttendanceController extends Controller
{
    protected $responseFormatter;
    protected $user;

    public function __construct(HttpResponseFormatter $responseFormatter)
    {
        $this->responseFormatter = $responseFormatter;
        $this->user = auth('api')->user();
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            // Validasi permintaan
            $rules = [
                'status' => 'required|in:1,2,3,4,5',
            ];

            // Tambahkan validasi untuk information jika status adalah 4
            if ($request->status == 4) {
                $rules['information'] = 'required|string';
            }

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'statusCode' => 400,
                    'message' => 'Error!',
                    'data_driver_attendace' => ['errors' => $validator->errors()]
                ],
                    400
                );
            }

            // Cari bus berdasarkan ID
            $bus = Buss::findOrFail($id);

            // Update status bus
            $bus->status = $request->status;

            // Simpan informasi tambahan jika status adalah 4
            if ($request->status == 4) {
                $bus->information = $request->information;
            }

            $bus->save();
            return response()->json([
                'statusCode' => 200,
                'message' => 'Success!',
                'data_driver_attendace' => $bus,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => 500,
                'message' => 'An error occurred while processing your request.',
                'data_driver_attendace' => ['error' => $e->getMessage()]
            ], 500);
        }
    }
}


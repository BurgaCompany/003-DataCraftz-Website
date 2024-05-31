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
                return $this->responseFormatter->setStatusCode(400)
                    ->setMessage('Validation Error!')
                    ->setResult(['error' => $validator->errors()])
                    ->format();
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

            return $this->responseFormatter->setStatusCode(200)
                ->setMessage('Bus status updated successfully!')
                ->setResult(['bus' => $bus])
                ->format();
        } catch (\Exception $e) {
            return $this->responseFormatter->setStatusCode(500)
                ->setMessage('Error!')
                ->setResult(['error' => $e->getMessage()])
                ->format();
        }
    }
}


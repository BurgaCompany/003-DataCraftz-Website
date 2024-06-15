<?php

namespace App\Http\Controllers\Api;

use App\Helpers\HttpResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Buss;
use App\Models\Reservation;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
{
    protected $responseFormatter;
    protected $user;

    public function __construct(HttpResponseFormatter $responseFormatter)
    {
        $this->responseFormatter = $responseFormatter;
        $this->user = auth('api')->user();
    }

    public function index(Request $request)
    {
        try {
            $schedules = Schedule::with(['bus', 'fromStation', 'toStation'])->get();

            return response()->json([
                'statusCode' => 200,
                'message' => 'Success!',
                'result' =>  $schedules
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => 500,
                'message' => 'Error!',
                'result' => ['errors' => $e->getMessage()]
            ]);
        }
    }

    public function reserveTicket(Request $request)
{
    try {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'schedule_id' => 'required|exists:schedules,id',
            'tickets_booked' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'statusCode' => 400,
                'message' => 'Validation Error!',
                'result' => ['errors' => $validator->errors()]
            ], 400);
        }

        $user = auth('api')->user();

        if (!$user) {
            return response()->json([
                'statusCode' => 401,
                'message' => 'Unauthenticated',
                'result' => ['error' => 'Unauthenticated']
            ], 401);
        }

        // Find the schedule by schedule_id
        $schedule = Schedule::find($request->input('schedule_id'));

        if (!$schedule) {
            return response()->json([
                'statusCode' => 404,
                'message' => 'Schedule Not Found',
                'result' => ['error' => 'Schedule not found']
            ], 404);
        }

        // Find the bus associated with the schedule
        $bus = Buss::find($schedule->bus_id);

        if (!$bus) {
            return response()->json([
                'statusCode' => 404,
                'message' => 'Bus Not Found',
                'result' => ['error' => 'Bus not found']
            ], 404);
        }

        // Check if there are enough chairs available
        if ($bus->chair < $request->tickets_booked) {
            return response()->json([
                'statusCode' => 400,
                'message' => 'Not Enough Chairs',
                'result' => ['error' => 'Not enough chairs available']
            ], 400);
        }

        // Check if there is an existing reservation for the user and schedule
        $reservation = Reservation::where('user_id', $user->id)
            ->where('schedule_id', $schedule->id)
            ->where('status', 1)
            ->first();

        if ($reservation) {
            // If there is, add the booked tickets to the existing reservation
            $reservation->tickets_booked += $request->tickets_booked;
            $reservation->save();
        } else {
            // If not, create a new reservation
            $reservation = Reservation::create([
                'user_id' => $user->id,
                'bus_id' => $bus->id,
                'schedule_id' => $schedule->id,
                'tickets_booked' => $request->tickets_booked,
                'date_departure' => $request->departure_date,
                'status' => 1, // Status 1: reserved
            ]);
        }

        // Decrease the number of chairs available in the bus
        $bus->chair -= $request->tickets_booked;
        $bus->save();

        // Load the user's name
        $reservation->load('user');

        return response()->json([
            'statusCode' => 201,
            'message' => 'Reservation Successful!',
            'result' => $reservation
        ], 201);
    } catch (\Exception $e) {
        return response()->json([
            'statusCode' => 500,
            'message' => 'Error!',
            'result' => ['error' => $e->getMessage()]
        ], 500);
    }
}

    public function updateReserveTicket(Request $request)
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'reservation_id' => 'required',
                'status' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'statusCode' => 400,
                    'message' => 'Validation Error!',
                    'result' =>  $validator->errors()
                ], 400);
            }

            $user = auth('api')->user();

            if (!$user) {
                return response()->json([
                    'statusCode' => 401,
                    'message' => 'Unauthenticated',
                    'result' =>  'Unauthenticated'
                ], 401);
            }

            // Find the reservation by ID
            $reservation = Reservation::findOrFail($request->reservation_id);

            // Update the status
            $reservation->status = $request->status;
            $reservation->save();

            return $this->responseFormatter->setStatusCode(201)
                ->setMessage('Update Reservation Successful!')
                ->format();
        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => 500,
                'message' => 'Error!',
                'result' =>  $e->getMessage()
            ], 500);
        }
    }


    public function historyReserve(Request $request)
    {
        try {
            $user = auth('api')->user();

            // Ensure the user is authenticated
            if (!$user) {
                return response()->json(['error' => 'Unauthenticated'], 401);
            }

            // Retrieve reservation for the authenticated user
            $schedules = Reservation::with(['schedule', 'schedule.fromStation', 'schedule.toStation', 'schedule.bus'])
                ->where('user_id', $user->id)
                ->get();


            return response()->json([
                'statusCode' => 200,
                'message' => 'Success!',
                'result' =>  $schedules
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => 500,
                'message' => 'Error!',
                'result' =>  $e->getMessage()
            ]);
        }
    }

    public function conductorReserveTicket(Request $request)
    {
        try {
            $user = auth('api')->user();

            // Ensure the user is authenticated
            if (!$user) {
                return response()->json(['error' => 'Unauthenticated'], 401);
            }

            $reservations = Reservation::with([
                'user',
                'bus', // Eager load the related bus data
                'bus.drivers',
                'bus.busConductors',
                'schedule',
                'schedule.fromStation',
                'schedule.toStation',
            ])
                ->whereHas('bus.busConductors', function ($query) use ($user) {
                    $query->where('users.id', $user->id);
                })
                ->get();


            return response()->json([
                'statusCode' => 200,
                'message' => 'Success!',
                'result' =>  $reservations
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => 500,
                'message' => 'Error!',
                'result' =>  $e->getMessage()
            ]);
        }
    }
}

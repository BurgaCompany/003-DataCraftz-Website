<?php

namespace App\Http\Controllers\Api;

use App\Helpers\HttpResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\FindScheduleByDateResource;
use App\Http\Resources\FindScheduleResource;
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
            $schedules = Schedule::with(['bus', 'fromStation', 'toStation', 'driver'])->get();

            return response()->json([
                'statusCode' => 200,
                'message' => 'Success!',
                'data_schedule' =>  $schedules
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => 500,
                'message' => 'Error!',
                'data_schedule' => ['errors' => $e->getMessage()]
            ]);
        }
    }

    public function getAllBusses()
    {
        try {
            $busses = Schedule::all();

            if ($busses->isEmpty()) {
                return response()->json([
                    'statusCode' => 404,
                    'message' => 'Data not found!',
                ]);
            }
            return response()->json([
                'statusCode' => 200,
                'message' => 'Success!',
                'data_schedule' => $busses
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'statusCode' => 500,
                'message' => 'Error!',
                'data_schedule' =>  $th->getMessage()
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
                    'data_schedule' => ['errors' => $validator->errors()]
                ], 400);
            }

            $user = auth('api')->user();

            if (!$user) {
                return response()->json([
                    'statusCode' => 401,
                    'message' => 'Unauthenticated',
                    'data_schedule' => ['error' => 'Unauthenticated']
                ], 401);
            }

            // Find the schedule by schedule_id
            $schedule = Schedule::find($request->input('schedule_id'));

            if (!$schedule) {
                return response()->json([
                    'statusCode' => 404,
                    'message' => 'Schedule Not Found',
                    'data_schedule' => ['error' => 'Schedule not found']
                ], 404);
            }

            // Find the bus associated with the schedule
            $bus = Buss::find($schedule->bus_id);

            if (!$bus) {
                return response()->json([
                    'statusCode' => 404,
                    'message' => 'Bus Not Found',
                    'data_schedule' => ['error' => 'Bus not found']
                ], 404);
            }

            // Check if there are enough chairs available
            if ($bus->chair < $request->tickets_booked) {
                return response()->json([
                    'statusCode' => 400,
                    'message' => 'Not Enough Chairs',
                    'data_schedule' => ['error' => 'Not enough chairs available']
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
                'data_schedule' => $reservation
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => 500,
                'message' => 'Error!',
                'data_schedule' => ['error' => $e->getMessage()]
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
                    'data_schedule' =>  $validator->errors()
                ], 400);
            }

            $user = auth('api')->user();

            if (!$user) {
                return response()->json([
                    'statusCode' => 401,
                    'message' => 'Unauthenticated',
                    'data_schedule' =>  'Unauthenticated'
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
                'data_schedule' =>  $e->getMessage()
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
                'data_schedule' =>  $schedules
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => 500,
                'message' => 'Error!',
                'data_schedule' =>  $e->getMessage()
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
                'data_schedule' =>  $reservations
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => 500,
                'message' => 'Error!',
                'data_schedule' =>  $e->getMessage()
            ]);
        }
    }

    public function findScheduleByInput(Request $request)
    {
        $from_station_id = $request->query('from_station');
        $to_station_id = $request->query('to_station');
        $date = \DateTime::createFromFormat('Y-n-j', $request->query('date'));
        $date_departure = $date->format('Y-m-d');
        $date_now = now()->format('Y-m-d');
        $schedule = Schedule::with(['bus', 'fromStation', 'toStation'])
            ->whereFromStation($from_station_id)
            ->whereToStation($to_station_id)
            ->get();

        $reservation = Reservation::where('date_departure', $date_departure)->count();

        if ($schedule->isEmpty()) {
            return response()->json([
                'statusCode' => 400,
                'message' => 'Not found',
            ], 400);
        }

        if ($date_departure == $date_now) {
            $schedule_byDate = Schedule::with(['bus', 'fromStation', 'toStation', 'driver'])
            ->whereFromStation($from_station_id)
            ->whereToStation($to_station_id)
            ->get();
            return response()->json([
                'statusCode' => 200,
                'message' => 'Success!',
                'data_schedule' => $schedule_byDate->map(function ($schedule) use ($date_departure) {
                    return new FindScheduleByDateResource($schedule, $date_departure);
                })
            ]);
        }
        if ($date_departure < $date_now) {
            return response()->json([
                'statusCode' => 400,
                'message' => 'Date must be greater than today!',
            ]);
        }

        if ($reservation > 0) {
            $schedule_byDate = Schedule::with(['bus', 'fromStation', 'toStation', 'driver'])
            ->whereFromStation($from_station_id)
            ->whereToStation($to_station_id)
            ->get();
            return response()->json([
                'statusCode' => 200,
                'message' => 'Success!',
                'data_schedule' => $schedule_byDate->map(function ($schedule) use ($date_departure) {
                    return new FindScheduleByDateResource($schedule, $date_departure);
                })
            ]);
        }

        return response()->json([
            'statusCode' => 200,
            'message' => 'Success!',
            'data_schedule' =>  FindScheduleResource::collection($schedule)
        ]);
    }
}

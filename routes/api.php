<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\CondectureController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\DriverAttendanceController;
use App\Http\Controllers\Api\DriverReviewController;
use App\Http\Controllers\Api\MidtransController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\Api\StationController;
use App\Http\Controllers\Api\TrackBusController;
use App\Http\Controllers\DriverController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
});

// Route::middleware(['auth:sanctum'])->group(function () {
// Route::prefix('schedules')->group(function () {
//     Route::get('/', [ScheduleController::class, 'index']);
//     Route::get('/reserve', [ScheduleController::class, 'historyReserve']);
//     Route::get('/conductor-reserve', [ScheduleController::class, 'conductorReserveTicket']);
//     Route::post('/reserve', [ScheduleController::class, 'reserveTicket']);
//     Route::post('/update-reserve', [ScheduleController::class, 'updateReserveTicket']);
// });


Route::group(['namespace' => 'schedules'], function () {
    Route::get('schedules', [ScheduleController::class, 'getAllBusses']);
    Route::get('schedules/find', [ScheduleController::class, 'findScheduleByInput']);
});

Route::group(['namespace' => 'station'], function () {
    Route::get('station', [StationController::class, 'allStation']);
});

Route::group(['namespace' => 'midtrans'], function () {
    Route::post('midtrans-payment-gateway', [MidtransController::class, 'show']);
});

Route::group(['namespace' => 'tracking'], function () {
    Route::post('tracking-bus', [TrackBusController::class, 'updateLocation']);
});

Route::group(['namespace' => 'review'], function () {
    Route::post('review-rating', [DriverReviewController::class, 'rating']);
});

Route::group(['namespace' => 'reservation'], function () {
    Route::get('reservation-goon', [ReservationController::class, 'ReservationGoOn']);
    Route::get('reservation-history', [ReservationController::class, 'ReservationHistory']);
    Route::get('reservation-detail', [ReservationController::class, 'getReservationById']);
    Route::get('reservation-after-payment', [ReservationController::class, 'getReservation']);

});

Route::group(['namespace' => 'conductor'], function () {
    Route::get('scan/conductor', [CondectureController::class, 'validationId']);
    Route::get('check/update', [CondectureController::class, 'updateStatusReservation']);
});



Route::put('Driver/status/{id}', [DriverAttendanceController::class, 'updateStatus']);

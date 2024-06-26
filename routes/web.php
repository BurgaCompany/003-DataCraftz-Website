<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\BusConductorController;
use App\Http\Controllers\BussesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UptController;
use App\Http\Controllers\BusStationController;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\DashboardPoController;
use App\Http\Controllers\DashboardUptController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\OtobusController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\TransitController;
use App\Models\Reservation;

Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

// Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('role:Upt|Admin')->name('dashboard');


Route::get('/profile', [ProfileController::class, 'show'])->middleware('role:Root|Upt|Admin|PO')->name('profile');
Route::post('/profile/update-image', [ProfileController::class, 'updateImage'])->middleware('role:Root|Upt|Admin|PO')->name('profile.update-image');
Route::put('/profile/update/{id}', [ProfileController::class, 'update'])->middleware('role:Root|Upt|Admin|PO')->name('profile.update');

Route::middleware(['role:Root'])->group(function () {
    Route::get('/upts', [UptController::class, 'index'])->name('upts.index');
    Route::get('/upts/search', [UptController::class, 'search'])->name('upts.search');
    Route::get('/upts/create', [UptController::class, 'create'])->name('upts.create');
    Route::post('/upts', [UptController::class, 'store'])->name('upts.store');
    Route::get('/upts/{id}/edit', [UptController::class, 'edit'])->name('upts.edit');
    Route::get('/upts/{id}/detail', [UptController::class, 'detail'])->name('upts.detail');
    Route::put('/upts/{id}', [UptController::class, 'update'])->name('upts.update');
    Route::post('/upts/delete', [UptController::class, 'destroyMulti'])->name('upts.destroy.multi');

    Route::get('/otobuses', [OtobusController::class, 'index'])->name('otobuses.index');
    Route::get('/otobuses/search', [OtobusController::class, 'search'])->name('otobuses.search');
    Route::get('/otobuses/create', [OtobusController::class, 'create'])->name('otobuses.create');
    Route::post('/otobuses', [OtobusController::class, 'store'])->name('otobuses.store');
    Route::get('/otobuses/{id}/edit', [OtobusController::class, 'edit'])->name('otobuses.edit');
    Route::get('/otobuses/{id}/detail', [OtobusController::class, 'detail'])->name('otobuses.detail');
    Route::put('/otobuses/{id}', [OtobusController::class, 'update'])->name('otobuses.update');
    Route::post('/otobuses/delete', [OtobusController::class, 'destroyMulti'])->name('otobuses.destroy.multi');

    //Route for schedules

    Route::get('/schedules/create', [ScheduleController::class, 'create'])->name('schedules.create');
    Route::post('/schedules', [ScheduleController::class, 'store'])->name('schedules.store');
    Route::post('/schedules/delete', [ScheduleController::class, 'destroyMulti'])->name('schedules.destroy.multi');

    Route::get('/deposits/{id}/edit', [DepositController::class, 'edit'])->name('deposits.edit');
    Route::put('/deposits/{id}/', [DepositController::class, 'update'])->name('deposits.update');
});

Route::middleware(['role:Upt'])->group(function () {
    Route::get('/dashboard_upt', [DashboardUptController::class, 'index'])->name('dashboard_upt');

    //route for admins
    Route::get('/admins', [AdminController::class, 'index'])->name('admins.index');
    Route::get('/admins/search', [AdminController::class, 'search'])->name('admins.search');
    Route::get('/admins/create', [AdminController::class, 'create'])->name('admins.create');
    Route::post('/admins', [AdminController::class, 'store'])->name('admins.store');
    Route::get('/admins/{id}/edit', [AdminController::class, 'edit'])->name('admins.edit');
    Route::get('/admins/{id}/detail', [AdminController::class, 'detail'])->name('admins.detail');
    Route::put('/admins/{id}', [AdminController::class, 'update'])->name('admins.update');
    Route::post('/admins/delete', [AdminController::class, 'destroyMulti'])->name('admins.destroy.multi');

    // route for bus_stations
    Route::get('/bus_stations', [BusStationController::class, 'index'])->name('bus_stations.index');
    Route::get('/bus_stations/search', [BusStationController::class, 'search'])->name('bus_stations.search');
    Route::get('/bus_stations/create', [BusStationController::class, 'create'])->name('bus_stations.create');
    Route::post('/bus_stations', [BusStationController::class, 'store'])->name('bus_stations.store');
    Route::get('/bus_stations/{id}/detail', [BusStationController::class, 'detail'])->name('bus_stations.detail');
    Route::get('/bus_stations/{id}/edit', [BusStationController::class, 'edit'])->name('bus_stations.edit');
    Route::put('/bus_stations/{id}', [BusStationController::class, 'update'])->name('bus_stations.update');
    Route::post('/bus_stations/delete', [BusStationController::class, 'destroyMulti'])->name('bus_stations.destroy.multi');
});

Route::middleware(['role:PO'])->group(function () {

    Route::get('/dashboard_po', [DashboardPoController::class, 'index'])->name('dashboard_po');

    Route::get('/rating/{id}/detail', [DriverController::class, 'rating'])->name('drivers.rating');

    //Route for Drivers
    Route::get('/drivers', [DriverController::class, 'index'])->name('drivers.index');
    Route::get('/drivers/search', [DriverController::class, 'search'])->name('drivers.search');
    Route::get('/drivers/{id}/detail', [DriverController::class, 'detail'])->name('drivers.detail');
    Route::get('/drivers/create', [DriverController::class, 'create'])->name('drivers.create');
    Route::post('/drivers', [DriverController::class, 'store'])->name('drivers.store');
    Route::get('/drivers/{id}/edit', [DriverController::class, 'edit'])->name('drivers.edit');
    Route::put('/drivers/{id}', [DriverController::class, 'update'])->name('drivers.update');
    Route::post('/drivers/delete', [DriverController::class, 'destroyMulti'])->name('drivers.destroy.multi');

    //Route for bus_conductors
    Route::get('/bus_conductors', [BusConductorController::class, 'index'])->name('bus_conductors.index');
    Route::get('/bus_conductors/search', [BusConductorController::class, 'search'])->name('bus_conductors.search');
    Route::get('/bus_conductors/{id}/detail', [BusConductorController::class, 'detail'])->name('bus_conductors.detail');
    Route::get('/bus_conductors/create', [BusConductorController::class, 'create'])->name('bus_conductors.create');
    Route::post('/bus_conductors', [BusConductorController::class, 'store'])->name('bus_conductors.store');
    Route::get('/bus_conductors/{id}/edit', [BusConductorController::class, 'edit'])->name('bus_conductors.edit');
    Route::put('/bus_conductors/{id}', [BusConductorController::class, 'update'])->name('bus_conductors.update');
    Route::post('/bus_conductors/delete', [BusConductorController::class, 'destroyMulti'])->name('bus_conductors.destroy.multi');

    //Route for bus
    Route::get('/busses/create', [BussesController::class, 'create'])->name('busses.create');
    Route::post('/busses', [BussesController::class, 'store'])->name('busses.store');
    Route::post('/busses/delete', [BussesController::class, 'destroyMulti'])->name('busses.destroy.multi');
    Route::get('/busses', [BussesController::class, 'index'])->name('busses.index');
    Route::get('/busses/search', [BussesController::class, 'search'])->name('busses.search');
    Route::get('/busses/{id}/edit', [BussesController::class, 'edit'])->name('busses.edit');
    Route::get('/busses/{id}/detail', [BussesController::class, 'detail'])->name('busses.detail');
    Route::put('/busses/{id}', [BussesController::class, 'update'])->name('busses.update');
    Route::get('/busses/filter', [BussesController::class, 'filter'])->name('busses.filter');

    Route::get('/api/bus-coordinates/{id}', [BussesController::class, 'getCoordinates']);
});

Route::middleware(['role:Admin'])->group(function () {

    Route::get('/dashboard_admin', [DashboardAdminController::class, 'index'])->name('dashboard_admin');

    // route for reservations
    Route::get('/reservations/create', [ReservationController::class, 'create'])->name('reservations.create');
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    Route::get('/reservations/{reservation}/print', [ReservationController::class, 'print'])->name('reservations.print');
    Route::get('/search-user', [ReservationController::class, 'searchUser'])->name('search-user');
});

Route::middleware(['role:Root|Upt|Admin|PO'])->group(function () {
    Route::get('/schedules/search', [ScheduleController::class, 'search'])->name('schedules.search');
    Route::get('/schedules', [ScheduleController::class, 'index'])->name('schedules.index');
    Route::get('/schedules/{id}/detail', [ScheduleController::class, 'detail'])->name('schedules.detail');
    Route::get('/schedules/search', [ScheduleController::class, 'search'])->name('schedules.search');

    // route for banks
    Route::get('/banks', [BankController::class, 'index'])->name('banks.index');
    Route::get('/banks/search', [BankController::class, 'search'])->name('banks.search');
    Route::get('/banks/create', [BankController::class, 'create'])->name('banks.create');
    Route::post('/banks', [BankController::class, 'store'])->name('banks.store');
    Route::get('/banks/{id}/detail', [BankController::class, 'detail'])->name('banks.detail');
    Route::get('/banks/{id}/edit', [BankController::class, 'edit'])->name('banks.edit');
    Route::put('/banks/{id}', [BankController::class, 'update'])->name('banks.update');
    Route::post('/banks/delete', [BankController::class, 'destroyMulti'])->name('banks.destroy.multi');

    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::post('/reservations/depo', [ReservationController::class, 'depo_up'])->name('reservations.depo_up');

    Route::get('/deposits', [DepositController::class, 'index'])->name('deposits.index');
    //Route::get('/banks/{id}/detail', [BankController::class, 'detail'])->name('deposits.detail');
    Route::get('/deposits/{id}/detail', [DepositController::class, 'detail'])->name('deposits.detail');
});

Route::middleware(['role:Root|PO'])->group(function () {
    Route::get('/schedules/{id}/edit', [ScheduleController::class, 'edit'])->name('schedules.edit');
    Route::put('/schedules/{id}', [ScheduleController::class, 'update'])->name('schedules.update');
});

Route::middleware(['role:Root|Upt|Admin'])->group(function () {
    Route::get('/transits', [TransitController::class, 'index'])->name('transits.index');
});

Route::get('/track/bus', function () {
    return view('maps.index');
});

Route::get('/storage-link', function () {
    $target_folder = base_path() . '/storage/app/public';
    $link_folder = $_SERVER['DOCUMENT_ROOT'] . "/storage";
    symlink($target_folder, $link_folder);
});




Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

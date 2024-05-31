<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Buss extends Model
{
    use HasFactory;

    protected $table = 'busses'; // Menambahkan nama tabel

    protected $fillable = [
        'name',
        'license_plate_number',
        'chair',
        'class',
        'price',
        'status',
        'information',
        'images',
        'id_upt',
    ];

    public function upt()
    {
        return $this->belongsTo(User::class, 'id_upt');
    }

    public function driveconduc()
    {
        return $this->hasMany(DriverConductorBus::class, 'bus_id');
    }

    public function drivers()
    {
        return $this->belongsToMany(User::class, 'driver_conductor_bus', 'bus_id', 'driver_id');
    }

    public function busConductors()
    {
        return $this->belongsToMany(User::class, 'driver_conductor_bus', 'bus_id', 'bus_conductor_id');
    }

    public function schedules()
    {
        return $this->belongsTo(Schedule::class, 'id');
    }
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}

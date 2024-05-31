<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverConductorBus extends Model
{
    use HasFactory;

    protected $table = 'driver_conductor_bus';

    protected $fillable = [
        'driver_id',
        'bus_conductor_id',
        'bus_id',
    ];

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function busConductor()
    {
        return $this->belongsTo(User::class, 'bus_conductor_id');
    }

    public function bus()
    {
        return $this->belongsTo(Buss::class, 'bus_id');
    }
}

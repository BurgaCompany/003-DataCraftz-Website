<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'address', 'gender', 'phone_number', 'images', 'id_upt', 'api_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function busStations()
    {
        return $this->hasMany(BusStation::class);
    }

    public function adminBusStations()
    {
        return $this->hasMany(AdminBusStation::class);
    }

    public function driveconduc()
    {
        return $this->hasMany(DriverConductorBus::class, 'bus_id');
    }

    public function driverBus()
    {
        return $this->hasMany(DriverConductorBus::class, 'driver_id');
    }

    public function ConductorBus()
    {
        return $this->hasMany(DriverConductorBus::class, 'bus_conductor_id');
    }
    public function apiTokens()
    {
        return $this->hasMany(ApiToken::class);
    }
}

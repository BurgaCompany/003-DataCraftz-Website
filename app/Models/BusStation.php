<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusStation extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'address'];

    public function upt()
    {
        return $this->belongsTo(User::class, 'id_upt');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class);
    }

    // Mendefinisikan relasi dengan AdminBusStation
    public function adminBusStations()
    {
        return $this->hasMany(AdminBusStation::class, 'bus_station_id');
    }

    // Mendapatkan daftar admin terkait dengan stasiun bus
    public function admins()
    {
        return $this->belongsToMany(User::class, 'admin_bus_station', 'bus_station_id', 'user_id');
    }
}

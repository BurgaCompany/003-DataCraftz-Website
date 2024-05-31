<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Bus;

class Schedule extends Model
{
    protected $table = 'schedules';
    protected $fillable = [
        'bus_id',
        'from_station_id',
        'to_station_id',
        'price',
        'time_start',
        'pwt',
    ];

    public function getDurationAttribute()
    {
        $hours = floor($this->pwt / 60);
        $minutes = $this->pwt % 60;

        if ($hours > 0 && $minutes > 0) {
            return "{$hours} jam & {$minutes} menit";
        } elseif ($hours > 0) {
            return "{$hours} jam";
        } else {
            return "{$minutes} menit";
        }
    }


    public function bus()
    {
        return $this->belongsTo(Buss::class);
    }

    public function fromStation()
    {
        return $this->belongsTo(BusStation::class, 'from_station_id');
    }

    public function toStation()
    {
        return $this->belongsTo(BusStation::class, 'to_station_id');
    }
}

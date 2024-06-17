<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackBus extends Model
{
    use HasFactory;

    protected $table = 'track_bus';

    protected $fillable = [
        'bus_id',
        'latitude',
        'longitude',
    ];
}

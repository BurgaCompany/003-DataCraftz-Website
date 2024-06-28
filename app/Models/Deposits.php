<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposits extends Model
{
    use HasFactory;

    protected $table = 'deposits';

    protected $fillable = [
        'user_id',
        'bus_station_id',
        'bank_id',
        'type',
        'amount',
        'type',
        'images',
        'status',

    ];

    // User relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // BusStation relationship
    public function busStation()
    {
        return $this->belongsTo(BusStation::class);
    }

    // Bank relationship
    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }
}

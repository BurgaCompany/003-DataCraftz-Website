<?php

namespace App\Http\Resources;

use App\Models\Reservation;
use Illuminate\Http\Resources\Json\JsonResource;

class FindScheduleByDateResource extends JsonResource
{
    private $date_departure;

    public function __construct($resource, $date_departure)
    {
        // Ensure you call the parent constructor
        parent::__construct($resource);
        $this->date_departure = $date_departure;
    }

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'bus' => $this->bus->name,
            'bus_id' => $this->bus->id,
            'driver_id' => $this->driver->drivers->id,
            'driver_name' => $this->driver->drivers->name,
            'chair' => $this->bus->chair - Reservation::where('schedule_id', $this->id)->where('date_departure', $this->date_departure)->count(),
            'from_station' => $this->fromStation->name,
            'to_station' => $this->toStation->name,
            'price' => $this->price,
            'time_start' => $this->time_start,
            'pwt' => sprintf("%d Jam %02d Menit", floor($this->pwt / 60), $this->pwt % 60),
            'create_at' => $this->created_at,
            'update_at' => $this->updated_at,
        ];
    }
}

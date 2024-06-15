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
            'chair' => $this->bus->chair - Reservation::where('schedule_id', $this->id)->where('date_departure', $this->date_departure)->count(),
            'from_station' => $this->fromStation->name,
            'to_station' => $this->toStation->name,
            'price' => $this->price,
            'time_start' => $this->time_start,
            'pwt' => $this->pwt,
            'create_at' => $this->created_at,
            'update_at' => $this->updated_at,
        ];
    }
}

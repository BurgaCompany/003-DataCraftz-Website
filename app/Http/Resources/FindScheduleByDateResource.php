<?php

namespace App\Http\Resources;

use App\Models\Reservation;
use Illuminate\Http\Resources\Json\JsonResource;

class FindScheduleByDateResource extends JsonResource
{
    private function calculatePWT()
    {
        $start = new \DateTime($this->time_start);
        $end = new \DateTime($this->time_arrive);

        if ($end < $start) {
            $end->modify('+1 day');
        }

        $interval = $start->diff($end);
        $totalHours = $interval->days * 24 + $interval->h;
        $totalMinutes = $interval->i;

        return sprintf('%d Jam %d Menit', $totalHours, $totalMinutes);
    }

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type_bus' => $this->bus->class,
            'bus_id' => $this->bus->id,
            'driver_id' => $this->driver->drivers->id,
            'driver_name' => $this->driver->drivers->name,
            'chair' => $this->bus->chair - Reservation::where('schedule_id', $this->id)
                ->where('date_departure', $this->date_departure)
                ->sum('tickets_booked'),
            'plat_number' => $this->bus->license_plate_number,
            'name_station' => $this->fromStation->name,
            'to_name_station' => $this->toStation->name,
            'from_station' => $this->fromStation->code_name,
            'to_station' => $this->toStation->code_name,
            'price' => $this->price,
            'time_start' => $this->time_start,
            'time_arrive' => $this->time_arrive,
            'pwt' => $this->calculatePWT(),
            'create_at' => $this->created_at,
            'update_at' => $this->updated_at,
        ];
    }
}

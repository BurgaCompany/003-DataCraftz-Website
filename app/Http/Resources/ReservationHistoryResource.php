<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReservationHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    // Existing properties and methods...

    private function calculatePWT()
    {
        $start = new \DateTime($this->schedule->time_start);
        $end = new \DateTime($this->schedule->time_arrive);

        if ($end < $start) {
            $end->modify('+1 day');
        }

        $interval = $start->diff($end);
        $totalHours = $interval->days * 24 + $interval->h;
        $totalMinutes = $interval->i;

        return sprintf('%d Jam %d Menit', $totalHours, $totalMinutes);
    }

    // Assuming this is part of a larger method that returns the resource array
    public function toArray($request)
    {
        return [
            'order_id' => $this->order_id,
            'user_name' => $this->user->name,
            'driver_id' => $this->schedule->driver->driver_id,
            'schedule_from_station' => $this->schedule->fromStation->name,
            'schedule_to_station' => $this->schedule->toStation->name,
            'schedule_from_station_code_name' => $this->schedule->fromStation->code_name,
            'schedule_to_station_code_name' => $this->schedule->toStation->code_name,
            'schedule_pwt' => $this->calculatePWT(),
            'bus_class' => $this->bus->class,
            'schedule_price' => $this->schedule->price,
            'schedule_time_start' => $this->schedule->time_start,
            'schedule_time_arrive' => $this->schedule->time_arrive,
            'license_plate_number' => $this->bus->license_plate_number,
            'tickets_booked' => $this->tickets_booked,
            'date_departure' => $this->date_departure,
            'status' => $this->status == 1 ? 'Berlangsung' : 'Selesai',
            'status_bus' => $this->bus->status,
        ];
    }
}

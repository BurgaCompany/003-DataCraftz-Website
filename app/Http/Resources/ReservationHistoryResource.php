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
    public function toArray($request)
    {
        $start = new \DateTime($this->schedule->time_start);
        $end = new \DateTime($this->schedule->time_arrive);

        // Periksa apakah waktu kedatangan lebih awal dari waktu keberangkatan
        if ($end < $start) {
            $end->modify('+1 day');
        }

        // Menghitung selisih waktu
        $interval = $start->diff($end);

        // Menghitung total jam dan menit termasuk perbedaan hari
        $totalHours = $interval->days * 24 + $interval->h;
        $totalMinutes = $interval->i;

        // Format hasil perhitungan waktu
        $pwt = sprintf('%d Jam %d Menit', $totalHours, $totalMinutes);
        return [
            'order_id' => $this->order_id,
            'user_name' => $this->user->name,
            'schedule_from_station' => $this->schedule->fromStation->name,
            'schedule_from_station_code_name' => $this->schedule->fromStation->code_name,
            'schedule_to_station_code_name' => $this->schedule->toStation->code_name,
            'schedule_pwt' => $pwt,
            'bus_class' => $this->bus->class,
            'schedule_price' => $this->schedule->price,
            'schedule_time_start' => $this->schedule->time_start,
            'license_plate_number' => $this->bus->license_plate_number,
            'tickets_booked' => $this->tickets_booked,
            'date_departure' => $this->date_departure,
            'status' => $this->status,
        ];
    }
}

<?php

namespace App\Http\Resources;

use App\Models\Reservation;
use Illuminate\Http\Resources\Json\JsonResource;

class FindScheduleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'bus' => $this->bus->name,
            'bus_id' => $this->bus->id,
            'chair' => $this->bus->chair,
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

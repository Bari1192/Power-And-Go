<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RenthistoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return
            [
                "id" => $this->id,
                "auto_azon" => $this->auto_azon,
                "person_id" => $this->person_id,
                "start_percent" => $this->start_percent,
                "start_kw" => $this->start_kw,
                "end_percent" => $this->end_percent,
                "end_kw" => $this->end_kw,
                "rent_start_date" => $this->rent_start_date,
                "rent_start_time" => $this->rent_start_time,
                "rent_end_date" => $this->rent_end_date,
                "rent_end_time" => $this->rent_end_time,
                "driving_distance" => $this->driving_distance,
                "parking_start" => $this->parking_start,
                "parking_end" => $this->parking_end,
                "parking_minutes" => $this->parking_minutes,
                "driving_minutes" => $this->driving_minutes,
                "rental_cost" => $this->rental_cost,
                "auto" => new CarResource($this->whenLoaded('auto')),
                "szemely" => new PersonResource($this->whenLoaded('person')),
                "felhasznalo" => new UserResource($this->whenLoaded('person.user')),
            ];
    }
}

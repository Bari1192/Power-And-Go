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
                "car_id" => $this->car_id,
                "person_id" => $this->person_id,
                "start_percent" => $this->start_percent,
                "start_kw" => $this->start_kw,
                "end_percent" => $this->end_percent,
                "end_kw" => $this->end_kw,
                "rent_start" => $this->rent_start,
                "rent_close" => $this->rent_close,
                "distance" => $this->distance,
                "parking_minutes" => $this->parking_minutes,
                "driving_minutes" => $this->driving_minutes,
                "rental_cost" => $this->rental_cost,
                "car" => new CarResource($this->whenLoaded('car')),
                "person" => new PersonResource($this->whenLoaded('person')),
                "user" => new UserResource($this->whenLoaded('person.user')),
            ];
    }
}

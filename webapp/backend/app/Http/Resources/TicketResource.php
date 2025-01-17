<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'car_id' => $this->car_id,
            'status_id' => $this->status_id,
            'status_descrip' => $this->whenLoaded('status', fn() => $this->status->status_descrip),
            'created_at' => $this->created_at,
            'car' => $this->whenLoaded('auto', function () {
                return [
                    'plate' => $this->auto->plate,
                    'power_percent' => $this->auto->power_percent,
                    'power_kw' => $this->auto->power_kw,
                    'estimated_range' => $this->auto->estimated_range,
                ];
            }),
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FleetResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return
            [
                'flotta_id' => $this->id,
                'manufacturer' => $this->manufacturer,
                'carmodel' => $this->carmodel,
                'motor_power' => $this->motor_power,
                'top_speed' => $this->top_speed,
                'tire_size' => $this->tire_size,
                'driving_range' => $this->driving_range,
            ];
    }
}

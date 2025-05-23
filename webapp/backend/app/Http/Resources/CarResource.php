<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'car_id' => $this->id,
            'plate' => $this->plate,
            'power_percent' => $this->power_percent,
            'power_kw' => round($this->power_kw, 1),
            'estimated_range' => round($this->estimated_range),
            'status' => $this->status,
            'status_name' => $this->carstatus->status_name,
            'category_id' => $this->category_id,
            'equipment_class' => $this->equipment_class,
            'odometer' => $this->odometer,
            'manufactured' => $this->manufactured,
            "manufacturer" => $this->fleet->manufacturer,
            "carmodel" => $this->fleet->carmodel,
            "driving_range" => $this->fleet->driving_range,
            "motor_power" => $this->fleet->motor_power,
            "top_speed" => $this->fleet->top_speed,
            "tire_size" => $this->fleet->tire_size,
        ];
    }
}

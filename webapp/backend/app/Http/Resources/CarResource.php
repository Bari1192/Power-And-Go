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
            'power_kw' => $this->power_kw,
            'estimated_range' => $this->estimated_range,
            'status' => $this->status,
            'category_id' => $this->category_id,
            'equipment_class' => $this->equipment_class,
            'odometer' => number_format($this->odometer, 0, '', ' '),
            'manufacturing_year' => $this->manufacturing_year,
            'fleet_id' => $this->fleet_id,
            'carmodel' => optional($this->fleet)->carmodel,
            'manufacturer' => optional($this->fleet)->manufacturer,
            'motor_power' => optional($this->fleet)->motor_power,
            'top_speed' => optional($this->fleet)->top_speed,
            'tire_size' => optional($this->fleet)->tire_size,
            'driving_range' => optional($this->fleet)->driving_range,

        ];
    }
}

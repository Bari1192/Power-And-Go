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
            'status_name' => $this->carstatus->status_name,
            'category_id' => $this->category_id,
            'equipment_class' => $this->equipment_class,
            'odometer' => number_format($this->odometer, 0, '', ' '),
            'manufacturing_year' => $this->manufacturing_year,
            'car_data' => $this->whenLoaded('fleet', function () {
                return collect($this->fleet)->except('id');
            }),
        ];
    }
}

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
            'admin_description' => $this->description,
            'car_id' => $this->car_id,
            'status_id' => $this->status_id,
            'status_descrip' => $this->whenLoaded('status', fn() => $this->status->status_descrip),
            'created_at' => date_format($this->created_at, 'Y-m-d H:i:s'),
            'car' => $this->whenLoaded('car', function () {
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

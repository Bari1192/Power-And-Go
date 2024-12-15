<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "description" => $this->description,
            "car_id" => $this->car_id,
            "status_id" => $this->status_id,
        ];
    }
}

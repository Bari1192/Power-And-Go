<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BillResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "bill_type" => $this->bill_type,
            "user_id" => $this->user_id,
            "person_id" => $this->person_id,
            "car_id" => $this->car_id,
            "rent_id"=>$this->rent_id,
            "plate" => $this->cars->plate,
            "total_cost" => $this->total_cost,
            "credits" => $this->credits,
            "charged_kw" => $this->charged_kw,
            "distance" => $this->distance,
            "parking_minutes" => $this->parking_minutes,
            "driving_minutes" => $this->driving_minutes,
            "rent_start" => $this->rent_start,
            "rent_close" => $this->rent_close,
            "invoice_date" => $this->invoice_date,
            "invoice_status" => $this->invoice_status,
            "username" => $this->users->user_name,
            "person" => $this->whenLoaded('persons', $this->persons->firstname . ' ' . $this->persons->lastname),
        ];
    }
}

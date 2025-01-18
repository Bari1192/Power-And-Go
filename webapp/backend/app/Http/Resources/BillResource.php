<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BillResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id"=>$this->id,
            "user_id" => $this->user_id,
            "username"=>$this->whenLoaded('users')->user_name,
            "person_id" => $this->person_id,
            "person"=>$this->whenLoaded('persons')->firstname.' '.$this->whenLoaded('persons')->lastname,
            "car_id" => $this->car_id,
            "rent_start_date" => $this->rent_start_date,
            "rent_start_time" => $this->rent_start_time,
            "rent_end_date" => $this->rent_end_date,
            "rent_end_time" => $this->rent_end_time,
            "driving_distance" => $this->driving_distance,
            "parking_minutes" => $this->parking_minutes,
            "driving_minutes" => $this->driving_minutes,
            "total_cost" => number_format($this->total_cost,0,'',' '),
            "bill_type"=>$this->bill_type,
            "invoice_date" => $this->invoice_date,
            "invoice_status" => $this->invoice_status,
        ];
    }
}

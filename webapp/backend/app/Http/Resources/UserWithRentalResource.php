<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserWithRentalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'user_id' => $this->id,
            'username' => $this->user_name,
            'rentals' => $this->cars->map(function ($car) {
                return [
                    'car_id' => $car->id,
                    'license_plate' => $car->plate,
                    'fleet' => $car->fleet->manufacturer,
                    'start_percent' => $car->rent_details->start_percent,
                    'start_kw' => $car->rent_details->start_kw,
                    'end_percent' => $car->rent_details->end_percent,
                    'end_kw' => $car->rent_details->end_kw,
                    'rent_start' => $car->rent_details->rent_start,
                    'rent_close' => $car->rent_details->rent_close,
                    'distance' => $car->rent_details->distance,
                    'parking_minutes' => $car->rent_details->parking_minutes,
                    'driving_minutes' => $car->rent_details->driving_minutes,
                    'rental_cost' => $car->rent_details->rental_cost,
                    'invoice_date' => $car->rent_details->invoice_date,
                ];
            }),
        ];
    }
}

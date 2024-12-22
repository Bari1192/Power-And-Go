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
            'username' => $this->felh_nev,
            'rentals' => $this->cars->map(function ($car) {
                return [
                    'car_id' => $car->id,
                    'license_plate' => $car->rendszam,
                    'fleet' => $car->fleet->gyarto ?? null,
                    'rental_details' => $car->rent_details, // Pivot adatok
                ];
            }),
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarWithUsersResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'car_id' => $this->id,
            'plate' => $this->plate,
            'category_id' => $this->category_id,
            'equipment_class' => $this->equipment_class,
            'odometer' => number_format($this->odometer, 0, '', ' '),
            'manufacturer' => $this->whenLoaded('fleet')->manufacturer,
            'carmodel' => $this->whenLoaded('fleet')->carmodel,
            'renters' => $this->users->map(function ($user) {
                return [
                    'rent_id' => $user->rent_details->id,
                    'user' => $user->user_name,
                    'rent_start' => $user->rent_details->rent_start,
                    'start_percent' => $user->rent_details->start_percent,
                    'start_kw' => $user->rent_details->start_kw,
                    'rent_close' => $user->rent_details->rent_close,
                    'end_percent' => $user->rent_details->end_percent,
                    'end_kw' => $user->rent_details->end_kw,
                    'distance' => $user->rent_details->distance,
                    'rental_cost' => number_format($user->rent_details->rental_cost, 0, '', ' '),
                    'parking' => $user->rent_details->parking_minutes,
                    'invoice_date' => $user->rent_details->invoice_date,
                ];
            })->sortByDesc('rent_close')->values(),
        ];
    }
}

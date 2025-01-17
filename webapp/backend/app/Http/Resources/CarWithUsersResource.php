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
            'berlok' => $this->users->map(function ($user) {
                return [
                    'berles_id' => $user->rent_details->id,
                    'user' => $user->user_name,
                    
                    // 'password_2_4' => $user->password_2_4,
                    // 'nev' => $user->person->firstname . " " . $user->person->lastname,
                    // 'phone' => $user->person->phone,
                    // 'szul_dat' => $user->person->birth_date,
                    'rent_start_date' => $user->rent_details->rent_start_date,
                    'rent_start_time' => $user->rent_details->rent_start_time,
                    'start_percent' => $user->rent_details->start_percent,
                    'start_kw' => $user->rent_details->start_kw,
                    'rent_end_date' => $user->rent_details->rent_end_date,
                    'rent_end_time' => $user->rent_details->rent_end_time,
                    'end_percent' => $user->rent_details->end_percent,
                    'end_kw' => $user->rent_details->end_kw,
                    'driving_distance' => $user->rent_details->driving_distance,
                    'rental_cost' => number_format($user->rent_details->rental_cost, 0, '', ' '),
                    'parkolas' => $user->rent_details->parking_minutes,
                    'invoice_date' => $user->rent_details->invoice_date,
                ];
            }),
        ];
    }
}

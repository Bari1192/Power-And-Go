<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource

{
    public function toArray(Request $request): array
    {
        return array_filter([
            'id' => $this->id,
            'person_id' => $this->person_id,
            'sub_id' => $this->sub_id,
            'account_balance' => $this->account_balance,
            'pin' => $this->pin,
            'password_2_4' => $this->password_2_4,
            'user_name' => $this->user_name,
            'role' => $this->role,
            'plant_tree' => $this->plant_tree,
            'vip_discount' => $this->vip_discount,
            'bonus_min_exp' => $this->bonus_min_exp,
            'bonus_minutes' => $this->bonus_minutes,
            'driving_minutes' => $this->driving_minutes,
            'contributions' => $this->contributions,
            'registered' => date_format($this->created_at, 'Y-m-d H:m:s'),
            "id_card" => $this->whenLoaded('person', fn() => $this->person->id_card),
            "firstname" => $this->whenLoaded('person', fn() => $this->person->firstname),
            "lastname" => $this->whenLoaded('person', fn() => $this->person->lastname),
            "phone" => $this->whenLoaded('person', fn() => $this->person->phone),
            "email" => $this->whenLoaded('person', fn() => $this->person->email),
            "prices" => $this->subscription->prices->map(function ($price) {
                return [
                    'category_class' => $price->category_class,
                    'rental_start' => $price->rental_start,
                    'driving_minutes' => $price->driving_minutes,
                    'discounted_driving' => $price->discounted_driving,
                    'parking_minutes' => $price->parking_minutes,
                    'reserv_minutes' => $price->reserv_minutes,
                    'disc_parking_minutes' => $price->disc_parking_minutes,
                    'daily_fee' => $price->daily_fee,
                    'daily_km_limit' => $price->daily_km_limit,
                    'km_fee' => $price->km_fee,
                    'airport_out_fee' => $price->airport_out_fee,
                    'airport_in_fee' => $price->airport_in_fee,
                    'airport_out_terminal_fee' => $price->airport_out_terminal_fee,
                    'airport_in_terminal_fee' => $price->airport_in_terminal_fee,
                    'zone_opening_fee' => $price->zone_opening_fee,
                    'zone_closing_fee' => $price->zone_closing_fee,
                    'three_hour_fee' => $price->three_hour_fee,
                    'six_hour_fee' => $price->six_hour_fee,
                    'twelve_hour_fee' => $price->twelve_hour_fee,
                    'weekend_daily_fee' => $price->weekend_daily_fee,
                ];
            })
        ], function ($value) {
            return ($value != null || $value != 0);
        });
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource

{
    public function toArray(Request $request): array
    {
        return array_filter([
            'user_id' => $this->id,
            'person_id' => $this->person_id,
            'user_name' => $this->user_name,
            'password_2_4' => $this->password_2_4,
            'plant_tree' => $this->plant_tree,
            'vip_discount' => $this->vip_discount,
            'bonus_minutes' => $this->bonus_minutes,
            'bonus_min_exp' => $this->bonus_min_exp,
            'driving_minutes' => $this->driving_minutes,
            'sub_id' => $this->sub_id,
            'pin' => $this->pin,
            'role'=>$this->role,
        ], function ($value) {
            return ($value != null || $value != 0);
        });
    }
}

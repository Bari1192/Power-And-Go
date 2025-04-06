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
            'registered' => date_format($this->created_at,'Y-m-d H:m:s') ,
        ], function ($value) {
            return ($value != null || $value != 0);
        });
    }
}

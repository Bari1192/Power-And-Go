<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "felh_id" => $this->id,
            "szemely_id" => $this->szemely_id,
            "felh_egyenleg" => $this->felh_egyenleg,
            "felh_nev" => $this->felh_nev,
            "jelszo_2_4" => $this->jelszo_2_4,
            "elofiz_id" => $this->elofiz_id,
        ];
    }
}

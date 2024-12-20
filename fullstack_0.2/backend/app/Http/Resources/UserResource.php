<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'felh_nev' => $this->felh_nev,
            'felh_egyenleg' => $this->felh_egyenleg,
            'elofiz_id' => $this->elofiz_id,
            'person' => $this->whenLoaded('person', function () {
                return [
                    'v_nev' => $this->person->v_nev,
                    'k_nev' => $this->person->k_nev,
                    'szul_datum' => $this->person->szul_datum,
                    'telefon' => $this->person->telefon,
                    'email' => $this->person->email,
                ];
            }),
            'cars' => CarResource::collection($this->whenLoaded('cars')), 
        ];
    }
}

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
            'cars' => $this->whenLoaded('cars', function () {
                return $this->cars->map(function ($car) {
                    return [
                        'id' => $car->id,
                        'rendszam' => $car->rendszam,
                        'reszletek' => [
                            'nyitas_szaz' => $car->pivot->nyitas_szaz,
                            'nyitas_kw' => $car->pivot->nyitas_kw,
                            'zaras_szaz' => $car->pivot->zaras_szaz,
                            'zaras_kw' => $car->pivot->zaras_kw,
                            'berles_kezd_datum' => $car->pivot->berles_kezd_datum,
                            'berles_kezd_ido' => $car->pivot->berles_kezd_ido,
                            'berles_veg_datum' => $car->pivot->berles_veg_datum,
                            'berles_veg_ido' => $car->pivot->berles_veg_ido,
                            'megtett_tavolsag' => $car->pivot->megtett_tavolsag,
                            'parkolas_kezd' => $car->pivot->parkolas_kezd,
                            'parkolas_veg' => $car->pivot->parkolas_veg,
                            'parkolasi_perc' => $car->pivot->parkolasi_perc,
                            'vezetesi_perc' => $car->pivot->vezetesi_perc,
                            'berles_osszeg' => $car->pivot->berles_osszeg,
                            'rentstatus' => $car->pivot->rentstatus,
                        ],
                    ];
                });
            }),
        ];
    }
}

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
            'rendszam' => $this->rendszam,
            'kategoria' => $this->kategoria,
            'felszereltseg' => $this->felszereltseg,
            'kilometerora' => number_format($this->kilometerora, 0, '', ' '),
            'gyarto' => $this->whenLoaded('fleet')->gyarto,
            'tipus' => $this->whenLoaded('fleet')->tipus,
            'berlok' => $this->users->map(function ($user) {
                return [
                    'berles_id' => $user->rent_details->id,
                    'user' => $user->user_name,
                    
                    // 'jelszo_2_4' => $user->jelszo_2_4,
                    // 'nev' => $user->person->v_nev . " " . $user->person->k_nev,
                    // 'telefon' => $user->person->telefon,
                    // 'szul_dat' => $user->person->szul_datum,
                    'berles_kezd_datum' => $user->rent_details->berles_kezd_datum,
                    'berles_kezd_ido' => $user->rent_details->berles_kezd_ido,
                    'nyitas_szaz' => $user->rent_details->nyitas_szaz,
                    'nyitas_kw' => $user->rent_details->nyitas_kw,
                    'berles_veg_datum' => $user->rent_details->berles_veg_datum,
                    'berles_veg_ido' => $user->rent_details->berles_veg_ido,
                    'zaras_szaz' => $user->rent_details->zaras_szaz,
                    'zaras_kw' => $user->rent_details->zaras_kw,
                    'megtett_tavolsag' => $user->rent_details->megtett_tavolsag,
                    'berles_osszeg' => number_format($user->rent_details->berles_osszeg, 0, '', ' '),
                    'parkolas' => $user->rent_details->parkolasi_perc,
                    'szamla_kelt' => $user->rent_details->szamla_kelt,
                ];
            }),
        ];
    }
}

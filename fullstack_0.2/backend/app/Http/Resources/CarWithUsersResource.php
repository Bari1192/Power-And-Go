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
            'toltes_szaz' => $this->toltes_szaz,
            'toltes_kw' => $this->toltes_kw,
            'becs_tav' => $this->becs_tav,
            'status' => $this->status,
            'kategoria' => $this->kategoria,
            'felszereltseg' => $this->felszereltseg,
            'kilometerora' => number_format($this->kilometerora,0,'',' ') ,
            'gyartasi_ev' => $this->gyartasi_ev,
            'fleet' => $this->whenLoaded('fleet'),
            'berlok' => $this->whenLoaded('users', function () {
                return $this->users->map(function ($user) {
                    return [
                        'username' => $user->felh_nev,
                        'jelszo_2_4' => $user->jelszo_2_4,
                        'vnev' => $user->person->v_nev,
                        'knev' => $user->person->k_nev,
                        'telefon' => $user->person->telefon,
                        'szul_dat' => $user->person->szul_datum,
                        'renthistory' => [
                            'nyitas_szaz' => $user->rent_details->nyitas_szaz,
                            'nyitas_kw' => $user->rent_details->nyitas_kw,
                            'zaras_szaz' => $user->rent_details->zaras_szaz,
                            'zaras_kw' => $user->rent_details->zaras_kw,
                            'berles_kezd_datum' => $user->rent_details->berles_kezd_datum,
                            'berles_kezd_ido' => $user->rent_details->berles_kezd_ido,
                            'berles_veg_datum' => $user->rent_details->berles_veg_datum,
                            'berles_veg_ido' => $user->rent_details->berles_veg_ido,
                            'megtett_tavolsag' => $user->rent_details->megtett_tavolsag,
                            'berles_osszeg' => $user->rent_details->berles_osszeg,
                            'parkolas' => $user->rent_details->parkolasi_perc,
                            'szamla_kelt' => $user->rent_details->szamla_kelt,
                        ]
                    ];
                });
            }),
        ];
    }
}

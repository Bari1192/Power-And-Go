<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BillResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "szamla_azon"=>$this->id,
            "felh_id" => $this->felh_id,
            "username"=>$this->whenLoaded('users')->felh_nev,
            "szemely_id" => $this->szemely_id,
            "szemely"=>$this->whenLoaded('persons')->v_nev.' '.$this->whenLoaded('persons')->k_nev,
            "car_id" => $this->car_id,
            "berles_kezd_datum" => $this->berles_kezd_datum,
            "berles_kezd_ido" => $this->berles_kezd_ido,
            "berles_veg_datum" => $this->berles_veg_datum,
            "berles_veg_ido" => $this->berles_veg_ido,
            "megtett_tavolsag" => $this->megtett_tavolsag,
            "parkolasi_perc" => $this->parkolasi_perc,
            "vezetesi_perc" => $this->vezetesi_perc,
            "osszeg" => $this->osszeg,
            "szamla_tipus"=>$this->szamla_tipus,
            "szamla_kelt" => date_format($this->szamla_kelt, "Y-m-d H:i:s"),
            "szamla_status" => $this->szamla_status,
        ];
    }
}

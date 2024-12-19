<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BillResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            // "szamla_tipus" => $this->szamla_tipus,
            "felh_id" => $this->felh_id,
            "szemely_id" => $this->szemely_id,
            "auto_azon" => $this->auto_azon,
            "berles_kezd_datum" => $this->berles_kezd_datum,
            "berles_kezd_ido" => $this->berles_kezd_ido,
            "berles_veg_datum" => $this->berles_veg_datum,
            "berles_veg_ido" => $this->berles_veg_ido,
            "megtett_tavolsag" => $this->megtett_tavolsag,
            "parkolasi_perc" => $this->parkolasi_perc,
            "vezetesi_perc" => $this->vezetesi_perc,
            "osszeg" => $this->osszeg,
            "szamla_kelt" => $this->szamla_kelt,
            "szamla_status" => $this->szamla_status,
        ];
    }
}

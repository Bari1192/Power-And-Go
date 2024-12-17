<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SzamlaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "szamla_azon" => $this->szamla_id,
            "szamla_tipus" => $this->szamla_tipus,
            "osszeg" => number_format($this->osszeg, 0, '', ' '),
            "megtett_tavolsag" => $this->megtett_tavolsag,
            "parkolasi_perc" => $this->parkolasi_perc,
            "vezetesi_perc" => $this->vezetesi_perc,
            "berles_kezd_datum" => $this->berles_kezd_datum,
            "berles_kezd_ido" => $this->berles_kezd_ido,
            "berles_veg_datum" => $this->berles_veg_datum,
            "berles_veg_ido" => $this->berles_veg_ido,
            "szamla_kelt" => $this->szamla_kelt,
            "szamla_status" => $this->szamla_status,
            "auto" => new AutoResource($this->whenLoaded('autok')),
        ];
    }
}

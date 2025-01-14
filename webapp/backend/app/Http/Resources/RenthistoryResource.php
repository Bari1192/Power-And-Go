<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RenthistoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return
            [
                "id" => $this->id,
                "auto_azon" => $this->auto_azon,
                "szemely_azon" => $this->szemely_azon,
                "nyitas_szaz" => $this->nyitas_szaz,
                "nyitas_kw" => $this->nyitas_kw,
                "zaras_szaz" => $this->zaras_szaz,
                "zaras_kw" => $this->zaras_kw,
                "berles_kezd_datum" => $this->berles_kezd_datum,
                "berles_kezd_ido" => $this->berles_kezd_ido,
                "berles_veg_datum" => $this->berles_veg_datum,
                "berles_veg_ido" => $this->berles_veg_ido,
                "megtett_tavolsag" => $this->megtett_tavolsag,
                "parkolas_kezd" => $this->parkolas_kezd,
                "parkolas_veg" => $this->parkolas_veg,
                "parkolasi_perc" => $this->parkolasi_perc,
                "vezetesi_perc" => $this->vezetesi_perc,
                "berles_osszeg" => $this->berles_osszeg,
                "auto" => new CarResource($this->whenLoaded('auto')),
                "szemely" => new PersonResource($this->whenLoaded('person')),
                "felhasznalo" => new UserResource($this->whenLoaded('person.user')),
            ];
    }
}

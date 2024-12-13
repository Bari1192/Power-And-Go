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
                'lezart_berles_id' => $this->lezart_berles_id,
                'auto_azonosito' => $this->auto_azonosito,
                'auto_kategoria' => $this->auto_kategoria,
                'szemely_id_fk' => $this->szemely_id_fk,
                'nyitas_toltes_szazalek' => $this->nyitas_toltes_szazalek,
                'nyitas_toltes_kw' => $this->nyitas_toltes_kw,
                'zaras_toltes_szazalek' => $this->zaras_toltes_szazalek,
                'zaras_toltes_kw' => $this->zaras_toltes_kw,
                'berles_kezd_datum' => $this->berles_kezd_datum,
                'berles_kezd_ido' => $this->berles_kezd_ido,
                'berles_veg_datum' => $this->berles_veg_datum,
                'berles_veg_ido' => $this->berles_veg_ido,
                'megtett_tavolsag' => $this->megtett_tavolsag,
                'parkolas_kezd' => $this->parkolas_kezd,
                'parkolas_veg' => $this->parkolas_veg,
                'parkolasi_perc' => $this->parkolasi_perc,
                'vezetesi_perc' => $this->vezetesi_perc,
                'berles_osszeg' => $this->berles_osszeg,
            ];
    }
}

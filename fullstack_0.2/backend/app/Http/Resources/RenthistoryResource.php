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
                'id' => $this->lezart_berles_id,
                'carId' => $this->auto_azonosito,
                'carCategory' => $this->auto_kategoria,
                'personId' => $this->szemely_id_fk,
                'openPercent' => $this->nyitas_toltes_szazalek,
                'openKW' => $this->nyitas_toltes_kw,
                'closedPercent' => $this->zaras_toltes_szazalek,
                'closedKW' => $this->zaras_toltes_kw,
                'startDate' => $this->berles_kezd_datum,
                'startTime' => $this->berles_kezd_ido,
                'closeDate' => $this->berles_veg_datum,
                'closeTime' => $this->berles_veg_ido,
                'distance' => $this->megtett_tavolsag,
                'parkStart' => $this->parkolas_kezd,
                'parkEnd' => $this->parkolas_veg,
                'parkSum' => $this->parkolasi_perc,
                'driveMin' => $this->vezetesi_perc,
                'rentCost' => $this->berles_osszeg,
            ];
    }
}

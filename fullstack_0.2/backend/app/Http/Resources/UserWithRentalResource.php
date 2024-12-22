<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserWithRentalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'user_id' => $this->id,
            'username' => $this->felh_nev,

            'berles_kezd_datum' => $this->rent_details->berles_kezd_datum ?? null,
            'berles_kezd_ido' => $this->rent_details->berles_kezd_ido ?? null,
            'berles_veg_datum' => $this->rent_details->berles_veg_datum ?? null,
            'berles_veg_ido' => $this->rent_details->berles_veg_ido ?? null,
            'megtett_tavolsag' => $this->rent_details->megtett_tavolsag ?? null,
            'parkolas_kezd' => $this->rent_details->parkolas_kezd ?? null,
            'parkolas_veg' => $this->rent_details->parkolas_veg ?? null,
            'parkolasi_perc' => $this->rent_details->parkolasi_perc ?? null,
            'vezetesi_perc' => $this->rent_details->vezetesi_perc ?? null,
            'berles_osszeg' => $this->rent_details->berles_osszeg ?? null,
            'rentstatus' => $this->rent_details->rentstatus ?? null,
        ];
    }
}

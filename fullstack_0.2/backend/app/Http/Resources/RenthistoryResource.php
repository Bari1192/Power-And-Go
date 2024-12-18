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
                'lezart_id' => $this->id,
                'auto' => new CarResource($this->whenLoaded('auto')),
                'felhasznalo' => new UserResource($this->whenLoaded('felhasznalo')),
                'szemely'=> new PersonResource($this->whenLoaded('szemely')),
                'berles_kezd_datum' => $this->berles_kezd_datum,
                'berles_veg_datum' => $this->berles_veg_datum,
                'megtett_tavolsag' => $this->megtett_tavolsag,
                'berles_osszeg' => number_format($this->berles_osszeg,0,'',' '),
            ];
    }
}

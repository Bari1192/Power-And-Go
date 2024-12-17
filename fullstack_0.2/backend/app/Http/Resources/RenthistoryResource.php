<?php

namespace App\Http\Resources;

use App\Models\Kategoria;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RenthistoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return
            [
                'lezart_id' => $this->lezart_berles_id,
                'auto' => new AutoResource($this->whenLoaded('auto')),
                'felhasznalo' => new UserResource($this->whenLoaded('felhasznalo')),
                'szemely'=> new SzemelyResource($this->whenLoaded('szemely')),
                'berles_kezd_datum' => $this->berles_kezd_datum,
                'berles_veg_datum' => $this->berles_veg_datum,
                'megtett_tavolsag' => $this->megtett_tavolsag,
                'berles_osszeg' => $this->berles_osszeg,
            ];
    }
}

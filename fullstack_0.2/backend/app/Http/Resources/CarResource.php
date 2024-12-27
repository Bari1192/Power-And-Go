<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarResource extends JsonResource
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
            'kilometerora' => number_format($this->kilometerora,0,'',' '),
            'gyartasi_ev' => $this->gyartasi_ev,
            'flotta_adatok' => new FleetResource($this->whenLoaded('fleet')),
        ];
    }
}

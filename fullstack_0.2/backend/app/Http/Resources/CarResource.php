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
            'kilometerora' => number_format($this->kilometerora, 0, '', ' '),
            'gyartasi_ev' => $this->gyartasi_ev,
            'tipus' => $this->whenLoaded('fleet')->tipus,
            'gyarto' => $this->whenLoaded('fleet')->gyarto,
            'teljesitmeny'=>$this->whenLoaded('fleet')->teljesitmeny,
            'vegsebesseg'=>$this->whenLoaded('fleet')->vegsebesseg,
            'gumimeret'=>$this->whenLoaded('fleet')->gumimeret,
            'hatotav'=>$this->whenLoaded('fleet')->hatotav,
        ];
    }
}

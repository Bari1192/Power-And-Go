<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AutoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'autok_id' => $this->autok_id,
            'status' => new CarstatusResource($this->whenLoaded('carstatus')),
            'toltes_szazalek' => $this->toltes_szazalek,
            'toltes_kw' => $this->toltes_kw,
            'hatotav' => $this->becsult_hatotav,
            'rendszam' => $this->rendszam,
            'km_ora_allas' => number_format($this->km_ora_allas, 0, '', ' '),
            'gyartasi_ev' => $this->gyartasi_ev,
            'flotta' => new FleetResource($this->whenLoaded('flotta')),
            'histories' =>RenthistoryResource::collection($this->whenLoaded('lezartberlesek')),
            'felsz_id_fk'=>$this->felsz_id_fk,
            'kategoria_besorolas_fk'=>$this->kategoria_besorolas_fk,
            'szamlak'=>new SzamlaResource($this->whenLoaded('szamlak')),
        ];
    }
}

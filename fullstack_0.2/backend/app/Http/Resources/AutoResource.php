<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AutoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'auto_id' => $this->autok_id,
            'status' => new CarstatusResource($this->whenLoaded('carstatus')),
            'tolt_szaz' => $this->toltes_szazalek,
            'tolt_kw' => $this->toltes_kw,
            'hatotav' => $this->becsult_hatotav,
            'rendszam' => $this->rendszam,
            'km_allas' => number_format($this->km_ora_allas, 0, '', ' '),
            'gyart_ev' => $this->gyartasi_ev,
            'flotta' => new FleetResource($this->whenLoaded('flotta')),
            // 'histories' => new RenthistoryResource($this->whenLoaded('lezartberlesek')),
            'felsz_id' => $this->felsz_id_fk,
            'kategoria' => $this->kategoria_besorolas_fk,
            //'szamlak' => SzamlaResource::collection($this->whenLoaded('szamlak')),
            'tickets' => TicketResource::collection($this->whenLoaded('tickets')),
        ];
    }
}

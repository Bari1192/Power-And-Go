<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'car_id' => $this->autok_id,
            'power_perc' => $this->toltes_szazalek,
            'power_kw' => $this->toltes_kw,
            'assum_dist' => $this->becsult_hatotav,
            'plate' => $this->rendszam,
            'actual_km' => number_format($this->km_ora_allas, 0, '', ' '),
            'made' => $this->gyartasi_ev,
            'equipment' => $this->felsz_id_fk,
            'category' => $this->kategoria_besorolas_fk,
            'status' => new CarstatusResource($this->whenLoaded('carstatus')),
            'fleet' => new FleetResource($this->whenLoaded('flotta')),
            'tickets' => TicketResource::collection($this->whenLoaded('tickets')),
        ];
    }
}

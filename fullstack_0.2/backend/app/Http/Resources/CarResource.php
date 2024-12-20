<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'gyarto' => $this->fleet->gyarto,
            'tipus' => $this->fleet->tipus,
            'hatotav' => $this->fleet->hatotav,
            'teljesitmeny' => $this->fleet->teljesitmeny,
            'vegsebesseg' => $this->fleet->vegsebesseg,
            'rendszam' => $this->rendszam,
            'toltes_szaz' => $this->toltes_szaz,
            'toltes_kw' => $this->toltes_kw,
            'becs_tav' => $this->becs_tav,
            'status' => $this->carstatus->status_name,
            'tickets' => $this->whenLoaded('tickets')->isNotEmpty()
                ? TicketResource::collection($this->tickets)
                : null,
            'kategoria' => $this->kategoria,
            'felszereltseg' => $this->felszereltseg,
            'kilometerora' => number_format($this->kilometerora, 0, '', ' '),
            'gyartasi_ev' => $this->gyartasi_ev,
            'berlok' => UserResource::collection($this->whenLoaded('berlok')),
            'details' => $this->berlok->map(function ($user) {
                return [
                    'nyitas_szaz' => $user->pivot->nyitas_szaz,
                    'nyitas_kw' => $user->pivot->nyitas_kw,
                    'zaras_szaz' => $user->pivot->zaras_szaz,
                    'zaras_kw' => $user->pivot->zaras_kw,
                    'berles_kezd_datum' => $user->pivot->berles_kezd_datum,
                    'berles_kezd_ido' => $user->pivot->berles_kezd_ido,
                ];
            }),
        ];
    }
}

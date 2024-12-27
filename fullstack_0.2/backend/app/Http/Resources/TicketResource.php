<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'car_id' => $this->car_id,
            'status_id' => $this->status_id,
            'status_descrip' => $this->whenLoaded('status', fn() => $this->status->status_descrip),
            'bejelentve' => $this->bejelentve,
            'car' => $this->whenLoaded('auto', function () {
                return [
                    'rendszam' => $this->auto->rendszam,
                    'toltes_szaz' => $this->auto->toltes_szaz,
                    'toltes_kw' => $this->auto->toltes_kw,
                    'becs_tav' => $this->auto->becs_tav,
                ];
            }),
        ];
    }
}

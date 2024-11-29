<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AutoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            'autok_id'=>$this->autok_id,
            'rendszam'=>$this->rendszam,
            'km_ora_allas'=>$this->km_ora_allas,
            'gyartasi_ev'=>$this->gyartasi_ev,
        ];
    }
}

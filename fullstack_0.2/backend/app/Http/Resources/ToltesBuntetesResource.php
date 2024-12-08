<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ToltesBuntetesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'szamla_id' => $this->szamla_id,
            'szemely_id' => $this->szemely_id,
            'felh_id' => $this->felh_id,
            'szamla_status' => $this->szamla_status,
            'szamla_tipus' => "Minimum töltési ÁSZF megsértése.",
            'osszeg' => number_format($this->osszeg, 0, '', ' '), 
            'szamla_kelt' => $this->szamla_kelt,
        ];
    }
}

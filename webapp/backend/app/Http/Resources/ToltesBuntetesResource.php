<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ToltesBuntetesResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'szamla_id' => $this->id,
            'szemely_id' => $this->szemely_id,
            'felh_id' => $this->felh_id,
            'szamla_status' => $this->szamla_status,
            'szamla_tipus' => "Minimum töltési ÁSZF megsértése.",
            'osszeg' => number_format($this->osszeg, 0, '', ' '), 
            'szamla_kelt' => date_format($this->szamla_kelt, "Y-m-d H:i:s"),
        ];
    }
}

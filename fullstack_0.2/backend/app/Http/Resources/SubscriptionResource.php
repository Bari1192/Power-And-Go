<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "elofiz_id" => $this->id,
            "elofiz_nev" => $this->elofiz_nev,
            "havi_dij" => $this->havi_dij,
            "eves_dij" => $this->eves_dij,
        ];
    }
}

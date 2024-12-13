<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FleetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return
            [
                'flotta_id' => $this->flotta_id,
                'gyarto' => $this->gyarto,
                'tipus' => $this->tipus,
                'teljesitmeny' => $this->teljesitmeny,
                'vegsebesseg' => $this->vegsebesseg,
                // 'gumimeret' => $this->gumimeret,
                'hatotav' => $this->hatotav,
            ];
    }
}

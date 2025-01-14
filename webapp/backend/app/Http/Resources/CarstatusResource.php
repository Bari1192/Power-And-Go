<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarstatusResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return
            [
                "id" => $this->id,
                "status_name" => $this->status_name,
                "status_descrip" => $this->status_descrip,
            ];
    }
}

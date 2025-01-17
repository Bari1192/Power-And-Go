<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "sub_id" => $this->id, 
            "sub_name" => $this->sub_name,
            "sub_monthly" => $this->sub_monthly,
            "sub_annual" => $this->sub_annual,
        ];
    }
}

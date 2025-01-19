<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource

{
    public function toArray(Request $request): array
    {
            return [
                'user_id' => $this->id,
                'person_id'=>$this->whenLoaded('person',$this->person_id),
                'user_name' => $this->user_name,
            ];
    }
}

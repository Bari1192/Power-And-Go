<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Hash;

class PersonResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "person_id" => $this->id,
            "person_password" => Hash::make($this->person_password),
            "id_card" => $this->id_card,
            "driving_license" => $this->driving_license,
            "license_start_date" => $this->license_start_date,
            "license_end_date" => $this->license_end_date,
            "firstname" => $this->firstname,
            "lastname" => $this->lastname,
            "birth_date" => $this->birth_date,
            "phone" => $this->phone,
            "email" => $this->email,
        ];
    }
}

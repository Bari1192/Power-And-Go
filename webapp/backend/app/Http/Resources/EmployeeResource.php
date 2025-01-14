<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "szemely_azon" => $this->szemely_azon,
            "terulet" => $this->terulet,
            "munkakor" => $this->munkakor,
            "beosztas" => $this->beosztas,
            "munkaber_tipus" => $this->munkaber_tipus,
            "fizetes" => $this->fizetes,
            "belepes_datum" => $this->belepes_datum,
        ];
    }
}

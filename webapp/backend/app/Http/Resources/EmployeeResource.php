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
            "person_id" => $this->person_id,
            "field" => $this->field,
            "role" => $this->role,
            "position" => $this->position,
            "salary_type" => $this->salary_type,
            "salary" => $this->salary,
            "hire_date" => $this->hire_date,
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            "category_id" => ["nullable", "integer", "exists:categories,id"],
            "equipment_class" => ["nullable", "integer", "exists:equipments,id"],
            "fleet_id" => ["integer", "exists:fleets,id"],
            "status" => ["integer", "exists:carstatus,id"],
            "plate" => ["required", "string", "between:7,10", "unique:cars,plate"],
            "odometer" => ["nullable", "integer", "between:0,300000"],
            "manufacturing_year" => ["required", "integer", "min:2014", "max:" . date('Y')],
            "power_kw" => ["required", "numeric", "regex:/^\d+(\.\d)?$/", "between:0,500"],
            "power_percent" => ["required", "numeric", "regex:/^\d+(\.\d{1,2})?$/", "between:0,100"],
            "estimated_range" => ["required", "numeric", "regex:/^\d+(\.\d)?$/", "between:0,1000"],
            "estimated_range" => ["required", "decimal:1", "between:0,1000"],
        ];
    }
}

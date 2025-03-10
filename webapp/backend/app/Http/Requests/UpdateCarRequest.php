<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            "fleet_id" => ["nullable", "integer", "exists:fleets,id"],
            "category_id" => ["nullable", "integer", "exists:categories,category_class"],
            "equipment_class" => ["nullable", "integer", "exists:equipments,id"],
            "status" => ["nullable", "integer", "exists:carstatus,id"],
            "plate" => ["required", "string", "min:5", "max:8", "unique:cars,plate"],
            "odometer" => ["nullable", "integer", "between:0,300000"],
            "manufactured" => ["nullable", "integer", "min:2014", "max:" . date('Y')],
            "power_percent" => ["required", "decimal:2", "between:0,100"],
            "power_kw" => ["required", "decimal:1", "between:0,500"],
            "estimated_range" => ["required", "decimal:1", "between:0,1000"],
        ];
    }
}

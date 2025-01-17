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
            "plate" => ["required", "string", "between:7,10"],
            "category_id" => ["nullable", "integer", "exists:categories,category_class"],
            "felszereltseg" => ["nullable", "integer", "exists:equipments,id"],
            "flotta_azon" => ["nullable", "integer", "exists:fleets,id"],
            "odometer" => ["nullable", "integer", "between:0,300000"],
            "manufacturing_year" => ["nullable", "integer", "regex:/^\d{4}$/"],
            "power_percent" => ["required", "decimal:2", "between:0,100"],
            "power_kw" => ["required", "decimal:1", "between:0,500"],
            "estimated_range" => ["required", "decimal:1", "between:0,1000"],
            "status" => ["nullable", "integer", "exists:carstatus,id"],
        ];
    }
}

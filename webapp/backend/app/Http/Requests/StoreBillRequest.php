<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBillRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "bill_type" => ["required", Rule::in(['rental','accident','damage','charging_penalty'])],
            "user_id" => ["required","integer","exists:users,id"],
            "person_id" => ["required","integer","exists:persons,id"],
            "car_id" => ["required","integer","exists:cars,id"],
            "total_cost" => ["required", "integer", "min:0"],
            "driving_distance" => ["nullable", "integer", "min:0"],
            "parking_minutes" => ["nullable", "integer", "min:0"],
            "driving_minutes" => ["nullable", "integer", "min:0"],
            "rent_start_date" => ["required", "date_format:Y-m-d", "before_or_equal:rent_end_date"],
            "rent_start_time" => ["required", "date_format:H:i:s"],
            "rent_end_date" => ["required", "date_format:Y-m-d", "after_or_equal:rent_start_date"],
            "rent_end_time" => ["required", "date_format:H:i:s"],
            "invoice_date" => ["nullable", "date_format:Y-m-d H:i:s"],
            "invoice_status" => ["required", Rule::in(['active','pending','archiv'])],
        ];
    }
}

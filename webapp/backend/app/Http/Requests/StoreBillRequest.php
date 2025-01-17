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
            "total_cost" => ["required", "integer", "min:0"],
            "driving_distance" => ["nullable", "integer", "min:0"],
            "parking_minutes" => ["nullable", "integer", "min:0"],
            "driving_minutes" => ["nullable", "integer", "min:0"],
            "rent_start_date" => ["required", "date", "before_or_equal:rent_end_date"],
            "rent_start_time" => ["required", "date_format:H:i"],
            "rent_end_date" => ["required", "date", "after_or_equal:rent_start_date"],
            "rent_end_time" => ["required", "date_format:H:i"],
            "invoice_date" => ["nullable", "date"],
            "invoice_status" => ["required", "in:active,pending,archiv"],
        ];
    }
}

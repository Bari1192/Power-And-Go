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
            "bill_type" => ["required", Rule::in(['rental', 'accident', 'damage', 'charging_penalty'])],
            "user_id" => ["required", "integer", "exists:users,id"],
            "person_id" => ["required", "integer", "exists:persons,id"],
            "car_id" => ["required", "integer", "exists:cars,id"],
            "rent_id" => ["required", "integer", "exists:car_user_rents,id"],
            "total_cost" => ["required", "integer", "min:0"],
            "distance" => ["nullable", "integer", "min:0"],
            "parking_minutes" => ["nullable", "integer", "min:0"],
            "driving_minutes" => ["nullable", "integer", "min:0"],
            "rent_start" => ["required", "date", "before_or_equal:rent_close"],
            "rent_close" => ["required", "date", "after_or_equal:rent_start"],
            "invoice_date" => ["nullable", "date_format:Y-m-d H:i:s"],
            "invoice_status" => ["required", Rule::in(['active', 'pending', 'archiv'])],
            "email_sent" => ["required", "boolean"],
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFleetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            "manufacturer" => ['required', 'string', 'between:2,30'],
            "carmodel" => ['required', 'string', 'between:2,30'],
            "driving_range" => ['required', 'integer', 'min:125', 'max:1000'],
            "motor_power" => ['required', 'integer', 'min:18', 'max:500'],
            "top_speed" => ['required', 'integer', 'min:130', 'max:300'],
            "tire_size" => ['required'],
        ];
    }
}

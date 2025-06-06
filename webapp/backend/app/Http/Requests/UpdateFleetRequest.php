<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFleetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return
            [
                'id' => ["exists:fleets,id"],
                "manufacturer" => ['required', 'string', 'between:2,30'],
                "carmodel" => ['required', 'string', 'between:2,30'],
                "driving_range" => ['required', 'integer', 'min:125', 'max:1000'],
                "motor_power" => ['required', 'integer', 'min:18', 'max:500'],
                "top_speed" => ['required', 'integer', 'min:130', 'max:300'],
                "tire_size" => ['required', 'string', 'regex:/^\d{3}\|\d{2}-R\d{2}$/'],
            ];
    }
}

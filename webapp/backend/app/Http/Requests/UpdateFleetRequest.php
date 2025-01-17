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
                'id' => ["required"],
                'manufacturer' => ["required"],
                'carmodel' => ["required"],
                'motor_power' => ["required"],
                'top_speed' => ["required"],
                'tire_size' => ["required"],
                'driving_range' => ["required"],
            ];
    }
}

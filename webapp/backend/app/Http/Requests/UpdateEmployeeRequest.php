<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "szemely_azon" => 'required',
            "terulet" => 'required',
            "munkakor" => 'required',
            "beosztas" => 'required',
            "munkaber_tipus" => 'required',
            "fizetes" => 'required',
            "belepes_datum" => 'required',
        ];
    }
}

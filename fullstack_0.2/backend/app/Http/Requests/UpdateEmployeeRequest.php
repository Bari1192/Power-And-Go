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
            "terulet" => ["required"],
            "munkakor" => ["required"],
            "beosztas" => ["required"],
            "munkaido" => ["required"],
            "fizetes_ossz" => ["required"],
            "belepes_datum" => ["required"],
        ];
    }
}

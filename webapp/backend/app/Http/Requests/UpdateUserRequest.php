<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "felh_id" => ["required"],
            "person_id" => ["required"],
            "felh_egyenleg" => ["required"],
            "jelszo_2_4" => ["required"],
            "felh_nev" => ["required"],
            "elofiz_id" => ["required"],
        ];
    }
}

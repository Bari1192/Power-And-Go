<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "felh_id" => ["required"],
            "szemely_id" => ["required"],
            "felh_egyenleg" => ["required"],
            "jelszo_2_4" => ["required"],
            "felh_nev" => ["required"],
            "elofiz_id" => ["required"],
        ];
    }
}

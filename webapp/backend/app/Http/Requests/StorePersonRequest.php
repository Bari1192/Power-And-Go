<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePersonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            "person_password" => ["required", "string", "size:8"],
            "id_card" => ["required", "string", "unique:persons,id_card"],
            "driving_license" => ["nullable", "string", "unique:persons,driving_license"],
            "license_start_date" => ["nullable", "date"],
            "license_end_date" => ["nullable", "date"], 
            "firstname" => ["required", "string", "max:100"],
            "lastname" => ["required", "string", "max:100"],
            "birth_date" => ["required", "date"], 
            "phone" => ["required", "string", "starts_with:+36", "size:12"], 
            "email" => ["required", "string", "email", "unique:persons,email"],
        ];
    }
}

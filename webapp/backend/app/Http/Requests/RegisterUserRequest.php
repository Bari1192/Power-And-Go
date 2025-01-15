<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            "szemely_id" => ["required", "exists:persons,id"],
            "felh_nev" => ["required", "string", "max:50","unique:users,felh_nev"],
            "password" => ["required","string", "min:8"],
        ];
    }
}

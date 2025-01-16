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
            "szig_szam" => ["required"],
            "v_nev" => ["required"],
            "k_nev" => ["required"],
            "szul_datum" => ["required"],
            "telefon" => ["required"],
            "email" => ["required"],

            "user_name" => ["required", "string", "max:50", "unique:users,user_name"],
            'password' => ["required", "string", "min:8"],
        ];
    }
}

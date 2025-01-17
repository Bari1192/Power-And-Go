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
            "id_card" => ["required"],
            "firstname" => ["required"],
            "lastname" => ["required"],
            "birth_date" => ["required"],
            "phone" => ["required"],
            "email" => ["required"],

            "user_name" => ["required", "string", "max:50", "unique:users,user_name"],
            'password' => ["required", "string", "min:8"],
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthenticateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            "user_name" => ["required", "min:8", "max:20"],
            "password" => ["required", "string", "min:8"],
        ];
    }
}

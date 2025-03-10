<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthenticateUserPinRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            "user_name" => ["required", "string", "exists:users,user_name"],
            "pin" => ["required", "string","Max:255"],
        ];
    }
}

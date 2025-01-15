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
            "felh_nev" => ["required","max:20","min:8"],
            "password" => ["required","string", "min:8"],
        ];
    }
}

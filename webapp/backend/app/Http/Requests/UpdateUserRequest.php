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
            "user_id" => ["required"],
            "person_id" => ["required"],
            "account_balance" => ["required"],
            "password_2_4" => ["required"],
            "user_name" => ["required"],
            "sub_id" => ["required"],
        ];
    }
}

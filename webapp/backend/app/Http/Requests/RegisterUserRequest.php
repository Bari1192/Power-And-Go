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
            'person_id' => ['required', 'integer', 'exists:persons,id'],
            'user_name' => ['required', 'string', 'max:45', 'unique:users,user_name'],
            'password' => ['required', 'string', 'min:8', 'max:60'],
            'password_2_4' => ['required', 'string', 'size:2'],
            'account_balance' => ['required', 'integer', 'min:0', 'max:1000000'],
            'sub_id' => ['required', 'exists:subscriptions,id'],
        ];
    }
}

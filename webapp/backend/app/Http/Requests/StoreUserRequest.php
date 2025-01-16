<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
        'person_id' => ['required'],
        'user_name' => ['required', 'string', 'max:50', 'unique:users,user_name'], 
        'password' => ['required', 'string', 'min:8'],
        'jelszo_2_4' => ['required'],
        'felh_egyenleg' => ['required'],
        'elofiz_id' => ['required'],
        ];
    }
}

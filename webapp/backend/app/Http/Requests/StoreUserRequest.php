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
            'person_id' => ['required', 'integer', 'exists:persons,id'],
            'sub_id' => ['required', 'exists:subscriptions,id'],
            'user_name' => ['required', 'string', 'max:45', 'unique:users,user_name'],
            'password' => ['required', 'string', 'min:8', 'max:60'],
            'password_2_4' => ['required', 'string', 'size:2'],
            'vip_discount' => ['required', 'boolean'],
            'bonus_minutes' => ['required', 'integer', 'min:0'],
            'plant_tree' => ['required', 'boolean'],
            'bonus_min_exp' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'driving_minutes' => ['required', 'integer', 'min:0'],
            'account_balance' => ['required', 'integer', 'min:0', 'max:100000'],
            'contributions' => ['required', 'integer', 'min:0', 'max:100000'],
        ];
    }
}

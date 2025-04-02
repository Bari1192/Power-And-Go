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
            'user_name' => ['required', 'string', 'between:8,45', 'unique:users,user_name'],
            'pin' => ['required', 'string'],
            'password_2_4' => ['required', 'string', 'size:2'],
            'account_balance' => ['required', 'integer', 'min:0', 'max:100000'],
            'sub_id' => ['required', 'exists:subscriptions,id'],
            'plant_tree' => ['required', 'boolean'],
            'vip_discount' => ['required', 'boolean'],
            'bonus_min_exp' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'bonus_minutes' => ['required', 'integer', 'min:0'],
            'driving_minutes' => ['required', 'integer', 'min:0'],
            'contributions' => ['required', 'integer', 'min:0', 'max:100000'],
            'role' => [],
        ];
    }
}

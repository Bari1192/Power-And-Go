<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Unique;

class RegisterUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            "firstname" => ["required", "string", "min:3", "max:50"],
            "lastname" => ["required", "string", "min:3", "max:25"],
            "birth_date" => [
                "required",
                "date",
                "before_or_equal:" . now()->subYears(18)->format('Y-m-d'),
            ],
            "phone" => ["required", "string", "starts_with:+36,0036", "min:12", "max:15", "unique:persons,phone"],
            "email" => ["required", "string", "unique:persons,email"],
            'id_card' => ['required', 'unique:persons,id_card'],
            "person_password" => ["required", "string", "min:8"],
            'user_name' => ['required', 'string', 'unique:users,user_name'],
            'pin' => ['required', 'string', 'Max:255'],
            'vip_discount' => ['boolean'],
            'bonus_minutes' => ['integer'],
            'plant_tree' => ['boolean'],
            'bonus_min_exp' => ['date_format:Y-m-d', 'after_or_equal:today'],
            'driving_minutes' => ['integer', 'min:0'],
            'account_balance' => ['integer', 'min:0', 'max:100000'],
            'contributions' => ['integer', 'min:0', 'max:100000'],
            'role' => [''],
        ];
    }
}

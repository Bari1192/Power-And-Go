<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            "category_class" => ["required","integer","between:1,10"],
            "motor_power" => ["required","integer","between:18,200"],
        ];
    }
}

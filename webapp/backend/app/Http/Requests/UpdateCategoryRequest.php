<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            "id" => ["required", "exists:categories,id"],
            "category_class" => ["required", "integer", "between:1,20"],
            "motor_power" => ["required", "integer", "between:18,200"],
        ];
    }
}

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
            //"id" => ["required", "exists:categories,id"],
            "kat_besorolas" => ["required","integer","between:1,10"],
            "teljesitmeny" => ["required","integer","between:18,200"],
        ];
    }
}

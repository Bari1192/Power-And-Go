<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            "id" => ["required", "exists:categories,id"],
            "kat_besorolas" => ["required","integer","between:1,10"],
            "teljesitmeny" => ["required","integer","between:18,200"],
        ];
    }
}

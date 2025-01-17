<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            "person_id"=>'required|integer|exists:persons,id',
            "field"=>'required',
            "role"=>'required',
            "position"=>'required',
            "salary_type"=>'required',
            "salary"=>'required',
            "start_date"=>'required',
        ];
    }
}

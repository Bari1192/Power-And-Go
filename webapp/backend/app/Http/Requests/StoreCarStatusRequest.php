<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCarStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'status_name' => ['required', 'min:8','max:50','alpha', 'unique:carstatus'],
            'status_descrip' => 'required|regex:/^[a-zA-Z\s]+$/|max:255', # Betűk és szóközök
        ];
    }
}

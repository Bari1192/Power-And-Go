<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "car_id" => ['required', 'integer', 'exists:cars,id'],
            "status_id" => ['required', 'integer', 'between:1,7', 'exists:carstatus,id'],
            "description" => ['required', 'max:255'],
        ];
    }
}

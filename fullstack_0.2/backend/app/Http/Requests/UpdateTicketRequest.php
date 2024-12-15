<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "description" => ['required', 'string', 'max:255'],
            "car_id" => ['required', 'integer', 'exists:autok,autok_id'],
            "status_id" => ['required', 'integer', 'exists:carstatus,id'],
        ];
    }
}

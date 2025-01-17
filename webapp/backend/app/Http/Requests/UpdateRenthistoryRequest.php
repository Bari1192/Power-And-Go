<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRenthistoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "lezart_id" => ['required'],
            "auto_id" => ['required'],
            // "auto_kategoria" => ['required'],
            // "person_id_fk" => ['required'],
            // "nyitas_toltes_szazalek" => ['required'],
            // "nyitas_toltes_kw" => ['required'],
            // "zaras_toltes_szazalek" => ['required'],
            // "zaras_toltes_kw" => ['required'],
            // "rent_start_date" => ['required'],
            // "rent_start_time" => ['required'],
            // "rent_end_date" => ['required'],
            // "rent_end_time" => ['required'],
            // "driving_distance" => ['required'],
            // "parking_start" => ['nullable'],
            // "parking_end" => ['nullable'],
            // "parking_minutes" => ['nullable'],
            // "driving_minutes" => ['required'],
            // "rental_cost" => ['required'],
        ];
    }
}

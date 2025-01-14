<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBillRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "szamla_tipus" => ["required", Rule::in(['berles','baleset','karokozas','toltes_buntetes'])],
            "osszeg" => ["required", "integer", "min:0"],
            "megtett_tavolsag" => ["nullable", "integer", "min:0"],
            "parkolasi_perc" => ["nullable", "integer", "min:0"],
            "vezetesi_perc" => ["nullable", "integer", "min:0"],
            "berles_kezd_datum" => ["required", "date", "before_or_equal:berles_veg_datum"],
            "berles_kezd_ido" => ["required", "date_format:H:i"],
            "berles_veg_datum" => ["required", "date", "after_or_equal:berles_kezd_datum"],
            "berles_veg_ido" => ["required", "date_format:H:i"],
            "szamla_kelt" => ["nullable", "date"],
            "szamla_status" => ["required", "in:active,pending,archiv"],
        ];
    }
}

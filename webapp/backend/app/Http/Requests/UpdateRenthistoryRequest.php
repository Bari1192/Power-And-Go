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
            // "szemely_id_fk" => ['required'],
            // "nyitas_toltes_szazalek" => ['required'],
            // "nyitas_toltes_kw" => ['required'],
            // "zaras_toltes_szazalek" => ['required'],
            // "zaras_toltes_kw" => ['required'],
            // "berles_kezd_datum" => ['required'],
            // "berles_kezd_ido" => ['required'],
            // "berles_veg_datum" => ['required'],
            // "berles_veg_ido" => ['required'],
            // "megtett_tavolsag" => ['required'],
            // "parkolas_kezd" => ['nullable'],
            // "parkolas_veg" => ['nullable'],
            // "parkolasi_perc" => ['nullable'],
            // "vezetesi_perc" => ['required'],
            // "berles_osszeg" => ['required'],
        ];
    }
}

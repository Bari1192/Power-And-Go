<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePersonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "szemely_jelszo" => ["required", "string", "size:8"],
            "szig_szam" => ["required", "string", "unique:persons,szig_szam"],
            "jogos_szam" => ["nullable", "string", "unique:persons,jogos_szam"],
            "jogos_erv_kezdete" => ["nullable", "date"],
            "jogos_erv_vege" => ["nullable", "date"],
            "v_nev" => ["required", "string", "max:100"],
            "k_nev" => ["required", "string", "max:100"],
            "szul_datum" => ["required", "date"],
            "telefon" => ["required", "string", "starts_with:+36", "size:12"],
            "email" => ["required", "string", "email", "unique:persons,email"],
        ];
    }
}

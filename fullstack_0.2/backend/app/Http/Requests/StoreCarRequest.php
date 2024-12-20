<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            "rendszam" => ["required","unique:cars,rendszam"],
            "toltes_szaz" => ["required","decimal:2","min:0","max:100"],
            "toltes_kw" => ["required","decimal:1","min:0","max:100"],
            "becs_tav" => ["required","decimal:1"],
            "status" => ["required"],
            "kategoria" => ["required"],
            "felszereltseg" => ["required"],
            "flotta_azon" => ["required"],
            "kilometerora" => ["required"],
            "gyartasi_ev" => ["required"],
        ];
    }
}

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
            "rendszam" => ["required", "string", "between:7,10"],
            "kategoria" => ["nullable", "integer", "exists:categories,kat_besorolas"],
            "felszereltseg" => ["nullable", "integer", "exists:equipments,id"],
            "flotta_azon" => ["nullable", "integer", "exists:fleets,id"],
            "kilometerora" => ["nullable", "integer", "between:0,300000"],
            "gyartasi_ev" => ["nullable", "integer", "regex:/^\d{4}$/"],
            "toltes_szaz" => ["required", "decimal:2", "between:0,100"],
            "toltes_kw" => ["required", "decimal:1", "between:0,500"],
            "becs_tav" => ["required", "decimal:1", "between:0,1000"],
            "status" => ["nullable", "integer", "exists:carstatus,id"],
        ];
    }
}

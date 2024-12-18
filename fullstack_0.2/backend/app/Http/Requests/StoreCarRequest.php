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
            "rendszam" => ["required", "string", "between:7,10", "exists:autok,rendszam"],
            "kategoria_besorolas_fk" => ["nullable", "integer", "exists:kategoriak,kat_id"],
            "felsz_id_fk" => ["nullable", "integer", "exists:felszereltsegek,felsz_id"],
            "flotta_id_fk" => ["nullable", "integer", "exists:flotta_tipusok,flotta_id"],
            "km_ora_allas" => ["nullable", "integer", "between:0,300000"],
            "gyartasi_ev" => ["nullable", "integer", "between:2019,2024"],
            "toltes_szazalek" => ["required", "decimal:0,2", "between:0,100"],
            "toltes_kw" => ["required", "decimal:0,2", "between:0,500"],
            "becsult_hatotav" => ["required", "integer", "between:0,1000"],
            "status" => ["nullable", "integer", "exists:carstatus,id"],
        ];
    }
}

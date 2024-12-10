<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAutoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "rendszam" => ["required", "string", "between:7,10", "unique:autok,rendszam"],
            "kategoria_besorolas_fk" => ["required", "integer", "exists:kategoriak,kat_id"],
            "felsz_id_fk" => ["nullable", "integer", "exists:felszereltsegek,felsz_id"],
            "flotta_id_fk" => ["required", "integer", "exists:flotta_tipusok,flotta_id"],
            "km_ora_allas" => ["required", "integer","between:0,300000"],
            "gyartasi_ev" => ["required", "integer", "between:2019,2024"],
        ];
    }
}

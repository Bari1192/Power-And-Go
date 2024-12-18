<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "person_id" => $this->id,
            "szemely_jelszo" => $this->szemely_jelszo,
            "szig_szam" => $this->szig_szam,
            "jogos_szam" => $this->jogos_szam,
            "jogos_erv_kezdete" => $this->jogos_erv_kezdete,
            "jogos_erv_vege" => $this->jogos_erv_vege,
            "v_nev" => $this->v_nev,
            "k_nev" => $this->k_nev,
            "szul_datum" => $this->szul_datum,
            "telefon" => $this->telefon,
            "email" => $this->email,
        ];
    }
}

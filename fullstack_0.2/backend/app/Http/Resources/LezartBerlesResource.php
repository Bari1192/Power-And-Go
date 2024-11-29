<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LezartBerlesResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $berles_kezdete = Carbon::createFromFormat('Y-m-d H:i:s', $this->berles_kezd_datum . ' ' . $this->berles_kezd_ido);
        $berles_vege = Carbon::createFromFormat('Y-m-d H:i:s', $this->berles_veg_datum . ' ' . $this->berles_veg_ido);

        $berles_percek = $berles_kezdete->diffInMinutes($berles_vege); ### Időtartam percekben
        return [
            'lezart_berles_id' => $this->lezart_berles_id,
            'megtett_tavolsag'=>$this->megtett_tavolsag,
            ### Az AUTOMATA e-mail küldéshez kellenek ezek:
            'auto_rendszam' => $this->auto->rendszam,
            'berles_kezdete' => $berles_kezdete->format('Y-m-d H:i'),
            'berles_vege' => $berles_vege->format('Y-m-d H:i'),
            'berles_percek' => number_format($berles_percek, 0, '', ''),

            ### A FELHASZNALO e-mail és k_nev lekérése más táblákból.
            'szemely_knev'=>$this->felhasznalo->szemely->k_nev,
            'szemely_email' => $this->felhasznalo->szemely->email,
        ];
    }
}

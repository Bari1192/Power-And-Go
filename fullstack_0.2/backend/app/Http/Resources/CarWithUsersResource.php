<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarWithUsersResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'car_id' => $this->id,
            'rendszam' => $this->rendszam,
            'gyarto' => $this->whenLoaded('fleet')->gyarto,
            'tipus' => $this->whenLoaded('fleet')->tipus,
            'felhasznalok' => $this->whenLoaded('users', function () {
                return $this->users->map(function ($user) {
                    return [
                        'username' => $user->felh_nev,
                        'jelszo_2_4' => $user->jelszo_2_4,
                        'person_information' => new PersonResource($user->person),
                    ];
                });
            }),
        ];
    }
}

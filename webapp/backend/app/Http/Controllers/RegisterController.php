<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Models\Person;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function store(RegisterUserRequest $request): JsonResponse
    {
        $data = $request->validated();

        DB::beginTransaction();
        ## Adatbázisban Transaction műveletet csináljon, azaz
        ## Csak akkor jöjjön létre a regisztráció, HA
        ## A PERSON és a USER létrehozása is sikeresen megtörtént.
        ## Nem fog konzisztenciát okozni / duplikálásokat.

        try {
            $person = Person::create([
                "szemely_jelszo" => $data['password'], # Itt NO HASH!
                "szig_szam" => $data['szig_szam'],
                "jogos_szam" => $data['jogos_szam'] ?? null,
                "jogos_erv_kezdete" => $data['jogos_erv_kezdete'] ?? null,
                "jogos_erv_vege" => $data['jogos_erv_vege'] ?? null,
                "v_nev" => $data['v_nev'],
                "k_nev" => $data['k_nev'],
                "szul_datum" => $data['szul_datum'],
                "telefon" => $data['telefon'],
                "email" => $data['email'],
            ]);
            $user = User::create([
                'person_id' => $person->id, # Meg fogja kapni az ID-t, mert beszúrja a Person adatot. Max ha ez nem fut le -> rollBack()
                'user_name' => $data['user_name'],
                'password' => Hash::make($data['password']),
                'jelszo_2_4' => $person->szemely_jelszo[0] . $person->szemely_jelszo[2],
                'felh_egyenleg' => $data['felh_egyenleg'] ?? 0,
                'elofiz_id' => 1,
            ]);

            DB::commit(); # NE FELEDD!

            return response()->json([
                "data" => [
                    "message" => "A(z) $user->user_name sikeresen regisztrált.",
                ]
            ]);
        } catch (Exception $exception) {
            DB::rollBack();  # PLÁNE NE FELEDD!

            return response()->json([
                "error" =>"A regisztráció sikertelen volt.". $exception->getMessage(),
            ], 500);
        }
    }
}

        # PÉLDA REGISZTRÁCIÓ 
            ##  http://backend.vm1.test/api/register
        # PÉLDA REGISZTRÁCIÓ 
        // {
        //     "user_name": "Test1234",
        //     "password": "12345678",
        //     "felh_egyenleg": 0,
        //     "elofiz_id": "1",
        //     "szig_szam": "XX823971",
        //     "v_nev": "Teszt",
        //     "k_nev": "Adat",
        //     "szul_datum": "1990-01-01",
        //     "telefon": "+36304447777",
        //     "email": "asdasd@gmail.com"
        //   }

        ## Authenticate-re => Token-t kapni.
        ## http://backend.vm1.test/api/authenticate

        // {
            // "user_name": "Test1234",
            // "password": "12345678"
        // }
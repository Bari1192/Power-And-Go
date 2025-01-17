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
                "person_password" => $data['password'], # Itt NO HASH!
                "id_card" => $data['id_card'],
                "driving_license" => $data['driving_license'] ?? null,
                "license_start_date" => $data['license_start_date'] ?? null,
                "license_end_date" => $data['license_end_date'] ?? null,
                "firstname" => $data['firstname'],
                "lastname" => $data['lastname'],
                "birth_date" => $data['birth_date'],
                "phone" => $data['phone'],
                "email" => $data['email'],
            ]);
            $user = User::create([
                'person_id' => $person->id, # Meg fogja kapni az ID-t, mert beszúrja a Person adatot. Max ha ez nem fut le -> rollBack()
                'user_name' => $data['user_name'],
                'password' => Hash::make($data['password']),
                'password_2_4' => $person->person_password[0] . $person->person_password[2],
                'account_balance' => $data['account_balance'] ?? 0,
                'sub_id' => 1,
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
        //     "account_balance": 0,
        //     "sub_id": "1",
        //     "id_card": "XX823971",
        //     "firstname": "Teszt",
        //     "lastname": "Adat",
        //     "birth_date": "1990-01-01",
        //     "phone": "+36304447777",
        //     "email": "asdasd@gmail.com"
        //   }

        ## Authenticate-re => Token-t kapni.
        ## http://backend.vm1.test/api/authenticate

        // {
            // "user_name": "Test1234",
            // "password": "12345678"
        // }
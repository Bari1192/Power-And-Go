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
                "error" => "A regisztráció sikertelen volt." . $exception->getMessage(),
            ], 500);
        }
    }
    public function destroy(User $user)
    {
        DB::transaction(function () use ($user) {
            $user->person->delete(); 
            $user->delete();
        });
        return response()->json(['message' => 'Felhasználó és adatai törölve lettek.'], 204);
    }
}

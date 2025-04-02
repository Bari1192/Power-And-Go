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
                "firstname" => $data['firstname'],
                "lastname" => $data['lastname'],
                "person_password" => Hash::make($data['person_password']),
                "id_card" => $data['id_card'],
                "driving_license" => $data['driving_license'] ?? null,
                "license_start_date" => $data['license_start_date'] ?? null,
                "license_end_date" => $data['license_end_date'] ?? null,
                "birth_date" => $data['birth_date'],
                "phone" => $data['phone'],
                "email" => $data['email'],
            ]);
            $user = User::create([
                'person_id' => $person->id,
                'user_name' => $data['user_name'],
                'pin' => Hash::make($data['pin']),
                'password_2_4' => $data['pin'][1] . $data['pin'][3],
                'account_balance' => $data['account_balance'] ?? 0,
                'sub_id' => $data['sub_id'] ?? 1,
                'role'=>$data['role'],  // Ezt majd ki kell venni, mert így mindenki ADMIN lesz! [Tesztre van!]
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

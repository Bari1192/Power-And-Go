<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthenticateRequest;
use App\Http\Requests\AuthenticateUserPinRequest;
use App\Models\Person;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function authenticatePerson(AuthenticateRequest $request)
    {
        ## Kettős validáció bevezetése 
        ## 1. Person >> App-hoz, weboldalhoz
        ## 2. Autó nyitáshoz / nyitásakor az újjlenyomat helyett.

        $credentials = $request->validated();
        $person = Person::where('email', $credentials['email'])->first();
        if ($person) {
            if (Hash::check($credentials['password'], $person->person_password)) {
                $user = User::where('person_id', $person->id)->first();

                if ($user) {
                    $token = $user->createToken("person_auth");
                    return response()->json([
                        "data" => [
                            "user" => $user->user_name,
                            "token" => $token->plainTextToken,
                        ]
                    ]);
                }
            }
        }
        return response()->json([
            "data" => [
                "message" => "Sikertelen belépés"
            ]
        ], 401);
    }
    public function authenticateUserPIN(AuthenticateUserPinRequest $request)
    {
        $credentials = $request->validate([
            'user_name' => ["required", "string", "between:8,45", "exists:users,user_name"],
            'pin' => ['required', 'string', 'size:4', 'exists:users,pin']
        ]);
        $user = User::where('user_name', $credentials['user_name'])->first();

        if ($user && $user->pin === $credentials['pin']) {
            return response()->json([
                "message" => "PIN hitelesítve. Autó nyitása folyamatban..."
            ]);
        }
        return response()->json([
            "error" => "A PIN Érvénytelen. Hozzáférés megtagadva!"
        ], 401);
    }
}

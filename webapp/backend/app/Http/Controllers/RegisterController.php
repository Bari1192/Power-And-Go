<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function store(RegisterUserRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = User::create([
            'szemely_id' => $data['szemely_id'],
            'felh_nev' => $data['felh_nev'],
            'password' => Hash::make($data['password']),
            'jelszo_2_4' => "13", // Ellenőrizd, hogy ez az érték itt van-e
            'felh_egyenleg' => $data['felh_egyenleg'] ?? 0,
            'elofiz_id' => $data['elofiz_id'] ?? 1,
        ]);
    
        //$data["password"] = Hash::make($data["password"]);
        return response()->json([
            "data" => [
                "message" => "A(z) $user->felh_nev sikeresen regisztrált.",
            ]
        ]);
    }
}

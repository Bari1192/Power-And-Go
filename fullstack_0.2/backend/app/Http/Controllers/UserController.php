<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\Felhasznalo;
use GuzzleHttp\Promise\Create;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserController extends Controller
{
    public function index(): JsonResource
    {
        $users = Felhasznalo::with(['szemely','elofizetes'])->get();
        return UserResource::collection($users);
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $user = Felhasznalo::Create($data);
        return new UserResource($user);
    }

    public function show(Felhasznalo $user):JsonResource
    {
        return new UserResource($user);
    }

    public function update(Request $request, Felhasznalo $user)
    {
        $data=$request->validated();
        $user->update($data);
        return new UserResource($user);
    }

    public function destroy(Felhasznalo $user)
    {
        return ($user->delete()) ? response()->noContent() : abort(500);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Resources\Json\JsonResource;

class UserController extends Controller
{
    public function index(): JsonResource
    {
        $users = User::all();
        return UserResource::collection($users);
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $user = User::Create($data);
        return new UserResource($user);
    }

    public function show(User $user): JsonResource
    {
        $user = User::with(['person', 'cars'])->findOrFail($user->id); // Betölti a `cars` és `person` relációkat
        return new UserResource($user);
    }
    
    public function update(StoreUserRequest $request, User $user)
    {
        $data = $request->validated();
        $user->update($data);
        return new UserResource($user);
    }

    public function destroy(User $user)
    {
        return ($user->delete()) ? response()->noContent() : abort(500);
    }
}

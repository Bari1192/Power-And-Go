<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserWithRentalResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(): JsonResource
    {
        $users = User::with('person')->get();
        return UserResource::collection($users);
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $data['pin'] = Hash::make($data['pin']);
        $user = User::Create($data);
        return new UserResource($user);
    }

    public function show(User $user): JsonResource
    {
        $user->load(['cars.fleet']);
        return new UserWithRentalResource($user);
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

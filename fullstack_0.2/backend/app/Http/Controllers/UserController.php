<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserController extends Controller
{
    public function index(): JsonResource
    {
        $users = User::with(['szemely','elofizetes'])->get();
        return UserResource::collection($users);
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $user = User::Create($data);
        return new UserResource($user);
    }

    public function show(User $user):JsonResource
    {
        return new UserResource($user);
    }

    public function update(Request $request, User $user)
    {
        $data=$request->validated();
        $user->update($data);
        return new UserResource($user);
    }

    public function destroy(User $user)
    {
        return ($user->delete()) ? response()->noContent() : abort(500);
    }
}

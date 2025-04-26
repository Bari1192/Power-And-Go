<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserWithRentalResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(): JsonResource
    {
        Gate::authorize("viewAny", User::class);
        $users = User::with('person')->get();
        return UserResource::collection($users);
    }

    public function store(StoreUserRequest $request)
    {
        Gate::allows("create", User::class);
        $data = $request->validated();
        $data['pin'] = Hash::make($data['pin']);
        $user = User::Create($data);
        return new UserResource($user);
    }

    public function show(User $user): JsonResource
    {
        $user->load(['cars.fleet', 'person','subscription.prices']);
        Gate::authorize("view", $user);
        return new UserResource($user);
        // return new UserWithRentalResource($user);
    }

    public function update(StoreUserRequest $request, User $user)
    {
        Gate::authorize("update", User::class);
        $data = $request->validated();
        $user->update($data);
        return new UserResource($user);
    }

    public function destroy(User $user)
    {
        Gate::authorize("delete", $user);
        return ($user->delete()) ? response()->noContent() : abort(500);
    }
}

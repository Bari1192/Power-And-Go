<?php

namespace App\Policies;

use App\Models\Person;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy extends BasePolicy
{
    public function viewAny(User $user): Response
    {
        // csak az láthatja/kérheti le a user-eket,
        // aki Admin VAGY Developer.
        return $this->isAdmin($user) || $this->isDeveloper($user)
            ? Response::allow()
            : Response::deny("Csak adminisztrátorok és fejlesztők tekinthetik meg az összes felhasználót.");
    }

    public function view(User $user): Response
    {
        return $this->isOwnProfile($user) || $this->isAdmin($user) || $this->isDeveloper($user)
            ? Response::allow()
            : Response::deny("Csak a saját profilodat vagy adminként/fejlesztőként más profilját tekintheted meg.");
    }

    public function create(): bool
    {
        return true;
    }

    public function update(User $user): Response
    {
        return $this->isOwnProfile($user) || $this->isAdmin($user) || $this->isDeveloper($user) ?
            Response::allow() : Response::deny("Only Owners or Admins/Developers can modify this data.");
    }

    public function delete(User $user): Response
    {
        return $this->isAdmin($user) || $this->isOwnProfile($user) || $this->isDeveloper($user) ?
            Response::allow() : Response::deny("Only Admins & Developers can modify this data.");
    }

    public function restore(User $user): Response
    {
        return $this->isDeveloper($user)
            ? Response::allow()
            : Response::deny("Csak fejlesztők állíthatnak vissza törölt felhasználókat!");
    }
    public function forceDelete(User $user): Response
    {
        return $this->isDeveloper($user)
            ? Response::allow()
            : Response::deny("Csak fejlesztők állíthatnak vissza törölt felhasználókat!");
    }
}

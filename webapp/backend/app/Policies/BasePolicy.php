<?php

namespace App\Policies;

use App\Models\Person;
use App\Models\User;

class BasePolicy
{
    public function __construct()
    {
        //
    }
    protected function isAdmin(User $user)
    {
        return "admin" == $user->role;
    }
    protected function isDeveloper(User $user)
    {
        return "developer" == $user->role;
    }
    protected function isOwnProfile(User $user)
    {
        return $user->person_id == $user->person->id;
    }
}

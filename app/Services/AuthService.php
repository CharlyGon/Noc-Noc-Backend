<?php

namespace App\Services;

use App\Models\User;

class AuthService
{
    /**
     * Check if the user is an admin.
     *
     * @param User $user
     * @return bool
     */
    public function isAdmin(User $user)
    {
        return $user->role === 'admin';
    }
}

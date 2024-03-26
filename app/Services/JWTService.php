<?php

namespace App\Services;

use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;

class JWTService
{
    /**
     * Generate a JWT token for the user.
     *
     * @param User $user
     * @return string
     */
    public function generateToken(User $user)
    {
        try {
            $expirationTimeInMinutes = 1440; // 24 hours = 24 * 60 minutes
            $options = ['expires_in' => $expirationTimeInMinutes];
            $customClaims = [
                'role' => $user->role,
                'user_id' => $user->id
            ];

            return JWTAuth::claims($customClaims)->fromUser($user, $options);
        } catch (\Exception $e) {
            throw new \Exception("Error al generar el token: " . $e->getMessage());
        }
    }

    /**
     * Verify the JWT token.
     *
     * @param string $token
     * @return User
     */
    public function verifyToken($token)
    {
        return JWTAuth::setToken($token)->authenticate();
    }
}

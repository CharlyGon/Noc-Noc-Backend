<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class PasswordResetService
{
    /**
     * Create a new password reset token.
     * @param string $email
     * @return string
     */
    public function createResetToken($email)
    {
        try {
            $token = Str::random(60);
            DB::table('password_resets')->updateOrInsert(
                ['email' => $email],
                [
                    'email' => $email,
                    'token' => Hash::make($token),
                    'created_at' => now()
                ]
            );

            return $token;
        } catch (\Exception $e) {
            throw new \Exception('Error creating reset token:' . $e->getMessage());
        }
    }

    /**
     * Verify the password reset token.
     * @param string $email
     * @param string $token
     * @return bool
     */
    public function verifyToken($email, $token)
    {
        try {
            $tokenData = DB::table('password_resets')->where('email', $email)->first();
            if (!$tokenData) {
                return false;
            }

            return Hash::check($token, $tokenData->token);
        } catch (\Exception $e) {
            throw new \Exception('Error verifying token' . $e->getMessage());
        }
    }

    /**
     * Find a user by email.
     * @param string $email
     * @return mixed
     */
    public function findUserByEmail($email)
    {
        try {
            $user = User::where('email', $email)->first();
            if (!$user) {
                return false;
            }

            return $user;
        } catch (\Exception $e) {
            throw new \Exception('Error deleting token: ' . $e->getMessage());
        }
    }

    /**
     * Delete the password reset token.
     * @param string $email
     * @return bool
     */
    public function deleteToken($email)
    {
        try {
            $delete = DB::table('password_resets')->where('email', $email)->delete();
            return $delete > 0;
        } catch (\Exception $e) {
            throw new \Exception('Error finding user by email:' . $e->getMessage());
        }
    }
}

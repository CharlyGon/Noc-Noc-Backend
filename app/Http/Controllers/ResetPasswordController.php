<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Services\PasswordResetService;

class ResetPasswordController extends Controller
{
    protected $passwordResetService;

    public function __construct(PasswordResetService $passwordResetService)
    {
        $this->passwordResetService = $passwordResetService;
    }

    /**
     * Reset the user's password.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        try {
            $user = $this->passwordResetService->findUserByEmail($request->email);

            $this->passwordResetService->verifyToken($request->email, $request->token);

            // Actualizar la contraseña del usuario.
            $user->update(['password' => Hash::make($request->password)]);

            $this->passwordResetService->deleteToken($request->email);

            return response()->json(['message' => 'Your password has been reset successfully.'], 200);
        } catch (\Exception $e) {
            Log::error('Error resetting password: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }
}

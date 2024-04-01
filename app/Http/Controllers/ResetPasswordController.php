<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Services\PasswordResetService;
use Illuminate\Support\Facades\Auth;
use App\Services\UserService;


class ResetPasswordController extends Controller
{
    protected $passwordResetService;
    protected $userService;


    public function __construct(PasswordResetService $passwordResetService, UserService $userService)
    {
        $this->passwordResetService = $passwordResetService;
        $this->userService = $userService;
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

            $this->userService->updateUser([
                'password' => $request->password,
            ], $user);

            $this->passwordResetService->deleteToken($request->email);

            return response()->json(['message' => 'Your password has been reset successfully.'], 200);
        } catch (\Exception $e) {
            Log::error('Error resetting password: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    /**
     * Change the user's password.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|different:current_password',
            'new_password_confirmation' => 'required|string|min:8|same:new_password',
        ]);

        try {
            $user = Auth::user();

            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json(['error' => 'La contraseña actual no coincide'], 401);
            }

            $this->userService->updateUser([
                'password' => $request->new_password,
            ], $user);

            return response()->json(['message' => 'Contraseña actualizada con éxito'],200);
        } catch (\Exception $e) {
            Log::error('Error al cambiar la contraseña: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['error' => 'Error al cambiar la contraseña'], 500);
        }
    }
}

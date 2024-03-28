<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use App\Services\UserService; // Import the missing class
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail; // Import the missing class
use App\Services\PasswordResetService; // Import the missing class

class ForgotPasswordController extends Controller
{

    protected $passwordResetService;
    protected $userService;

    public function __construct(PasswordResetService $passwordResetService, UserService $userService)
    {
        $this->passwordResetService = $passwordResetService;
        $this->userService = $userService;
    }

    /**
     * Send a reset link to the given user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        try {
            $request->validate(['email' => 'required|email']);
            $user = $this->userService->getUserByEmail($request->email);

            if (!$user) {
                return response()->json(['error' => 'No user found with this email address.'], 404);
            }

            // Generar el token y guardar en la base de datos.
            $token = $this->passwordResetService->createResetToken($request->email);
            $resetUrl = URL::to("/reset-password?token=$token&email=" . urlencode($request->email));

            // Construir el URL de restablecimiento con el token.
            $resetUrl = URL::to("/reset-password?token=$token&email=" . urlencode($request->email));

            // Enviar el correo electrónico personalizado.
            Mail::to($request->email)->send(new ResetPasswordMail($resetUrl));

            return response()->json(['message' => 'Password reset link sent successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to send reset link: ' . $e->getMessage()], 500);
        }
    }
}

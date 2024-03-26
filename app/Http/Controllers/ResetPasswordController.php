<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Services\UserService;

class ResetPasswordController extends Controller
{
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
            $userService = new UserService();
            $user = $userService->getUserByEmail($request->email);
            if (!$user) {
                return redirect()->back()->withErrors(['email' => 'Email address not found']);
            }

            $user = $userService->updateUser([
                'password' => Hash::make($request->password),
            ], $user);

            return response()->json(['message' => 'Your password has been reset successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error resetting password'], 500);
        }
    }
}

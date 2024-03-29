<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\JWTService;
use Exception;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;


class AuthController extends Controller
{
    protected $userService;
    protected $jwtService;

    public function __construct(UserService $userService, JWTService $jwtService)
    {
        $this->userService = $userService;
        $this->jwtService = $jwtService;
    }

    /**
     * Register a new user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request)
    {
        try {
            $token = $request->header('Authorization');
            $user = $this->jwtService->verifyToken($token);
            if (!$user) {
                return response()->json(['message' => 'Invalid token'], 401);
            }

            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
            ]);


            $newUser = $this->userService->createUser($validatedData);
            Mail::to($newUser->email)->send(new WelcomeMail($newUser));


            return response()->json([
                'message' => 'User successfully registered',
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error registering user: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Login a user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $token = $this->jwtService->generateToken($user);
                $shouldChangePassword = is_null($user->password_changed_at) ||
                    $user->password_changed_at->diffInDays(now()) > 90;


                return response()->json([
                    'access_token' => $token,
                    'should_change_password' => $shouldChangePassword,
                    'message' => 'Login successful'
                ]);
            }

            return response()->json(['message' => 'The provided credentials do not match our records.'], 401);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error occurred while processing login: ' . $e->getMessage()], 500);
        }
    }
}

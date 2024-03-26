<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\JWTService;
use App\Services\AuthService;
use Exception;

class AdminMiddleware
{
    protected $jwtService;
    protected $authService;

    public function __construct(JWTService $jwtService, AuthService $authService)
    {
        $this->jwtService = $jwtService;
        $this->authService = $authService;
    }

    public function handle($request, Closure $next)
    {
        $token = $request->header('Authorization');

        try {
            $user = $this->jwtService->verifyToken($token);
            if ($this->authService->isAdmin($user)) {
                return $next($request);
            } else {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        } catch (Exception $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
}

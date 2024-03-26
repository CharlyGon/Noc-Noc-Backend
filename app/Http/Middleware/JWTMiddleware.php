<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\JWTService;
use Exception;

class JWTMiddleware
{
    protected $jwtService;

    public function __construct(JWTService $jwtService)
    {
        $this->jwtService = $jwtService;
    }

    public function handle($request, Closure $next)
    {
        $token = $request->header('Authorization');

        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $user = $this->jwtService->verifyToken($token);

            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $request->merge(['authenticated_user' => $user]);

            return $next($request);
        } catch (Exception $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
}

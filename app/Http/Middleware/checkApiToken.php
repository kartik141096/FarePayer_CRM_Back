<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Passport\TokenRepository;

class CheckApiToken
{
    protected $tokenRepository;

    public function __construct(TokenRepository $tokenRepository)
    {
        $this->tokenRepository = $tokenRepository;
    }

    public function handle(Request $request, Closure $next)
    {
        // Get the token from the request
        $token = $request->bearerToken();
        // dd($this->tokenRepository->find($token));
        if (!$token) {
            return response()->json(['error' => 'Token not provided.'], 401);
        }

        // Check if the token exists and is not revoked
        $tokenDetails = $this->tokenRepository->find($token);
        // dd($tokenDetails);
        if (!$tokenDetails) {
            return response()->json(['error' => 'Invalid token.'], 401);
        }

        if ($tokenDetails->revoked) {
            return response()->json(['error' => 'Token has been revoked.'], 401);
        }

        return $next($request);
    }
}

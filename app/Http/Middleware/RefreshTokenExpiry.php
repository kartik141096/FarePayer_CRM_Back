<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Passport\TokenRepository;
use Carbon\Carbon;

class RefreshTokenExpiry
{
    protected $tokenRepository;

    public function __construct(TokenRepository $tokenRepository)
    {
        $this->tokenRepository = $tokenRepository;
    }

    // public function handle(Request $request, Closure $next)
    // {
    //     $tokenId = $request->user()->token()->id;
    //     $token = $this->tokenRepository->find($tokenId);
    //     $token->expires_at = Carbon::now()->addMinutes(60);
    //     $token->save();

    //     return $next($request);
    // }


    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($request->user()) {
            $tokenId = $request->user()->token()->id;
            $token = $this->tokenRepository->find($tokenId);
            
            $token->expires_at = Carbon::now()->addMinutes(10);
            $token->save();
            // if ($token && Carbon::now()->diffInMinutes($token->expires_at) < 10) {
                //     $token->expires_at = Carbon::now()->addMinutes(10);
                //     $token->save();
                // }

        }

        return $response;
    }
}

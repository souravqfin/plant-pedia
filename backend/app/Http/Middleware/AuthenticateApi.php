<?php

namespace App\Http\Middleware;

use App\Http\Responses\ApiResponse;
use App\Models\User;
use App\Services\Auth\JwtService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateApi
{
    public function __construct(private readonly JwtService $jwt) {}

    public function handle(Request $request, Closure $next, string $audience = 'web'): Response
    {
        $token = $request->bearerToken();

        if (! $token) {
            return ApiResponse::error('UNAUTHENTICATED', 'Authentication required.', 401);
        }

        try {
            $claims = $this->jwt->decode($token, $audience);
        } catch (\RuntimeException $e) {
            $code = $e->getMessage() === 'TOKEN_EXPIRED' ? 'TOKEN_EXPIRED' : 'UNAUTHENTICATED';

            return ApiResponse::error($code, 'The access token is invalid or expired.', 401);
        }

        $user = User::find($claims->sub);

        if (! $user || $user->status !== 'active') {
            return ApiResponse::error('ACCOUNT_DISABLED', 'This account is not active.', 403);
        }

        $request->setUserResolver(fn () => $user);
        $request->attributes->set('jwt_claims', $claims);

        return $next($request);
    }
}

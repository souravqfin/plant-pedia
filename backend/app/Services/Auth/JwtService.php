<?php

namespace App\Services\Auth;

use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Str;
use RuntimeException;

class JwtService
{
    public function issue(User $user, string $audience = 'web'): array
    {
        $now = time();
        $ttl = $audience === 'admin'
            ? config('api.jwt.admin_access_ttl')
            : config('api.jwt.access_ttl');

        $payload = [
            'iss' => config('api.jwt.issuer'),
            'sub' => (string) $user->getKey(),
            'aud' => $audience,
            'iat' => $now,
            'nbf' => $now,
            'exp' => $now + $ttl,
            'jti' => (string) Str::uuid(),
            'roles' => $user->roleCodes(),
        ];

        return [
            'token' => JWT::encode($payload, $this->secret(), 'HS256'),
            'expires_in' => $ttl,
            'expires_at' => $now + $ttl,
        ];
    }

    public function decode(string $token, string $audience = 'web'): object
    {
        try {
            $claims = JWT::decode($token, new Key($this->secret(), 'HS256'));
        } catch (\Throwable $e) {
            throw new RuntimeException('INVALID_TOKEN', 0, $e);
        }

        if (($claims->iss ?? null) !== config('api.jwt.issuer')) {
            throw new RuntimeException('INVALID_ISSUER');
        }

        if (($claims->aud ?? null) !== $audience) {
            throw new RuntimeException('INVALID_AUDIENCE');
        }

        if (($claims->exp ?? 0) < time()) {
            throw new RuntimeException('TOKEN_EXPIRED');
        }

        return $claims;
    }

    public function secret(): string
    {
        $secret = (string) config('api.jwt.secret');

        if ($secret === '') {
            throw new RuntimeException('JWT secret is not configured.');
        }

        return $secret;
    }
}

<?php

namespace App\Services\Auth;

use App\Models\RefreshToken;
use App\Models\User;
use App\Models\UserSession;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RefreshTokenService
{
    public function issue(User $user, UserSession $session, string $audience = 'web'): array
    {
        $plain = Str::random(64);

        RefreshToken::create([
            'user_id' => $user->getKey(),
            'user_session_id' => $session->getKey(),
            'family_id' => (string) Str::uuid(),
            'token_hash' => hash('sha256', $plain),
            'audience' => $audience,
            'expires_at' => now()->addSeconds(config('api.refresh.ttl')),
        ]);

        return ['plain' => $plain, 'expires_at' => now()->addSeconds(config('api.refresh.ttl'))];
    }

    public function rotate(string $plain, string $audience = 'web'): array
    {
        return DB::transaction(function () use ($plain, $audience) {
            $record = RefreshToken::query()
                ->where('token_hash', hash('sha256', $plain))
                ->lockForUpdate()
                ->first();

            if (! $record) {
                throw new \RuntimeException('INVALID_REFRESH_TOKEN');
            }

            if ($record->revoked_at !== null) {
                RefreshToken::where('family_id', $record->family_id)
                    ->whereNull('revoked_at')
                    ->update(['revoked_at' => now()]);

                throw new \RuntimeException('REFRESH_TOKEN_REUSED');
            }

            if ($record->expires_at->isPast()) {
                throw new \RuntimeException('REFRESH_TOKEN_EXPIRED');
            }

            if ($record->audience !== $audience) {
                throw new \RuntimeException('INVALID_REFRESH_AUDIENCE');
            }

            $session = UserSession::find($record->user_session_id);

            if (! $session || $session->revoked_at !== null) {
                throw new \RuntimeException('SESSION_REVOKED');
            }

            $user = $record->user;

            if (! $user || $user->status !== 'active') {
                throw new \RuntimeException('ACCOUNT_DISABLED');
            }

            $plainNew = Str::random(64);
            $new = RefreshToken::create([
                'user_id' => $user->getKey(),
                'user_session_id' => $session->getKey(),
                'family_id' => $record->family_id,
                'token_hash' => hash('sha256', $plainNew),
                'audience' => $audience,
                'expires_at' => now()->addSeconds(config('api.refresh.ttl')),
            ]);

            $record->update(['revoked_at' => now(), 'replaced_by' => $new->getKey()]);
            $session->update(['last_active_at' => now()]);

            return [
                'user' => $user,
                'session' => $session,
                'plain' => $plainNew,
                'expires_at' => $new->expires_at,
            ];
        });
    }

    public function revoke(string $plain): void
    {
        RefreshToken::where('token_hash', hash('sha256', $plain))
            ->whereNull('revoked_at')
            ->update(['revoked_at' => now()]);
    }

    public function revokeAllForUser(User $user): void
    {
        RefreshToken::where('user_id', $user->getKey())->whereNull('revoked_at')->update(['revoked_at' => now()]);
        UserSession::where('user_id', $user->getKey())->whereNull('revoked_at')->update(['revoked_at' => now()]);
    }

    public function revokeSession(User $user, int $sessionId): void
    {
        $session = UserSession::where('user_id', $user->getKey())->findOrFail($sessionId);
        $session->update(['revoked_at' => now()]);
        RefreshToken::where('user_session_id', $session->getKey())->whereNull('revoked_at')->update(['revoked_at' => now()]);
    }
}

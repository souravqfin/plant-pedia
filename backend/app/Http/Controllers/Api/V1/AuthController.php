<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Models\Role;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserSession;
use App\Services\Auth\JwtService;
use App\Services\Auth\RefreshTokenService;
use App\Services\Auth\TotpService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rules\Password as PasswordRule;

class AuthController extends Controller
{
    public function __construct(
        private readonly JwtService $jwt,
        private readonly RefreshTokenService $refresh,
        private readonly TotpService $totp,
    ) {}

    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:32', 'unique:users,phone'],
            'password' => ['required', 'confirmed', PasswordRule::min(8)->letters()->numbers()],
            'locale' => ['nullable', 'in:bn,en'],
        ]);

        $user = DB::transaction(function () use ($data, $request) {
            $user = User::create([
                'name' => $data['name'],
                'email' => strtolower($data['email']),
                'phone' => $data['phone'] ?? null,
                'password' => $data['password'],
                'locale' => $data['locale'] ?? config('api.default_locale'),
            ]);

            $user->profile()->create([
                'display_name' => $data['name'],
                'country_code' => config('api.default_country'),
            ]);

            $role = Role::where('name', 'user')->first();

            if ($role) {
                $user->roles()->attach($role->id);
            }

            return $user;
        });

        $session = $this->createSession($user, $request);
        $tokens = $this->jwt->issue($user, 'web');
        $refresh = $this->refresh->issue($user, $session, 'web');

        $response = ApiResponse::data([
            'access_token' => $tokens['token'],
            'token_type' => 'Bearer',
            'expires_in' => $tokens['expires_in'],
            'user' => $this->userPayload($user),
        ], ['verification_required' => true], 201);

        return $this->withAuthCookies($response, $refresh['plain']);
    }

    public function login(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'audience' => ['nullable', 'in:web,admin'],
            'otp' => ['nullable', 'string'],
        ]);

        $user = User::where('email', strtolower($data['email']))->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return ApiResponse::error('INVALID_CREDENTIALS', 'The email or password is incorrect.', 401);
        }

        if ($user->status !== 'active') {
            return ApiResponse::error('ACCOUNT_DISABLED', 'This account is not active.', 403);
        }

        $audience = $data['audience'] ?? 'web';

        if ($audience === 'admin' && ! $user->isAdmin()) {
            return ApiResponse::error('FORBIDDEN', 'Administrator access required.', 403);
        }

        if ($audience === 'admin' && $user->mfa_enabled_at !== null) {
            if (empty($data['otp']) || ! $this->totp->verify((string) $user->mfa_secret, $data['otp'])) {
                return ApiResponse::error('MFA_REQUIRED', 'A valid one-time code is required.', 401);
            }
        }

        $session = $this->createSession($user, $request);
        $user->forceFill(['last_login_at' => now()])->save();

        $tokens = $this->jwt->issue($user, $audience);
        $refresh = $this->refresh->issue($user, $session, $audience);

        $response = ApiResponse::data([
            'access_token' => $tokens['token'],
            'token_type' => 'Bearer',
            'expires_in' => $tokens['expires_in'],
            'user' => $this->userPayload($user),
        ]);

        return $this->withAuthCookies($response, $refresh['plain']);
    }

    public function refresh(Request $request): JsonResponse
    {
        $csrfError = $this->checkCsrf($request);

        if ($csrfError) {
            return $csrfError;
        }

        $plain = $request->cookie(config('api.refresh.cookie'));

        if (! $plain) {
            return ApiResponse::error('UNAUTHENTICATED', 'No refresh token present.', 401);
        }

        try {
            $result = $this->refresh->rotate($plain);
        } catch (\RuntimeException $e) {
            return ApiResponse::error($e->getMessage(), 'The session could not be refreshed.', 401);
        }

        $tokens = $this->jwt->issue($result['user'], 'web');

        $response = ApiResponse::data([
            'access_token' => $tokens['token'],
            'token_type' => 'Bearer',
            'expires_in' => $tokens['expires_in'],
            'user' => $this->userPayload($result['user']),
        ]);

        return $this->withAuthCookies($response, $result['plain']);
    }

    public function logout(Request $request): JsonResponse
    {
        $plain = $request->cookie(config('api.refresh.cookie'));

        if ($plain) {
            $this->refresh->revoke($plain);
        }

        $response = ApiResponse::data(['message' => 'Logged out.']);

        return $this->withoutAuthCookies($response);
    }

    public function logoutAll(Request $request): JsonResponse
    {
        $this->refresh->revokeAllForUser($request->user());

        $response = ApiResponse::data(['message' => 'All sessions revoked.']);

        return $this->withoutAuthCookies($response);
    }

    public function me(Request $request): JsonResponse
    {
        return ApiResponse::data($this->userPayload($request->user()));
    }

    public function sessions(Request $request): JsonResponse
    {
        $sessions = UserSession::where('user_id', $request->user()->id)
            ->whereNull('revoked_at')
            ->orderByDesc('last_active_at')
            ->get()
            ->map(fn (UserSession $s) => [
                'id' => $s->id,
                'device_name' => $s->device_name,
                'platform' => $s->platform,
                'ip' => $s->ip,
                'last_active_at' => $s->last_active_at?->toIso8601String(),
                'created_at' => $s->created_at?->toIso8601String(),
            ]);

        return ApiResponse::data($sessions);
    }

    public function revokeSession(Request $request, int $id): JsonResponse
    {
        $this->refresh->revokeSession($request->user(), $id);

        return ApiResponse::noContent();
    }

    public function verifyEmail(Request $request): JsonResponse
    {
        $user = User::findOrFail($request->integer('id'));

        if (! hash_equals(sha1($user->email), (string) $request->query('hash'))) {
            return ApiResponse::error('INVALID_SIGNATURE', 'This verification link is invalid.', 403);
        }

        if ($user->email_verified_at === null) {
            $user->forceFill(['email_verified_at' => now()])->save();
        }

        return ApiResponse::data(['message' => 'Email verified.']);
    }

    public function forgotPassword(Request $request): JsonResponse
    {
        $data = $request->validate(['email' => ['required', 'email']]);
        $user = User::where('email', strtolower($data['email']))->first();

        if ($user) {
            $token = Password::createToken($user);
            $url = rtrim(config('app.frontend_url', 'http://localhost:5173'), '/')
                .'/reset-password?token='.$token.'&email='.urlencode($user->email);

            try {
                Mail::raw(
                    "Reset your Plant Pedia password:\n\n{$url}\n\nThis link expires in 60 minutes.",
                    fn ($message) => $message->to($user->email)->subject('Reset your Plant Pedia password')
                );
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return ApiResponse::data(['message' => 'If the account exists, a reset link has been sent.']);
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'token' => ['required', 'string'],
            'password' => ['required', 'confirmed', PasswordRule::min(8)->letters()->numbers()],
        ]);

        $status = Password::reset(
            $data,
            function (User $user, string $password) {
                $user->forceFill(['password' => $password])->save();
                $this->refresh->revokeAllForUser($user);
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            return ApiResponse::error('INVALID_RESET_TOKEN', __($status), 422);
        }

        return ApiResponse::data(['message' => 'Password reset. Please sign in.']);
    }

    public function mfaSetup(Request $request): JsonResponse
    {
        $user = $request->user();
        $secret = $this->totp->generateSecret();

        $user->forceFill(['mfa_secret' => $secret, 'mfa_enabled_at' => null])->save();

        return ApiResponse::data([
            'secret' => $secret,
            'otpauth_uri' => $this->totp->uri($secret, $user->email),
        ]);
    }

    public function mfaEnable(Request $request): JsonResponse
    {
        $data = $request->validate(['code' => ['required', 'string']]);
        $user = $request->user();

        if (! $user->mfa_secret || ! $this->totp->verify($user->mfa_secret, $data['code'])) {
            return ApiResponse::error('INVALID_MFA_CODE', 'The one-time code is invalid.', 422);
        }

        $user->forceFill(['mfa_enabled_at' => now()])->save();

        return ApiResponse::data(['message' => 'MFA enabled.']);
    }

    public function mfaDisable(Request $request): JsonResponse
    {
        $data = $request->validate(['password' => ['required', 'string']]);
        $user = $request->user();

        if (! Hash::check($data['password'], $user->password)) {
            return ApiResponse::error('INVALID_PASSWORD', 'The password is incorrect.', 422);
        }

        $user->forceFill(['mfa_secret' => null, 'mfa_enabled_at' => null])->save();

        return ApiResponse::data(['message' => 'MFA disabled.']);
    }

    private function createSession(User $user, Request $request): UserSession
    {
        return UserSession::create([
            'user_id' => $user->id,
            'device_name' => substr((string) $request->userAgent(), 0, 120) ?: 'Unknown device',
            'platform' => $request->header('X-Client-Platform', 'web'),
            'ip' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 500),
            'last_active_at' => now(),
        ]);
    }

    private function userPayload(User $user): array
    {
        $user->loadMissing('profile', 'roles');

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'locale' => $user->locale,
            'status' => $user->status,
            'email_verified_at' => $user->email_verified_at?->toIso8601String(),
            'mfa_enabled' => $user->mfa_enabled_at !== null,
            'roles' => $user->roleCodes(),
            'permissions' => $user->permissionCodes()->all(),
            'profile' => $user->profile?->only(['display_name', 'bio', 'avatar_url', 'country_code', 'expertise']),
        ];
    }

    private function withAuthCookies(JsonResponse $response, string $refreshPlain): JsonResponse
    {
        $minutes = (int) config('api.refresh.ttl') / 60;
        $csrf = bin2hex(random_bytes(20));

        $response->headers->setCookie(cookie(
            config('api.refresh.cookie'),
            $refreshPlain,
            $minutes,
            config('api.refresh.path'),
            config('api.refresh.domain'),
            (bool) config('api.refresh.secure'),
            true,
            false,
            config('api.refresh.same_site'),
        ));

        $response->headers->setCookie(cookie(
            'pp_csrf',
            $csrf,
            $minutes,
            '/',
            config('api.refresh.domain'),
            (bool) config('api.refresh.secure'),
            false,
            false,
            config('api.refresh.same_site'),
        ));

        return $response;
    }

    private function withoutAuthCookies(JsonResponse $response): JsonResponse
    {
        foreach ([config('api.refresh.cookie'), 'pp_csrf'] as $name) {
            $response->headers->clearCookie($name, config('api.refresh.path'), config('api.refresh.domain'));
        }

        return $response;
    }

    private function checkCsrf(Request $request): ?JsonResponse
    {
        $cookie = $request->cookie('pp_csrf');

        if ($cookie === null) {
            return null;
        }

        $header = $request->header('X-CSRF-TOKEN');

        if (! $header || ! hash_equals($cookie, $header)) {
            return ApiResponse::error('CSRF_MISMATCH', 'The CSRF token is missing or invalid.', 419);
        }

        return null;
    }
}

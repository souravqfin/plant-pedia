<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable;

    protected $fillable = [
        'name', 'email', 'phone', 'password', 'status', 'locale',
        'mfa_secret', 'mfa_enabled_at', 'last_login_at',
    ];

    protected $hidden = ['password', 'remember_token', 'mfa_secret'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'mfa_enabled_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(UserSession::class);
    }

    public function refreshTokens(): HasMany
    {
        return $this->hasMany(RefreshToken::class);
    }

    public function favorites(): BelongsToMany
    {
        return $this->belongsToMany(Plant::class, 'favorites')->withTimestamps();
    }

    public function collections(): HasMany
    {
        return $this->hasMany(UserCollection::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(ContentSubmission::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function permissionCodes(): Collection
    {
        return $this->getAllPermissions()->pluck('name')->unique()->values();
    }

    public function hasPermission(string $permission): bool
    {
        try {
            return $this->hasPermissionTo($permission);
        } catch (\Throwable) {
            return false;
        }
    }

    public function isAdmin(): bool
    {
        return $this->hasAnyRole(['super_admin', 'admin', 'editor', 'moderator', 'contributor']);
    }

    public function roleCodes(): array
    {
        return $this->getRoleNames()->all();
    }
}

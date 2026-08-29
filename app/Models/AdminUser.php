<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class AdminUser extends Authenticatable implements JWTSubject
{
    use SoftDeletes;
    protected $fillable = ["name", "email", "password", "is_active"];
    protected $hidden = ["password"];
    protected function casts(): array
    {
        return ["password" => "hashed", "is_active" => "boolean"];
    }
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,
            "admin_user_roles",
        )->withTimestamps();
    }
    public function hasPermission(string $code): bool
    {
        return $this->roles()
            ->where("roles.is_active", true)
            ->whereHas(
                "permissions",
                fn($q) => $q->where("code", $code)->where("is_active", true),
            )
            ->exists();
    }
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims(): array
    {
        return [];
    }
}

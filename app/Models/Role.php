<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
class Role extends Model
{
    protected $fillable = ["name", "code", "is_active"];
    protected function casts(): array
    {
        return ["is_active" => "boolean"];
    }
    public function adminUsers(): BelongsToMany
    {
        return $this->belongsToMany(
            AdminUser::class,
            "admin_user_roles",
        )->withTimestamps();
    }
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            Permission::class,
            "role_permissions",
        )->withTimestamps();
    }
}

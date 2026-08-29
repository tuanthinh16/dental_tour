<?php
namespace App\Services;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
class AuthService
{
    public function attempt(array $credentials): string|false
    {
        $user = \App\Models\AdminUser::where(
            "email",
            $credentials["email"],
        )->first();
        if (
            !$user ||
            !$user->is_active ||
            !($token = Auth::guard("admin")->attempt($credentials))
        ) {
            Log::warning("Admin login failed", [
                "email" => $credentials["email"],
            ]);
            return false;
        }
        Log::info("Admin login succeeded", ["admin_user_id" => $user->id]);
        return $token;
    }
}

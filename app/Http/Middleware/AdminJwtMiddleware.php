<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
class AdminJwtMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken() ?: $request->cookie("admin_token");
        if (!$token) {
            return $this->deny($request);
        }
        try {
            $user = auth("admin")->setToken($token)->authenticate();
        } catch (JWTException) {
            return $this->deny($request);
        }
        if (!$user || !$user->is_active) {
            return $this->deny($request);
        }
        auth("admin")->setUser($user);
        return $next($request);
    }
    private function deny(Request $request): Response
    {
        return $request->expectsJson()
            ? response()->json(["message" => "Unauthenticated."], 401)
            : redirect()->route("admin.login");
    }
}

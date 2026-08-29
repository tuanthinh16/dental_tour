<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
class AdminJwtRefreshMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken() ?: $request->cookie("admin_token");
        if (!$token) {
            return response()->json(["message" => "Unauthenticated."], 401);
        }
        auth("admin")->setToken($token);
        return $next($request);
    }
}

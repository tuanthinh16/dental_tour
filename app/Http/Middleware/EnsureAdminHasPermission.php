<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
class EnsureAdminHasPermission
{
    public function handle(
        Request $request,
        Closure $next,
        string $permission,
    ): Response {
        abort_unless(auth("admin")->user()?->hasPermission($permission), 403);
        return $next($request);
    }
}

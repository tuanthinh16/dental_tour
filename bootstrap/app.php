<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . "/../routes/web.php",
        api: __DIR__ . "/../routes/api.php",
        commands: __DIR__ . "/../routes/console.php",
        health: "/up",
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            "admin.jwt" => \App\Http\Middleware\AdminJwtMiddleware::class,
            "admin.jwt.refresh" =>
                \App\Http\Middleware\AdminJwtRefreshMiddleware::class,
            "permission" =>
                \App\Http\Middleware\EnsureAdminHasPermission::class,
        ]);
        $middleware->trustProxies(at: '*');
        $middleware->encryptCookies(except: ["admin_token"]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn(Request $request) => $request->is("api/*") ||
                $request->expectsJson(),
        );
        $exceptions->report(function (\Throwable $exception): void {
            \Illuminate\Support\Facades\Log::error("Unhandled exception", [
                "exception" => $exception::class,
                "file" => $exception->getFile(),
                "line" => $exception->getLine(),
            ]);
        });
    })
    ->create();

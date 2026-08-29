<?php

use App\Http\Controllers\Admin\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix("admin/auth")->group(function (): void {
    Route::post("login", [AuthController::class, "login"])->middleware(
        "throttle:5,1",
    );
    Route::post("refresh", [AuthController::class, "refresh"])->middleware(
        "admin.jwt.refresh",
    );
    Route::middleware("admin.jwt")->group(function (): void {
        Route::post("logout", [AuthController::class, "logout"]);
        Route::get("me", [AuthController::class, "me"]);
    });
});

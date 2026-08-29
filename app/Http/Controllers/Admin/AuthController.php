<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
class AuthController extends Controller
{
    public function __construct(private AuthService $service) {}
    public function showLogin()
    {
        return view("admin.auth.login");
    }
    public function login(LoginRequest $request): JsonResponse
    {
        $token = $this->service->attempt($request->validated());
        if (!$token) {
            return response()->json(
                ["message" => "Email hoặc mật khẩu không đúng."],
                401,
            );
        }
        return $this->respond($token)->cookie(
            "admin_token",
            $token,
            (int) config("jwt.ttl"),
            "/",
            null,
            request()->isSecure(),
            true,
            false,
            "Lax",
        );
    }
    public function me(): JsonResponse
    {
        return response()->json(
            auth("admin")->user()->load("roles.permissions"),
        );
    }
    public function logout(): JsonResponse
    {
        auth("admin")->logout();
        return response()
            ->json(["message" => "Đã đăng xuất."])
            ->withoutCookie("admin_token");
    }
    public function refresh(): JsonResponse
    {
        try {
            $token = auth("admin")->refresh();
        } catch (\Tymon\JWTAuth\Exceptions\JWTException) {
            return response()
                ->json(
                    [
                        "message" =>
                            "Token không hợp lệ hoặc đã hết thời gian refresh.",
                    ],
                    401,
                )
                ->withoutCookie("admin_token");
        }
        return $this->respond($token)->cookie(
            "admin_token",
            $token,
            (int) config("jwt.ttl"),
            "/",
            null,
            request()->isSecure(),
            true,
            false,
            "Lax",
        );
    }
    private function respond(string $token): JsonResponse
    {
        return response()->json([
            "access_token" => $token,
            "token_type" => "bearer",
            "expires_in" => auth("admin")->factory()->getTTL() * 60,
        ]);
    }
}

<?php
namespace Tests\Feature;
use Database\Seeders\InitialAdminSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class AdminAuthTest extends TestCase
{
    use RefreshDatabase;
    public function test_admin_can_login_and_read_profile(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $this->seed(InitialAdminSeeder::class);
        $response = $this->postJson("/api/admin/auth/login", [
            "email" => "admin@example.com",
            "password" => "123",
        ])
            ->assertOk()
            ->assertJsonStructure(["access_token", "token_type", "expires_in"]);
        $this->withToken($response->json("access_token"))
            ->getJson("/api/admin/auth/me")
            ->assertOk()
            ->assertJsonPath("email", "admin@example.com");
    }
    public function test_inactive_or_invalid_admin_cannot_login(): void
    {
        $this->postJson("/api/admin/auth/login", [
            "email" => "admin@example.com",
            "password" => "wrong",
        ])->assertUnauthorized();
    }
}

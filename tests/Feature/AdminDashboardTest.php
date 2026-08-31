<?php

namespace Tests\Feature;

use App\Models\ConsultationRequest;
use Database\Seeders\InitialAdminSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_shows_operational_data_without_the_previous_hero(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $this->seed(InitialAdminSeeder::class);
        ConsultationRequest::create([
            'full_name' => 'Nguyễn An',
            'email' => 'an@example.com',
            'phone' => '0900000000',
            'status' => 'new',
        ]);

        $token = $this->postJson('/api/admin/auth/login', [
            'email' => 'admin@example.com',
            'password' => '123',
        ])->json('access_token');

        $this->withToken($token)
            ->get('/admin')
            ->assertOk()
            ->assertSee('Tổng quan vận hành')
            ->assertSee('Yêu cầu tư vấn mới nhất')
            ->assertSee('Nguyễn An')
            ->assertSee('Sản phẩm đi kèm')
            ->assertDontSee('Trung tâm nội dung');
    }
}

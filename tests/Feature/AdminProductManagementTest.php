<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Tour;
use Database\Seeders\InitialAdminSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminProductManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_manage_add_on_products_and_removes_deleted_product_from_tours(): void
    {
        $token = $this->adminToken();

        $this->withToken($token)
            ->get('/admin/products')
            ->assertOk()
            ->assertSee('Sản phẩm đi kèm');

        $this->withToken($token)
            ->post('/admin/products', [
                'name' => 'Xe đón sân bay riêng',
                'slug' => 'xe-don-san-bay-rieng',
                'short_description' => 'Đón tại sân bay Phú Quốc.',
                'description' => 'Xe riêng dành cho hành trình linh hoạt.',
                'base_price' => 450000,
                'original_price' => 500000,
                'currency' => 'VND',
                'sort_order' => 2,
                'is_active' => '1',
            ])
            ->assertRedirect(route('admin.products.index'));

        $product = Product::where('slug', 'xe-don-san-bay-rieng')->firstOrFail();
        $this->assertSame('addon', $product->product_type);
        $this->assertSame('450000.00', $product->daily_price);

        $tour = Tour::create([
            'product_code' => 'TOUR_PRODUCT_MANAGER_TEST',
            'name' => 'Tour có sản phẩm đi kèm',
            'slug' => 'tour-co-san-pham-di-kem',
            'daily_price' => 0,
            'currency' => 'VND',
            'included_product_ids' => (string) $product->id,
        ]);

        $this->withToken($token)
            ->put('/admin/products/'.$product->id, [
                'name' => 'Xe đón sân bay',
                'slug' => 'xe-don-san-bay',
                'base_price' => 500000,
                'currency' => 'VND',
                'sort_order' => 1,
                'is_active' => '1',
            ])
            ->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Xe đón sân bay',
            'daily_price' => 500000,
        ]);

        $this->withToken($token)
            ->delete('/admin/products/'.$product->id)
            ->assertRedirect();

        $this->assertSoftDeleted('products', ['id' => $product->id]);
        $this->assertSame([], $tour->fresh()->includedProductIdList());
    }

    private function adminToken(): string
    {
        $this->seed(RolePermissionSeeder::class);
        $this->seed(InitialAdminSeeder::class);

        return $this->postJson('/api/admin/auth/login', [
            'email' => 'admin@example.com',
            'password' => '123',
        ])->json('access_token');
    }
}

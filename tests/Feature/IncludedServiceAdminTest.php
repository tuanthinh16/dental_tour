<?php

namespace Tests\Feature;

use App\Models\IncludedService;
use App\Models\Tour;
use Database\Seeders\DemoContentSeeder;
use Database\Seeders\InitialAdminSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IncludedServiceAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_manage_services_and_assign_many_to_a_tour(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $this->seed(InitialAdminSeeder::class);
        $this->seed(DemoContentSeeder::class);

        $login = $this->postJson('/api/admin/auth/login', [
            'email' => 'admin@example.com',
            'password' => '123',
        ])->assertOk();
        $token = $login->json('access_token');

        $this->withToken($token)
            ->post('/admin/included-services', [
                'name' => 'Xe riêng theo lịch trình',
                'description' => 'Xe riêng phục vụ trong các ngày của tour.',
                'sort_order' => 10,
                'is_active' => 1,
            ])
            ->assertRedirect('/admin/included-services');

        $service = IncludedService::where('name', 'Xe riêng theo lịch trình')->firstOrFail();
        $tour = Tour::firstOrFail();
        $secondService = IncludedService::where('name', 'Bữa sáng')->firstOrFail();

        $this->withToken($token)
            ->get('/admin/tours/'.$tour->id.'/edit')
            ->assertOk()
            ->assertSee('data-service-picker', false)
            ->assertSee('Xe riêng theo lịch trình');

        $this->withToken($token)
            ->get('/admin/tours')
            ->assertOk()
            ->assertSee('Thêm Tour')
            ->assertSee('id="create-tours"', false)
            ->assertDontSee('/admin/tours/create', false)
            ->assertSee('Chỉnh landing');

        $this->withToken($token)
            ->get('/admin/included-services')
            ->assertOk()
            ->assertSee('id="create-included-services"', false)
            ->assertDontSee('/admin/included-services/create', false);

        foreach ([
            '/admin/destinations' => 'create-destinations',
            '/admin/pages' => 'create-pages',
        ] as $url => $formId) {
            $this->withToken($token)
                ->get($url)
                ->assertOk()
                ->assertSee('id="'.$formId.'"', false)
                ->assertDontSee($url.'/create', false);
        }

        $this->withToken($token)
            ->put('/admin/tours/'.$tour->id, [
                'destination_id' => $tour->destination_id,
                'name' => $tour->name,
                'slug' => $tour->slug,
                'short_description' => $tour->short_description,
                'description' => $tour->description,
                'duration_days' => $tour->duration_days,
                'duration_nights' => $tour->duration_nights,
                'base_price' => $tour->base_price,
                'original_price' => $tour->original_price,
                'currency' => $tour->currency,
                'badge' => $tour->badge,
                'sort_order' => $tour->sort_order,
                'is_featured' => $tour->is_featured,
                'is_active' => $tour->is_active,
                'service_selection_submitted' => 1,
                'service_ids' => [$service->id, $secondService->id],
            ])
            ->assertRedirect('/admin/tours');

        $this->assertSame(
            [$service->id, $secondService->id],
            $tour->fresh()->categoryIdList(),
        );
        $this->assertCount(2, $tour->fresh()->services);

        $this->withToken($token)
            ->delete('/admin/included-services/'.$service->id)
            ->assertRedirect();

        $this->assertSoftDeleted('categories', ['id' => $service->id]);
        $this->assertNotContains($service->id, $tour->fresh()->categoryIdList());
    }
}

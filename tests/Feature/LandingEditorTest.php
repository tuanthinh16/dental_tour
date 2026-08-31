<?php

namespace Tests\Feature;

use App\Models\Destination;
use App\Models\IncludedService;
use App\Models\Setting;
use App\Models\Tour;
use Database\Seeders\DemoContentSeeder;
use Database\Seeders\InitialAdminSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LandingEditorTest extends TestCase
{
    use RefreshDatabase;

    public function test_visual_editor_uses_the_real_landing_and_keeps_admin_controls_private(): void
    {
        Storage::fake('public');
        $this->seed(RolePermissionSeeder::class);
        $this->seed(InitialAdminSeeder::class);
        $this->seed(DemoContentSeeder::class);

        $login = $this->postJson('/api/admin/auth/login', [
            'email' => 'admin@example.com',
            'password' => '123',
        ])->assertOk();
        $token = $login->json('access_token');

        $this->withToken($token)
            ->get('/admin/landing-editor')
            ->assertOk()
            ->assertSee('Việt Nam rộng mở.')
            ->assertSee('Visual Editor đang bật')
            ->assertSee('Sửa hero')
            ->assertSee('Sửa trực tiếp tour')
            ->assertSee('Sửa điểm đến')
            ->assertSee('Tiền tệ')
            ->assertSee('Dịch vụ đi kèm')
            ->assertSee('data-visual-editor-open="create-tour-inline"', false)
            ->assertSee('data-visual-editor-open="create-destination-inline"', false)
            ->assertSee('data-service-picker', false)
            ->assertSee('data-tour-rail', false)
            ->assertSee('data-tour-card', false)
            ->assertSee('data-tour-rail-next', false)
            ->assertSee('max-h-64 overflow-y-auto', false)
            ->assertSee('data-visual-editor-panel', false)
            ->assertSee('data-destination-sort-list', false)
            ->assertDontSee('<dialog', false);

        $this->get('/')
            ->assertOk()
            ->assertSee('Việt Nam rộng mở.')
            ->assertDontSee('Visual Editor đang bật')
            ->assertDontSee('Sửa hero')
            ->assertDontSee('Sửa trực tiếp tour')
            ->assertDontSee('Sửa điểm đến')
            ->assertSee('data-tour-rail', false)
            ->assertSee('data-tour-rail-next', false)
            ->assertDontSee('create-tour-inline')
            ->assertDontSee('create-destination-inline');

        $this->withToken($token)
            ->put('/admin/landing-editor/hero-image', [
                'eyebrow' => 'Khám phá theo cách của bạn',
                'title_line_1' => 'Việt Nam đầy cảm hứng.',
                'title_before_image' => 'Bạn chọn',
                'title_after_image' => 'nhịp hành trình.',
                'description' => 'Một hành trình được thiết kế riêng cho từng vị khách.',
                'image' => UploadedFile::fake()->image('landing-hero.jpg', 1920, 1080),
            ])
            ->assertRedirect('/admin/landing-editor#landing-hero');

        $heroPath = Setting::where('key', 'landing_hero_image')->value('value');
        $this->assertStringStartsWith('/storage/site/hero/', $heroPath);
        Storage::disk('public')->assertExists(str_replace('/storage/', '', $heroPath));
        $this->assertDatabaseHas('settings', [
            'key' => 'landing_hero_title_line_1',
            'value' => 'Việt Nam đầy cảm hứng.',
        ]);
        $this->get('/')
            ->assertOk()
            ->assertSee($heroPath, false)
            ->assertSee('Việt Nam đầy cảm hứng.');

        $destination = Destination::query()->firstOrFail();
        $this->withToken($token)
            ->put("/admin/landing-editor/destinations/{$destination->id}", [
                'name' => 'Hội An bình yên',
                'short_description' => 'Một nhịp sống chậm giữa miền di sản.',
                'image' => UploadedFile::fake()->image('hoi-an.jpg', 1200, 900),
            ])
            ->assertRedirect('/admin/landing-editor#destinations');

        $destination->refresh();
        $this->assertSame('Hội An bình yên', $destination->name);
        $this->assertSame('Một nhịp sống chậm giữa miền di sản.', $destination->short_description);
        $this->assertNotNull($destination->image);
        $this->assertSame($destination->product_code, $destination->image->product_code);

        $tour = Tour::query()->firstOrFail();
        $selectedService = IncludedService::query()->firstOrFail();
        $this->withToken($token)
            ->put("/admin/landing-editor/tours/{$tour->id}", [
                'name' => 'Hành trình di sản mới',
                'destination_id' => $tour->destination_id,
                'short_description' => 'Trải nghiệm tinh gọn được cập nhật ngay trên card.',
                'duration_days' => 5,
                'base_price' => 18900000,
                'currency' => 'VND',
                'service_selection_submitted' => 1,
                'service_ids' => [$selectedService->id],
            ])
            ->assertRedirect('/admin/landing-editor#featured-tours');

        $this->assertDatabaseHas('products', [
            'id' => $tour->id,
            'product_type' => 'tour',
            'name' => 'Hành trình di sản mới',
            'short_description' => 'Trải nghiệm tinh gọn được cập nhật ngay trên card.',
            'daily_price' => 18900000,
            'currency' => 'VND',
            'duration_days' => 5,
            'duration_nights' => 4,
        ]);
        $this->assertCount(1, $tour->fresh()->services);
        $this->assertSame($selectedService->id, $tour->fresh()->services->first()->id);

        $this->withToken($token)
            ->post('/admin/landing-editor/tours', [
                'name' => 'Tour tạo trực tiếp',
                'destination_id' => $destination->id,
                'short_description' => 'Tour được thêm ngay dưới danh sách.',
                'duration_days' => 3,
                'base_price' => 12500000,
                'currency' => 'VND',
                'service_selection_submitted' => 1,
                'service_ids' => [$selectedService->id],
                'image' => UploadedFile::fake()->image('inline-tour.jpg', 1200, 900),
            ])
            ->assertRedirect('/admin/landing-editor#featured-tours');

        $createdTour = Tour::where('name', 'Tour tạo trực tiếp')->firstOrFail();
        $this->assertTrue($createdTour->is_featured);
        $this->assertSame('tour-tao-truc-tiep', $createdTour->slug);

        $this->withToken($token)
            ->post('/admin/landing-editor/tours', ['name' => 'Thiếu dữ liệu'])
            ->assertSessionHasErrors(['short_description', 'duration_days', 'base_price', 'image']);

        $orderedIds = Destination::query()->orderByDesc('sort_order')->limit(5)->pluck('id')->all();
        $this->withToken($token)
            ->putJson('/admin/landing-editor/destinations-order', [
                'destination_ids' => $orderedIds,
            ])
            ->assertOk()
            ->assertJsonPath('message', 'Đã cập nhật thứ tự điểm đến.');
        foreach ($orderedIds as $sortOrder => $destinationId) {
            $this->assertDatabaseHas('products', [
                'id' => $destinationId,
                'product_type' => 'destination',
                'sort_order' => $sortOrder,
            ]);
        }

        $this->withToken($token)
            ->post('/admin/landing-editor/destinations', [
                'name' => 'Sa Pa trực tiếp',
                'short_description' => 'Điểm đến mới từ Visual Editor.',
                'image' => UploadedFile::fake()->image('sapa-inline.jpg', 1200, 900),
            ])
            ->assertRedirect('/admin/landing-editor#destination-priority');
        $createdDestination = Destination::where('name', 'Sa Pa trực tiếp')->firstOrFail();

        $this->withToken($token)
            ->delete("/admin/landing-editor/tours/{$createdTour->id}")
            ->assertRedirect('/admin/landing-editor#featured-tours');
        $this->assertSoftDeleted('products', ['id' => $createdTour->id]);

        $this->withToken($token)
            ->delete("/admin/landing-editor/destinations/{$createdDestination->id}")
            ->assertRedirect('/admin/landing-editor#destinations');
        $this->assertSoftDeleted('products', ['id' => $createdDestination->id]);
    }
}

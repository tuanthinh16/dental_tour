<?php

namespace Tests\Feature;

use App\Models\Destination;
use App\Models\ProductImage;
use App\Models\Tour;
use Database\Seeders\DemoContentSeeder;
use Database\Seeders\InitialAdminSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminMediaUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_uploads_images_to_product_directory_and_links_by_product_code(): void
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
            ->get('/admin/destinations/create')
            ->assertOk()
            ->assertSee('data-image-input', false);

        $this->withToken($token)
            ->post('/admin/destinations', [
                'name' => 'Sapa',
                'slug' => 'sapa',
                'short_description' => 'Thị trấn giữa mây.',
                'description' => 'Hành trình khám phá Sapa.',
                'sort_order' => 20,
                'is_active' => 1,
                'image' => UploadedFile::fake()->image('sapa.jpg', 1200, 800),
            ])
            ->assertRedirect('/admin/destinations');

        $destination = Destination::where('slug', 'sapa')->firstOrFail();
        $destinationMedia = $destination->image;
        $this->assertNotNull($destinationMedia);
        $this->assertSame($destination->product_code, $destinationMedia->product_code);
        $this->assertStringContainsString('/product/'.$destination->product_code.'/', $destinationMedia->file_path);
        Storage::disk('public')->assertExists($this->storagePath($destinationMedia));

        $this->withToken($token)
            ->post('/admin/tours', [
                'destination_id' => $destination->id,
                'name' => 'Sapa trong mây',
                'slug' => 'sapa-trong-may',
                'short_description' => 'Một hành trình mới.',
                'description' => 'Nội dung hành trình Sapa.',
                'duration_days' => 3,
                'duration_nights' => 2,
                'base_price' => 399,
                'currency' => 'USD',
                'sort_order' => 20,
                'is_active' => 1,
                'service_selection_submitted' => 1,
                'image' => UploadedFile::fake()->image('sapa-tour.png', 1200, 800),
            ])
            ->assertRedirect('/admin/tours');

        $tour = Tour::where('slug', 'sapa-trong-may')->firstOrFail();
        $this->assertNotNull($tour->image);
        $this->assertSame($tour->product_code, $tour->image->product_code);
        $this->assertSame('list', $tour->image->image_type);
        $this->assertStringContainsString('/product/'.$tour->product_code.'/', $tour->image->file_path);
        Storage::disk('public')->assertExists($this->storagePath($tour->image));
        $this->assertSame(2, ProductImage::query()
            ->whereIn('product_code', [$destination->product_code, $tour->product_code])
            ->where('file_path', 'like', '/storage/product/%')
            ->count());
    }

    private function storagePath(ProductImage $media): string
    {
        return ltrim(str_replace('/storage/', '', parse_url($media->file_path, PHP_URL_PATH)), '/');
    }
}

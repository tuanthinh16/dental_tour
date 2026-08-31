<?php

namespace Tests\Feature;

use App\Models\Setting;
use Database\Seeders\InitialAdminSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SeoFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_expose_seo_metadata_robots_and_sitemap(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('name="keywords"', false)
            ->assertSee('rel="canonical"', false)
            ->assertSee('rel="manifest"', false)
            ->assertSee('application/ld+json', false);

        $this->get('/robots.txt')
            ->assertOk()
            ->assertHeader('content-type', 'text/plain; charset=UTF-8')
            ->assertSee('Sitemap: http://localhost/sitemap.xml');

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertSee('<urlset', false)
            ->assertSee('http://localhost/tours', false);
    }

    public function test_admin_can_update_seo_settings(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $this->seed(InitialAdminSeeder::class);
        Storage::fake('public');
        $token = $this->postJson('/api/admin/auth/login', [
            'email' => 'admin@example.com',
            'password' => '123',
        ])->json('access_token');

        $this->withToken($token)
            ->get('/admin/settings?tab=seo')
            ->assertOk()
            ->assertSee('SEO website')
            ->assertSee('data-settings-tab="seo"', false)
            ->assertSee('seo_keywords', false);

        $this->withToken($token)
            ->put('/admin/settings/seo', [
                'seo_site_title' => 'Tour Phú Quốc | Dental Tour',
                'seo_site_description' => 'Khám phá hành trình riêng tại Phú Quốc.',
                'seo_keywords' => 'tour Phú Quốc, cano Phú Quốc',
                'seo_og_image_upload' => UploadedFile::fake()->image('seo-share.jpg', 1200, 630),
                'seo_sitemap_urls' => "/gioi-thieu\n/chinh-sach-bao-mat",
            ])
            ->assertRedirect(route('admin.settings.index', ['tab' => 'seo']));

        $this->assertDatabaseHas('settings', [
            'key' => 'seo_keywords',
            'value' => 'tour Phú Quốc, cano Phú Quốc',
        ]);
        $this->assertDatabaseHas('settings', [
            'key' => 'seo_sitemap_urls',
            'value' => "/gioi-thieu\n/chinh-sach-bao-mat",
        ]);
        $ogImage = Setting::where('key', 'seo_og_image')->value('value');
        $this->assertStringStartsWith('/storage/site/seo/', $ogImage);
        Storage::disk('public')->assertExists(substr($ogImage, strlen('/storage/')));
        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertSee('http://localhost/gioi-thieu', false)
            ->assertSee('http://localhost/chinh-sach-bao-mat', false);
    }
}

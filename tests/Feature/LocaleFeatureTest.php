<?php

namespace Tests\Feature;

use App\Models\Destination;
use App\Models\Setting;
use App\Models\Tour;
use Database\Seeders\DemoContentSeeder;
use Database\Seeders\InitialAdminSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocaleFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_english_uses_translations_and_keeps_destination_names_unchanged(): void
    {
        $this->seed(DemoContentSeeder::class);

        $tour = Tour::query()->firstOrFail();
        $tour->replaceTranslation('en', [
            'name' => 'Island discovery in Phú Quốc',
            'short_description' => 'A quieter way to explore the sea.',
        ]);

        $destination = Destination::query()->firstOrFail();
        $destination->replaceTranslation('en', [
            'short_description' => 'A local rhythm beside the sea.',
        ]);

        $this->get('/language/en?redirect=/')->assertRedirect('/');
        $this->withSession(['locale' => 'en'])
            ->get('/')
            ->assertOk()
            ->assertSee('Explore journeys')
            ->assertSee('Island discovery in Phú Quốc')
            ->assertSee($destination->name);
    }

    public function test_visual_editor_saves_english_content_separately_from_vietnamese_source(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $this->seed(InitialAdminSeeder::class);
        $this->seed(DemoContentSeeder::class);

        $token = $this->postJson('/api/admin/auth/login', [
            'email' => 'admin@example.com',
            'password' => '123',
        ])->json('access_token');
        $tour = Tour::query()->firstOrFail();
        $destination = Destination::query()->firstOrFail();
        $sourceTourName = $tour->name;
        $sourceDestinationName = $destination->name;

        $this->withSession(['locale' => 'en'])->withToken($token)
            ->put('/admin/landing-editor/hero-image', [
                'eyebrow' => 'Designed journeys',
                'title_line_1' => 'Vietnam awaits.',
                'title_before_image' => 'Travel your',
                'title_after_image' => 'own way.',
                'description' => 'An English hero translation.',
            ])
            ->assertRedirect('/admin/landing-editor#landing-hero');

        $this->withSession(['locale' => 'en'])->withToken($token)
            ->put('/admin/landing-editor/destinations/'.$destination->id, [
                'short_description' => 'An English destination translation.',
            ])
            ->assertRedirect('/admin/landing-editor#destinations');

        $this->withSession(['locale' => 'en'])->withToken($token)
            ->put('/admin/landing-editor/tours/'.$tour->id, [
                'name' => 'A day in Phú Quốc',
                'short_description' => 'An English tour summary.',
                'description' => 'An English tour description.',
            ])
            ->assertRedirect('/admin/landing-editor#featured-tours');

        $this->assertSame('Vietnam awaits.', Setting::where('key', 'landing_hero_title_line_1_en')->value('value'));
        $this->assertSame($sourceDestinationName, $destination->fresh()->name);
        $this->assertSame('An English destination translation.', $destination->fresh()->translated('short_description', 'en'));
        $this->assertSame($sourceTourName, $tour->fresh()->name);
        $this->assertSame('A day in Phú Quốc', $tour->fresh()->translated('name', 'en'));
    }
}

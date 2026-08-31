<?php

namespace Tests\Feature;

use App\Support\ThemeOptions;
use Database\Seeders\InitialAdminSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ThemeSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_theme_options_fall_back_when_stored_values_are_invalid(): void
    {
        $theme = ThemeOptions::normalize([
            'ui_color_primary' => 'not-a-color',
            'ui_font_title' => 'Unknown Font',
        ]);

        $this->assertSame(ThemeOptions::DEFAULTS['ui_color_primary'], $theme['ui_color_primary']);
        $this->assertSame(ThemeOptions::DEFAULTS['ui_font_title'], $theme['ui_font_title']);
        $this->assertSame('#0B1F1B', $theme['ui_color_accent_contrast']);
        $this->assertSame('Be Vietnam Pro', ThemeOptions::DEFAULTS['ui_font_header']);
        $this->assertSame('Lora', ThemeOptions::DEFAULTS['ui_font_title']);
        $this->assertSame('Inter', ThemeOptions::DEFAULTS['ui_font_body']);
        $this->assertStringContainsString('Be Vietnam Pro', $theme['ui_font_header_stack']);
        $this->assertStringContainsString('Lora', $theme['ui_font_title_stack']);
        $this->assertStringContainsString('Inter', $theme['ui_font_body_stack']);
    }

    public function test_super_admin_can_update_theme_settings(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $this->seed(InitialAdminSeeder::class);

        $login = $this->postJson('/api/admin/auth/login', [
            'email' => 'admin@example.com',
            'password' => '123',
        ])->assertOk();

        $this->withToken($login->json('access_token'))
            ->get('/admin/settings')
            ->assertOk()
            ->assertSee('Giao diện website')
            ->assertSee('Xem trước website')
            ->assertSee('data-theme-preview-modal', false)
            ->assertSee('data-font-preview="ui_font_header"', false)
            ->assertSee('data-default="Be Vietnam Pro"', false)
            ->assertSee('data-default="Lora"', false)
            ->assertSee('data-default="Inter"', false);

        $payload = array_merge(ThemeOptions::DEFAULTS, [
            'ui_color_primary' => '#204E45',
            'ui_font_title' => 'Playfair Display',
        ]);

        $this->withToken($login->json('access_token'))
            ->put('/admin/settings/theme', $payload)
            ->assertRedirect('/admin/settings');

        $this->assertDatabaseHas('settings', [
            'key' => 'ui_color_primary',
            'value' => '#204E45',
        ]);
        $this->assertDatabaseHas('settings', [
            'key' => 'ui_font_title',
            'value' => 'Playfair Display',
        ]);
    }

    public function test_theme_settings_reject_unknown_fonts_and_invalid_colors(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $this->seed(InitialAdminSeeder::class);

        $login = $this->postJson('/api/admin/auth/login', [
            'email' => 'admin@example.com',
            'password' => '123',
        ])->assertOk();

        $payload = array_merge(ThemeOptions::DEFAULTS, [
            'ui_color_accent' => 'red',
            'ui_font_body' => 'Injected Font',
        ]);

        $this->withToken($login->json('access_token'))
            ->put('/admin/settings/theme', $payload)
            ->assertSessionHasErrors(['ui_color_accent', 'ui_font_body']);

        $this->assertDatabaseMissing('settings', [
            'key' => 'ui_color_primary',
        ]);
        $this->assertDatabaseMissing('settings', [
            'key' => 'ui_font_body',
        ]);
    }
}

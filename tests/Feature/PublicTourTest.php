<?php

namespace Tests\Feature;

use App\Models\Tour;
use Database\Seeders\DemoContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicTourTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_and_tour_detail_are_available(): void
    {
        $this->seed(DemoContentSeeder::class);
        $tour = Tour::firstOrFail();
        $this->get('/')
            ->assertOk()
            ->assertSee('Việt Nam')
            ->assertSee('Tour cano 3–4 đảo Phú Quốc')
            ->assertSee('Cầu Hôn, show đêm &amp; pháo hoa', false)
            ->assertSee('data-tour-zigzag-grid', false)
            ->assertDontSee('tour-stack-card', false)
            ->assertSee('Đánh giá từ người đã trải nghiệm');
        $this->get('/tours')
            ->assertOk()
            ->assertSee('Hòn Thơm: Cáp treo &amp; công viên nước', false)
            ->assertSee('Grand World Phú Quốc')
            ->assertSee('data-tour-zigzag-grid', false)
            ->assertSee('Liên hệ');
        $this->get('/tours/'.$tour->slug)
            ->assertOk()
            ->assertSee($tour->name);
    }

    public function test_consultation_is_validated_and_stored(): void
    {
        $this->post('/consultation', [])->assertSessionHasErrors([
            'full_name',
            'email',
            'phone',
        ]);
        $this->post('/consultation', [
            'full_name' => 'Nguyễn Văn A',
            'email' => 'a@example.com',
            'phone' => '0900000000',
        ])->assertRedirect();
        $this->assertDatabaseHas('consultation_requests', [
            'email' => 'a@example.com',
            'status' => 'new',
        ]);
    }
}

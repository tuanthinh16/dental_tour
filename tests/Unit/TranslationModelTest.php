<?php

namespace Tests\Unit;

use App\Models\Tour;
use Tests\TestCase;

class TranslationModelTest extends TestCase
{
    public function test_model_returns_the_source_value_without_an_english_translation(): void
    {
        $tour = new Tour(['name' => 'Tour Phú Quốc', 'translations' => []]);

        app()->setLocale('en');

        $this->assertSame('Tour Phú Quốc', $tour->translated('name'));
    }

    public function test_itinerary_accessor_reads_the_localized_source_data(): void
    {
        $tour = new Tour(['itinerary_data' => [['day_number' => 1, 'title' => 'Ngày đầu']]]);

        $this->assertSame('Ngày đầu', $tour->itineraries->first()->title);
    }
}

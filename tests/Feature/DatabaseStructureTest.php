<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DatabaseStructureTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_uses_the_new_product_structure_only(): void
    {
        $this->assertTrue(Schema::hasColumns('products', [
            'product_code',
            'product_type',
            'daily_price',
            'category_ids',
            'included_product_ids',
        ]));
        $this->assertTrue(Schema::hasColumns('categories', [
            'category_code',
            'name',
            'is_active',
        ]));
        $this->assertTrue(Schema::hasColumns('product_image', [
            'product_code',
            'image_type',
            'file_path',
        ]));

        foreach ([
            'destinations',
            'tours',
            'tour_categories',
            'tour_services',
            'tour_service_assignments',
            'tour_itineraries',
            'tour_excluded_items',
            'media',
        ] as $legacyTable) {
            $this->assertFalse(Schema::hasTable($legacyTable));
        }
    }
}

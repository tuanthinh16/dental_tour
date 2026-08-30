<?php

namespace Tests\Feature;

use App\Models\Destination;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Tour;
use Database\Seeders\DemoContentSeeder;
use Database\Seeders\ProductCatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductCatalogSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_is_replaced_from_the_two_source_images_without_changing_destinations(): void
    {
        Storage::fake('public');
        $this->seed(DemoContentSeeder::class);

        $destinationSnapshot = Destination::query()
            ->orderBy('id')
            ->get(['id', 'product_code', 'name', 'slug', 'short_description', 'description', 'sort_order'])
            ->toArray();

        Product::create([
            'product_code' => 'OLD_PRODUCT',
            'product_type' => 'addon',
            'name' => 'Sản phẩm cũ',
            'slug' => 'san-pham-cu',
        ]);

        $this->seed(ProductCatalogSeeder::class);

        $this->assertSame($destinationSnapshot, Destination::query()
            ->orderBy('id')
            ->get(['id', 'product_code', 'name', 'slug', 'short_description', 'description', 'sort_order'])
            ->toArray());
        $this->assertDatabaseMissing('products', ['product_code' => 'OLD_PRODUCT']);
        $this->assertSame(7, Tour::query()->count());
        $this->assertSame(11, Product::query()->where('product_type', 'addon')->count());
        $this->assertSame(18, ProductImage::query()
            ->whereIn('product_code', Product::query()->where('product_type', '!=', 'destination')->pluck('product_code'))
            ->where('image_type', 'list')
            ->count());

        $tour = Tour::query()->where('product_code', 'TOUR_PQ_3_4_ISLAND')->firstOrFail();
        $this->assertSame('phu-quoc', $tour->destination->slug);
        $this->assertSame('0.00', $tour->daily_price);
        Storage::disk('public')->assertExists('product/TOUR_PQ_3_4_ISLAND/play-services.jpg');
        Storage::disk('public')->assertExists('product/STAY_PQ_ROOM/master-services.jpg');
    }
}

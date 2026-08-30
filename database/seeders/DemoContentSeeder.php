<?php

namespace Database\Seeders;

use App\Models\Destination;
use App\Models\IncludedService;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Setting;
use App\Support\ThemeOptions;
use Illuminate\Database\Seeder;

class DemoContentSeeder extends Seeder
{
    public function run(): void
    {
        $destinationData = [
            ['DEST_DA_NANG', 'Đà Nẵng', 'da-nang', 'https://images.unsplash.com/photo-1559592413-7cec4d0cae2b?auto=format&fit=crop&w=900&q=80'],
            ['DEST_HOI_AN', 'Hội An', 'hoi-an', 'https://images.unsplash.com/photo-1559592413-7cec4d0cae2b?auto=format&fit=crop&w=900&q=80'],
            ['DEST_HUE', 'Huế', 'hue', 'https://images.unsplash.com/photo-1583417319070-4a69db38a482?auto=format&fit=crop&w=900&q=80'],
            ['DEST_NHA_TRANG', 'Nha Trang', 'nha-trang', 'https://images.unsplash.com/photo-1573790387438-4da905039392?auto=format&fit=crop&w=900&q=80'],
            ['DEST_PHU_QUOC', 'Phú Quốc', 'phu-quoc', 'https://images.unsplash.com/photo-1528127269322-539801943592?auto=format&fit=crop&w=900&q=80'],
        ];

        foreach ($destinationData as $index => [$code, $name, $slug, $url]) {
            $destination = Destination::updateOrCreate(
                ['product_code' => $code],
                [
                    'name' => $name,
                    'slug' => $slug,
                    'short_description' => 'Khám phá vẻ đẹp và văn hóa đặc sắc của '.$name.'.',
                    'description' => 'Một điểm đến giàu trải nghiệm với cảnh quan, ẩm thực và con người đáng nhớ.',
                    'sort_order' => $index,
                    'is_active' => true,
                ],
            );
            $this->seedImage($destination, $url);
        }

        collect([
            ['HOTEL', 'Khách sạn', 'Lưu trú theo tiêu chuẩn của từng hành trình.'],
            ['BREAKFAST', 'Bữa sáng', 'Bữa sáng trong các ngày lưu trú tại khách sạn.'],
            ['AIRPORT_TRANSFER', 'Đưa đón sân bay', 'Xe riêng đón và tiễn tại sân bay theo lịch trình.'],
            ['TOUR_GUIDE', 'Hướng dẫn viên', 'Hướng dẫn viên đồng hành tại điểm đến.'],
            ['ENTRANCE_TICKET', 'Vé tham quan', 'Vé vào cửa cho các điểm có trong lịch trình.'],
        ])->map(fn (array $row, int $index) => IncludedService::updateOrCreate(
            ['category_code' => $row[0]],
            [
                'name' => $row[1],
                'description' => $row[2],
                'sort_order' => $index,
                'is_active' => true,
            ],
        ));

        $this->call(ProductCatalogSeeder::class);

        foreach ([
            'site_name' => 'Dental Tour',
            'contact_email' => 'hello@dentaltour.vn',
            'contact_phone' => '+84 900 000 000',
            'company_address' => 'Đà Nẵng, Việt Nam',
        ] as $key => $value) {
            Setting::updateOrCreate(compact('key'), compact('value'));
        }

        // Thêm 2026-08-29: giá trị mặc định cho trình tùy chỉnh màu và font trong Admin CMS.
        foreach (ThemeOptions::DEFAULTS as $key => $value) {
            Setting::firstOrCreate(compact('key'), compact('value'));
        }
    }

    private function seedImage(Product $product, string $url): void
    {
        ProductImage::updateOrCreate(
            ['product_code' => $product->product_code, 'image_type' => 'list', 'sort_order' => 0],
            [
                'file_name' => strtolower($product->product_code).'.jpg',
                'file_path' => $url,
                'alt_text' => $product->name,
                'is_active' => true,
            ],
        );
    }
}

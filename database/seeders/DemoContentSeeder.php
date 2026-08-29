<?php

namespace Database\Seeders;

use App\Models\Destination;
use App\Models\IncludedService;
use App\Models\Media;
use App\Models\Setting;
use App\Models\Tour;
use App\Models\TourCategory;
use App\Support\ThemeOptions;
use Illuminate\Database\Seeder;

class DemoContentSeeder extends Seeder
{
    public function run(): void
    {
        $destinationData = [
            [
                'Đà Nẵng',
                'da-nang',
                'https://images.unsplash.com/photo-1559592413-7cec4d0cae2b?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'Hội An',
                'hoi-an',
                'https://images.unsplash.com/photo-1559592413-7cec4d0cae2b?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'Huế',
                'hue',
                'https://images.unsplash.com/photo-1583417319070-4a69db38a482?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'Nha Trang',
                'nha-trang',
                'https://images.unsplash.com/photo-1573790387438-4da905039392?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'Phú Quốc',
                'phu-quoc',
                'https://images.unsplash.com/photo-1528127269322-539801943592?auto=format&fit=crop&w=900&q=80',
            ],
        ];
        foreach ($destinationData as $i => [$name, $slug, $url]) {
            $media = Media::updateOrCreate(
                ['file_name' => $slug.'.jpg'],
                ['file_path' => $url, 'alt_text' => $name, 'is_active' => true],
            );
            Destination::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $name,
                    'short_description' => 'Khám phá vẻ đẹp và văn hóa đặc sắc của '.$name.'.',
                    'description' => 'Một điểm đến giàu trải nghiệm với cảnh quan, ẩm thực và con người đáng nhớ.',
                    'image_id' => $media->id,
                    'sort_order' => $i,
                    'is_active' => true,
                ],
            );
        }
        foreach (
            ['City Tour', 'Island Tour', 'Cultural Tour', 'Family Tour'] as $i => $name
        ) {
            TourCategory::updateOrCreate(
                ['slug' => str($name)->slug()],
                ['name' => $name, 'sort_order' => $i, 'is_active' => true],
            );
        }
        $includedServices = collect([
            ['Khách sạn', 'Lưu trú theo tiêu chuẩn của từng hành trình.'],
            ['Bữa sáng', 'Bữa sáng trong các ngày lưu trú tại khách sạn.'],
            ['Đưa đón sân bay', 'Xe riêng đón và tiễn tại sân bay theo lịch trình.'],
            ['Hướng dẫn viên', 'Hướng dẫn viên đồng hành tại điểm đến.'],
            ['Vé tham quan', 'Vé vào cửa cho các điểm có trong lịch trình.'],
        ])->map(function (array $service, int $index): IncludedService {
            return IncludedService::updateOrCreate(
                ['name' => $service[0]],
                [
                    'description' => $service[1],
                    'sort_order' => $index,
                    'is_active' => true,
                ],
            );
        });

        $tours = [
            [
                'Tinh hoa Đà Nẵng',
                'tinh-hoa-da-nang',
                'da-nang',
                'City Tour',
                3,
                2,
                399,
                'Bán chạy',
            ],
            [
                'Phố cổ và miền di sản',
                'pho-co-va-mien-di-san',
                'hoi-an',
                'Cultural Tour',
                4,
                3,
                549,
                'Yêu thích',
            ],
            [
                'Dấu ấn Cố đô',
                'dau-an-co-do',
                'hue',
                'Cultural Tour',
                3,
                2,
                429,
                null,
            ],
            [
                'Nha Trang biển gọi',
                'nha-trang-bien-goi',
                'nha-trang',
                'Island Tour',
                4,
                3,
                599,
                'Mới',
            ],
            [
                'Thiên đường Phú Quốc',
                'thien-duong-phu-quoc',
                'phu-quoc',
                'Island Tour',
                5,
                4,
                749,
                'Luxury',
            ],
            [
                'Đà Nẵng cho cả gia đình',
                'da-nang-cho-ca-gia-dinh',
                'da-nang',
                'Family Tour',
                4,
                3,
                629,
                null,
            ],
            [
                'Hội An chậm rãi',
                'hoi-an-cham-rai',
                'hoi-an',
                'City Tour',
                3,
                2,
                459,
                null,
            ],
            [
                'Hành trình miền Trung',
                'hanh-trinh-mien-trung',
                'hue',
                'Family Tour',
                6,
                5,
                899,
                'Trọn gói',
            ],
        ];
        foreach (
            $tours as $i => [
                $name,
                $slug,
                $destination,
                $category,
                $days,
                $nights,
                $price,
                $badge,
            ]
        ) {
            $dest = Destination::where('slug', $destination)->firstOrFail();
            $tour = Tour::updateOrCreate(
                ['slug' => $slug],
                [
                    'destination_id' => $dest->id,
                    'category_id' => TourCategory::where(
                        'name',
                        $category,
                    )->value('id'),
                    'name' => $name,
                    'short_description' => 'Hành trình chọn lọc đưa bạn đến gần hơn với thiên nhiên, văn hóa và con người địa phương.',
                    'description' => "Khám phá điểm đến theo một cách trọn vẹn và thư thái. Lịch trình cân bằng giữa tham quan, trải nghiệm bản địa và thời gian tự do.\n\nĐội ngũ của chúng tôi đồng hành xuyên suốt để mỗi khoảnh khắc trong chuyến đi đều thật đáng nhớ.",
                    'duration_days' => $days,
                    'duration_nights' => $nights,
                    'base_price' => $price,
                    'original_price' => $price + 100,
                    'currency' => 'USD',
                    'image_id' => $dest->image_id,
                    'badge' => $badge,
                    'is_featured' => $i < 6,
                    'sort_order' => $i,
                    'is_active' => true,
                ],
            );
            if (! $tour->itineraries()->exists()) {
                for ($day = 1; $day <= $days; $day++) {
                    $tour
                        ->itineraries()
                        ->create([
                            'day_number' => $day,
                            'title' => $day === 1
                                    ? 'Chào đón và khám phá'
                                    : 'Trải nghiệm ngày '.$day,
                            'description' => 'Tham quan các điểm nổi bật, thưởng thức ẩm thực địa phương và tận hưởng thời gian tự do.',
                            'sort_order' => $day,
                            'is_active' => true,
                        ]);
                }
            }
            $tour->services()->sync($includedServices->pluck('id'));
            if (! $tour->excludedItems()->exists()) {
                foreach (
                    ['Vé máy bay', 'Chi phí cá nhân', 'Phí visa'] as $j => $content
                ) {
                    $tour
                        ->excludedItems()
                        ->create(
                            compact('content') + [
                                'sort_order' => $j,
                                'is_active' => true,
                            ],
                        );
                }
            }
        }
        foreach (
            [
                'site_name' => 'Dental Tour',
                'contact_email' => 'hello@dentaltour.vn',
                'contact_phone' => '+84 900 000 000',
                'company_address' => 'Đà Nẵng, Việt Nam',
            ] as $key => $value
        ) {
            Setting::updateOrCreate(compact('key'), compact('value'));
        }

        // Thêm 2026-08-29: giá trị mặc định cho trình tùy chỉnh màu và font trong Admin CMS.
        foreach (ThemeOptions::DEFAULTS as $key => $value) {
            Setting::firstOrCreate(compact('key'), compact('value'));
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Destination;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class ProductCatalogSeeder extends Seeder
{
    private const SOURCE_IMAGES = [
        'play' => 'phu-quoc-play-services.jpg',
        'master' => 'phu-quoc-master-center.jpg',
    ];

    public function run(): void
    {
        $this->assertSourceImagesExist();

        $oldProductCodes = Product::withTrashed()
            ->where('product_type', '!=', 'destination')
            ->pluck('product_code');

        $createdProducts = DB::transaction(function () {
            Product::withTrashed()
                ->where('product_type', '!=', 'destination')
                ->get()
                ->each->forceDelete();

            $destinationId = Destination::query()
                ->where('slug', 'phu-quoc')
                ->value('id');

            if (! $destinationId) {
                throw new RuntimeException('Không tìm thấy điểm đến Phú Quốc để gắn sản phẩm.');
            }

            $createdProducts = collect();

            foreach ($this->products() as $index => $data) {
                $source = $data['source'];
                unset($data['source']);

                $product = Product::create($data + [
                    'destination_id' => $data['product_type'] === 'tour' ? $destinationId : null,
                    'daily_price' => 0,
                    'currency' => 'VND',
                    'sort_order' => $index,
                    'is_active' => true,
                ]);

                $createdProducts->push([$product, $source]);
            }

            return $createdProducts;
        });

        $oldProductCodes->each(
            fn (string $productCode) => Storage::disk('public')
                ->deleteDirectory('product/'.$productCode),
        );

        $createdProducts->each(
            fn (array $item) => $this->storeProductImage($item[0], $item[1]),
        );
    }

    private function products(): array
    {
        return [
            $this->tour(
                'TOUR_PQ_3_4_ISLAND',
                'Tour cano 3–4 đảo Phú Quốc',
                'tour-cano-3-4-dao-phu-quoc',
                'Lặn ngắm san hô và trải nghiệm đi bộ dưới biển bằng cano cao tốc.',
                'Tour cano tham quan 3–4 đảo, kết hợp snorkeling và sea walking tại Phú Quốc.',
                'Nổi bật',
            ),
            $this->tour(
                'TOUR_PQ_DAILY_LAND',
                'Tour đường bộ Phú Quốc hằng ngày',
                'tour-duong-bo-phu-quoc-hang-ngay',
                'Khám phá các điểm nổi bật của Phú Quốc bằng hành trình đường bộ trong ngày.',
                'Daily Land Tour dành cho khách muốn tham quan Phú Quốc theo lịch khởi hành hằng ngày.',
                'Hằng ngày',
            ),
            $this->tour(
                'TOUR_PQ_KISS_BRIDGE',
                'Cầu Hôn, show đêm & pháo hoa',
                'cau-hon-show-dem-phao-hoa',
                'Vé tham quan Cầu Hôn kết hợp chương trình biểu diễn buổi tối và pháo hoa.',
                'Sản phẩm vé Cầu Hôn, night show và fireworks tại Phú Quốc.',
                'Vé trải nghiệm',
            ),
            $this->tour(
                'TOUR_PQ_HON_THOM',
                'Hòn Thơm: Cáp treo & công viên nước',
                'hon-thom-cap-treo-cong-vien-nuoc',
                'Trải nghiệm cáp treo Hòn Thơm và vui chơi tại công viên nước.',
                'Sản phẩm vé Hòn Thơm Cable Car & Water Park.',
                'Vé vui chơi',
            ),
            $this->tour(
                'TOUR_PQ_VINWONDERS',
                'VinWonders Phú Quốc',
                'vinwonders-phu-quoc',
                'Vé vui chơi và khám phá VinWonders Phú Quốc.',
                'Sản phẩm vé VinWonders trong hệ thống trải nghiệm tại Phú Quốc.',
                'Vé vui chơi',
            ),
            $this->tour(
                'TOUR_PQ_SAFARI',
                'Safari Phú Quốc',
                'safari-phu-quoc',
                'Vé tham quan Safari Phú Quốc dành cho cá nhân, nhóm và gia đình.',
                'Sản phẩm vé Safari trong hệ thống trải nghiệm tại Phú Quốc.',
                'Vé tham quan',
            ),
            $this->tour(
                'TOUR_PQ_GRAND_WORLD',
                'Grand World Phú Quốc',
                'grand-world-phu-quoc',
                'Khám phá tổ hợp giải trí và trải nghiệm Grand World Phú Quốc.',
                'Sản phẩm trải nghiệm Grand World trong danh mục dịch vụ Phú Quốc.',
                'Trải nghiệm',
            ),
            $this->addon('ADDON_PQ_PRIVATE_SPEEDBOAT', 'Cano riêng Phú Quốc', 'cano-rieng-phu-quoc', 'Dịch vụ cano riêng theo nhu cầu tại Phú Quốc.', 'Private speedboat dành cho nhóm khách cần hành trình riêng.', 'play'),
            $this->addon('ADDON_PQ_PRIVATE_YACHT', 'Du thuyền riêng Phú Quốc', 'du-thuyen-rieng-phu-quoc', 'Dịch vụ du thuyền riêng tại Phú Quốc.', 'Private yacht dành cho hành trình và sự kiện riêng.', 'play'),
            $this->addon('ADDON_PQ_PRIVATE_CAR', 'Xe riêng Phú Quốc', 'xe-rieng-phu-quoc', 'Dịch vụ xe riêng và đưa đón tại Phú Quốc.', 'Private car phục vụ lịch trình cá nhân và nhóm khách.', 'play'),
            $this->addon('STAY_PQ_ROOM', 'Phòng nghỉ Phú Quốc', 'phong-nghi-phu-quoc', 'Đặt phòng nghỉ tại Phú Quốc.', 'Dịch vụ phòng và hỗ trợ lưu trú thuộc nhóm Stay.', 'master'),
            $this->addon('STAY_PQ_VILLA', 'Villa Phú Quốc', 'villa-phu-quoc', 'Đặt villa lưu trú tại Phú Quốc.', 'Dịch vụ villa và hỗ trợ lưu trú thuộc nhóm Stay.', 'master'),
            $this->addon('STAY_PQ_BOOKING', 'Dịch vụ booking lưu trú', 'dich-vu-booking-luu-tru', 'Hỗ trợ tìm kiếm và đặt dịch vụ lưu trú.', 'Booking phòng và villa theo nhu cầu của khách hàng.', 'master'),
            $this->addon('STAY_PQ_MANAGEMENT', 'Quản lý lưu trú', 'quan-ly-luu-tru-phu-quoc', 'Dịch vụ quản lý phòng và villa tại Phú Quốc.', 'Quản lý vận hành sản phẩm lưu trú thuộc nhóm Stay.', 'master'),
            $this->addon('INVEST_PQ_PROPERTY', 'Bất động sản Phú Quốc', 'bat-dong-san-phu-quoc', 'Tư vấn sản phẩm bất động sản tại Phú Quốc.', 'Dịch vụ Property thuộc nhóm Invest.', 'master'),
            $this->addon('INVEST_PQ_RENTAL', 'Cho thuê bất động sản', 'cho-thue-bat-dong-san-phu-quoc', 'Dịch vụ cho thuê bất động sản tại Phú Quốc.', 'Dịch vụ Rental thuộc nhóm Invest.', 'master'),
            $this->addon('INVEST_PQ_SETUP', 'Setup vận hành bất động sản', 'setup-van-hanh-bat-dong-san', 'Thiết lập quy trình và hệ thống vận hành bất động sản.', 'Dịch vụ Setup thuộc nhóm Invest.', 'master'),
            $this->addon('INVEST_PQ_MANAGEMENT', 'Quản lý bất động sản', 'quan-ly-bat-dong-san-phu-quoc', 'Dịch vụ quản lý bất động sản tại Phú Quốc.', 'Dịch vụ Management thuộc nhóm Invest.', 'master'),
        ];
    }

    private function tour(
        string $code,
        string $name,
        string $slug,
        string $shortDescription,
        string $description,
        string $badge,
    ): array {
        return [
            'product_code' => $code,
            'product_type' => 'tour',
            'name' => $name,
            'slug' => $slug,
            'short_description' => $shortDescription,
            'description' => $description,
            'duration_days' => 1,
            'duration_nights' => 0,
            'badge' => $badge,
            'itinerary_data' => [],
            'excluded_items' => [],
            'is_featured' => true,
            'source' => 'play',
        ];
    }

    private function addon(
        string $code,
        string $name,
        string $slug,
        string $shortDescription,
        string $description,
        string $source,
    ): array {
        return [
            'product_code' => $code,
            'product_type' => 'addon',
            'name' => $name,
            'slug' => $slug,
            'short_description' => $shortDescription,
            'description' => $description,
            'is_featured' => false,
            'source' => $source,
        ];
    }

    private function storeProductImage(Product $product, string $source): void
    {
        $sourceFileName = self::SOURCE_IMAGES[$source];
        $storedFileName = $source.'-services.jpg';
        $relativePath = 'product/'.$product->product_code.'/'.$storedFileName;

        Storage::disk('public')->put(
            $relativePath,
            file_get_contents(database_path('seeders/assets/'.$sourceFileName)),
        );

        ProductImage::create([
            'product_code' => $product->product_code,
            'image_type' => 'list',
            'file_name' => $storedFileName,
            'file_path' => '/storage/'.$relativePath,
            'alt_text' => $product->name,
            'sort_order' => 0,
            'is_active' => true,
        ]);
    }

    private function assertSourceImagesExist(): void
    {
        foreach (self::SOURCE_IMAGES as $fileName) {
            if (! is_file(database_path('seeders/assets/'.$fileName))) {
                throw new RuntimeException('Thiếu ảnh nguồn catalog: '.$fileName);
            }
        }
    }
}

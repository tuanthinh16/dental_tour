<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Tour;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductService
{
    public function __construct(private MediaService $media) {}

    public function save(array $data, ?Product $product = null): Product
    {
        return DB::transaction(function () use ($data, $product): Product {
            $image = $data['image'] ?? null;
            unset($data['image']);

            if (! $product) {
                $data['product_code'] = Product::makeUniqueCode($data['name'], 'ADDON');
                $data['product_type'] = 'addon';
                $product = Product::create($data);
            } else {
                $product->update($data);
            }

            if ($image instanceof UploadedFile) {
                $this->media->uploadProductImage($image, $product->product_code, 'list', $product->name);
                $product->unsetRelation('image');
            }

            Log::info('Admin add-on product saved', ['product_id' => $product->id]);

            return $product;
        });
    }

    public function delete(Product $product): void
    {
        DB::transaction(function () use ($product): void {
            Tour::query()->get()->each(function (Tour $tour) use ($product): void {
                if (in_array($product->id, $tour->includedProductIdList(), true)) {
                    $tour->syncIncludedProductIds(array_values(array_diff(
                        $tour->includedProductIdList(),
                        [$product->id],
                    )));
                }
            });

            $product->delete();
        });

        Log::info('Admin add-on product deleted', ['product_id' => $product->id]);
    }
}

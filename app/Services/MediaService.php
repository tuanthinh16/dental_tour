<?php

namespace App\Services;

use App\Models\ProductImage;
use Illuminate\Http\UploadedFile;

class MediaService
{
    public function uploadProductImage(
        UploadedFile $file,
        string $productCode,
        string $imageType = 'list',
        ?string $altText = null,
    ): ProductImage {
        $path = $file->store('product/'.$productCode, 'public');

        return ProductImage::updateOrCreate(
            ['product_code' => $productCode, 'image_type' => $imageType, 'sort_order' => 0],
            [
                'file_name' => $file->getClientOriginalName(),
                'file_path' => '/storage/'.$path,
                'alt_text' => $altText,
                'is_active' => true,
            ],
        );
    }

    public function uploadSiteImage(UploadedFile $file, string $directory): string
    {
        return '/storage/'.$file->store('site/'.$directory, 'public');
    }
}

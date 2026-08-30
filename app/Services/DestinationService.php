<?php

namespace App\Services;

use App\Models\Destination;
use App\Models\Product;
use App\Repositories\DestinationRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class DestinationService
{
    public function __construct(
        public DestinationRepository $repository,
        private MediaService $media,
    ) {}

    public function save(array $data, ?Destination $item = null): Destination
    {
        $image = $data['image'] ?? null;
        unset($data['image']);

        if (! $item) {
            $data['product_code'] = $data['product_code']
                ?? Product::makeUniqueCode($data['name'], 'DEST');
        }

        $saved = $item
            ? $this->repository->update($item, $data)
            : $this->repository->create($data);

        if ($image instanceof UploadedFile) {
            $this->media->uploadProductImage($image, $saved->product_code, 'list', $saved->name);
            $saved->unsetRelation('image');
        }
        Log::info('Admin destination saved', ['destination_id' => $saved->id]);

        return $saved;
    }

    public function delete(Destination $item): void
    {
        $this->repository->delete($item);
        Log::info('Admin destination deleted', ['destination_id' => $item->id]);
    }
}

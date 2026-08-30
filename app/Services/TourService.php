<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Tour;
use App\Repositories\TourRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TourService
{
    public function __construct(
        public TourRepository $repository,
        private MediaService $media,
    ) {}

    public function save(array $data, ?Tour $tour = null): Tour
    {
        return DB::transaction(function () use ($data, $tour): Tour {
            $relations = Arr::only($data, ['itineraries', 'excluded_items', 'service_ids', 'included_product_ids']);
            $main = Arr::except($data, array_keys($relations));
            $image = $main['image'] ?? null;
            unset($main['image']);

            if (! $tour) {
                $main['product_code'] = $main['product_code']
                    ?? Product::makeUniqueCode($main['name'], 'TOUR');
            }

            $saved = $tour
                ? $this->repository->update($tour, $main)
                : $this->repository->create($main);

            if ($image instanceof UploadedFile) {
                $this->media->uploadProductImage($image, $saved->product_code, 'list', $saved->name);
                $saved->unsetRelation('image');
            }

            if (array_key_exists('service_ids', $relations)) {
                $saved->syncCategoryIds($relations['service_ids'] ?? []);
            }
            if (array_key_exists('included_product_ids', $relations)) {
                $saved->syncIncludedProductIds($relations['included_product_ids'] ?? []);
            }

            $jsonData = [];
            if (array_key_exists('itineraries', $relations)) {
                $jsonData['itinerary_data'] = $this->normalizeRows($relations['itineraries'] ?? [], 'title');
            }
            if (array_key_exists('excluded_items', $relations)) {
                $jsonData['excluded_items'] = $this->normalizeRows($relations['excluded_items'] ?? [], 'content');
            }
            if ($jsonData !== []) {
                $saved->update($jsonData);
            }

            Log::info('Admin product tour saved', ['product_id' => $saved->id]);

            return $saved;
        });
    }

    public function delete(Tour $tour): void
    {
        $this->repository->delete($tour);
        Log::info('Admin product tour deleted', ['product_id' => $tour->id]);
    }

    private function normalizeRows(array $rows, string $requiredKey): array
    {
        return collect($rows)
            ->filter(fn (array $row) => filled($row[$requiredKey] ?? null))
            ->values()
            ->map(fn (array $row, int $index) => $row + [
                'sort_order' => $index,
                'is_active' => true,
            ])
            ->all();
    }
}

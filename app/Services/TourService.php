<?php

namespace App\Services;

use App\Models\Tour;
use App\Repositories\TourRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TourService
{
    public function __construct(public TourRepository $repository) {}

    public function save(array $data, ?Tour $tour = null): Tour
    {
        return DB::transaction(function () use ($data, $tour) {
            $relations = Arr::only($data, [
                'itineraries',
                'excluded_items',
                'service_ids',
            ]);
            $main = Arr::except($data, array_keys($relations));
            $saved = $tour
                ? $this->repository->update($tour, $main)
                : $this->repository->create($main);
            $this->sync($saved, $relations);
            if (array_key_exists('service_ids', $relations)) {
                $saved->services()->sync($relations['service_ids'] ?? []);
            }
            Log::info('Admin tour saved', ['tour_id' => $saved->id]);

            return $saved;
        });
    }

    private function sync(Tour $tour, array $data): void
    {
        foreach (
            [
                'itineraries' => 'itineraries',
                'excluded_items' => 'excludedItems',
            ] as $key => $relation
        ) {
            if (! array_key_exists($key, $data)) {
                continue;
            }
            $tour->{$relation}()->delete();
            foreach (
                array_values(
                    array_filter(
                        $data[$key] ?? [],
                        fn ($row) => filled(
                            $row['content'] ?? ($row['title'] ?? null),
                        ),
                    ),
                ) as $i => $row
            ) {
                $tour
                    ->{$relation}()
                    ->create($row + ['sort_order' => $i, 'is_active' => true]);
            }
        }
    }

    public function delete(Tour $tour): void
    {
        $this->repository->delete($tour);
        Log::info('Admin tour deleted', ['tour_id' => $tour->id]);
    }
}

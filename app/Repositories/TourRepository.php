<?php

namespace App\Repositories;

use App\Models\Tour;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class TourRepository
{
    private array $relations = [
        'destination',
        'category',
        'image',
        'itineraries',
        'services',
        'excludedItems',
    ];

    public function featured(int $limit = 6): Collection
    {
        return Tour::with(['destination', 'image', 'services'])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->limit($limit)
            ->get();
    }

    public function activePaginated(int $perPage = 12): LengthAwarePaginator
    {
        return Tour::with(['destination', 'category', 'image', 'services'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->paginate($perPage);
    }

    public function findActiveBySlug(string $slug): Tour
    {
        return Tour::with($this->relations)
            ->where('is_active', true)
            ->where('slug', $slug)
            ->firstOrFail();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Tour::with(['destination', 'category'])
            ->orderBy('sort_order')
            ->paginate($perPage);
    }

    public function create(array $data): Tour
    {
        return Tour::create($data);
    }

    public function update(Tour $item, array $data): Tour
    {
        $item->update($data);

        return $item;
    }

    public function delete(Tour $item): void
    {
        $item->delete();
    }
}

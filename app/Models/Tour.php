<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Collection;

class Tour extends Product
{
    protected static function booted(): void
    {
        static::addGlobalScope('tour', fn (Builder $query) => $query->where('product_type', 'tour'));
        static::creating(fn (Tour $tour) => $tour->product_type = 'tour');
    }

    protected function services(): Attribute
    {
        return Attribute::get(function (): Collection {
            $ids = $this->categoryIdList();

            return $ids === []
                ? collect()
                : IncludedService::query()
                    ->whereIn('id', $ids)
                    ->orderBy('sort_order')
                    ->get();
        });
    }

    protected function itineraries(): Attribute
    {
        return Attribute::get(fn (): Collection => collect($this->itinerary_data ?? [])
            ->map(fn (array $item) => (object) $item));
    }

    protected function excludedItems(): Attribute
    {
        return Attribute::get(fn (): Collection => collect($this->excluded_items ?? [])
            ->map(fn (array $item) => (object) $item));
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Destination extends Product
{
    protected static function booted(): void
    {
        static::addGlobalScope('destination', fn (Builder $query) => $query->where('product_type', 'destination'));
        static::creating(fn (Destination $destination) => $destination->product_type = 'destination');
    }

    public function tours(): HasMany
    {
        return $this->hasMany(Tour::class, 'destination_id');
    }
}

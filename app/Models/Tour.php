<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tour extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'destination_id',
        'name',
        'slug',
        'short_description',
        'description',
        'duration_days',
        'duration_nights',
        'base_price',
        'original_price',
        'currency',
        'image_id',
        'badge',
        'is_featured',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'base_price' => 'decimal:2',
            'original_price' => 'decimal:2',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(TourCategory::class, 'category_id');
    }

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'image_id');
    }

    public function itineraries(): HasMany
    {
        return $this->hasMany(TourItinerary::class)
            ->orderBy('sort_order')
            ->orderBy('day_number');
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(
            IncludedService::class,
            'tour_service_assignments',
            'tour_id',
            'tour_service_id',
        )
            ->withTimestamps()
            ->orderBy('tour_services.sort_order');
    }

    public function excludedItems(): HasMany
    {
        return $this->hasMany(TourExcludedItem::class)->orderBy('sort_order');
    }

    public function consultations(): HasMany
    {
        return $this->hasMany(ConsultationRequest::class);
    }
}

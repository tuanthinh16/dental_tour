<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use SoftDeletes;

    protected $table = 'products';

    protected $fillable = [
        'product_code',
        'product_type',
        'destination_id',
        'name',
        'slug',
        'short_description',
        'description',
        'daily_price',
        'original_daily_price',
        'base_price',
        'original_price',
        'currency',
        'duration_days',
        'duration_nights',
        'badge',
        'category_ids',
        'included_product_ids',
        'itinerary_data',
        'excluded_items',
        'is_featured',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'daily_price' => 'decimal:2',
            'original_daily_price' => 'decimal:2',
            'itinerary_data' => 'array',
            'excluded_items' => 'array',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    protected function basePrice(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->daily_price,
            set: fn ($value) => ['daily_price' => $value],
        );
    }

    protected function originalPrice(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->original_daily_price,
            set: fn ($value) => ['original_daily_price' => $value],
        );
    }

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class, 'destination_id');
    }

    public function image(): HasOne
    {
        return $this->hasOne(ProductImage::class, 'product_code', 'product_code')
            ->where('image_type', 'list')
            ->orderBy('sort_order');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class, 'product_code', 'product_code')
            ->orderBy('sort_order');
    }

    public function categoryIdList(): array
    {
        return $this->csvIds($this->category_ids);
    }

    public function includedProductIdList(): array
    {
        return $this->csvIds($this->included_product_ids);
    }

    public function syncCategoryIds(array $ids): void
    {
        $this->forceFill(['category_ids' => $this->idsToCsv($ids)])->save();
    }

    public function syncIncludedProductIds(array $ids): void
    {
        $this->forceFill(['included_product_ids' => $this->idsToCsv($ids)])->save();
    }

    public static function makeUniqueCode(string $name, string $prefix = 'PRD'): string
    {
        $base = Str::upper(Str::slug($name, '_')) ?: 'ITEM';
        $base = $prefix.'_'.$base;
        $code = $base;
        $suffix = 2;

        while (self::withTrashed()->where('product_code', $code)->exists()) {
            $code = $base.'_'.$suffix++;
        }

        return $code;
    }

    private function csvIds(?string $value): array
    {
        if (blank($value)) {
            return [];
        }

        return collect(explode(',', $value))
            ->map(fn (string $id) => (int) trim($id))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    private function idsToCsv(array $ids): ?string
    {
        $value = collect($ids)
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->implode(',');

        return $value !== '' ? $value : null;
    }
}

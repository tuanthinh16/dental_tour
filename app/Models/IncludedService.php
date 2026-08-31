<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class IncludedService extends Model
{
    use HasTranslations, SoftDeletes;

    protected $table = 'categories';

    protected $fillable = [
        'category_code',
        'name',
        'description',
        'translations',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean', 'translations' => 'array'];
    }

    protected static function booted(): void
    {
        static::creating(function (IncludedService $service): void {
            if (blank($service->category_code)) {
                $service->category_code = static::uniqueCode($service->name);
            }
        });
    }

    private static function uniqueCode(string $name): string
    {
        $base = Str::upper(Str::slug($name, '_')) ?: 'SERVICE';
        $code = $base;
        $suffix = 2;

        while (static::withTrashed()->where('category_code', $code)->exists()) {
            $code = $base.'_'.$suffix++;
        }

        return $code;
    }
}

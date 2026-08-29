<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
class Destination extends Model
{
    use SoftDeletes;
    protected $fillable = [
        "name",
        "slug",
        "short_description",
        "description",
        "image_id",
        "sort_order",
        "is_active",
    ];
    protected function casts(): array
    {
        return ["is_active" => "boolean"];
    }
    public function tours(): HasMany
    {
        return $this->hasMany(Tour::class);
    }
    public function image(): BelongsTo
    {
        return $this->belongsTo(Media::class, "image_id");
    }
}
